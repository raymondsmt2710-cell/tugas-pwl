# Security Guidelines — Autopahala

## Authorization Rules

### Role-Based Access Control (RBAC)

The system uses a simple two-role model stored in `users.role`:

| Role | Public Site | User Dashboard | Admin Panel |
|------|-------------|---------------|-------------|
| `user` | ✅ Full access | ✅ Own data only | ❌ Blocked |
| `admin` | ✅ Full access | ✅ Redirected to admin | ✅ Full access |

### Panel Access Control

```php
// User model - controls Filament panel access
public function canAccessPanel(Panel $panel): bool
{
    return $this->role === 'admin';
}
```

### Resource-Level Access

Every Filament resource restricts visibility:

```php
public static function canViewAny(): bool
{
    return auth()->user()->role === 'admin';
}
```

### Data Scoping (Multi-Tenancy Pattern)

CampaignResource scopes queries for non-admin users:

```php
public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    if (auth()->user()->role !== 'admin') {
        $query->where('user_id', auth()->id());
    }
    return $query;
}
```

## Policy Usage

### CampaignPolicy (Implemented)

| Method | Rule |
|--------|------|
| `viewAny` | Everyone (true) |
| `view` | Admin OR owner |
| `create` | Everyone (true) |
| `update` | Admin OR owner |
| `delete` | Admin OR owner (if pending status) |
| `restore` | Admin only |
| `forceDelete` | Admin only |

### Usage in Controllers

```php
// Explicit authorization check
$this->authorize('update', $campaign);

// In route model binding
Route::resource('campaigns', CampaignController::class)->middleware('can:update,campaign');
```

### Policies Needed (Not Yet Implemented)

- `DonationPolicy` — Who can view/refund donations
- `WithdrawalPolicy` — Who can request/approve withdrawals
- `UserPolicy` — Who can suspend/verify users

## Input Validation

### Controller-Level Validation (Current Pattern)

```php
// CampaignController@store
$validated = $request->validate([
    'title' => 'required|string|max:255',
    'short_description' => 'required|string|max:500',
    'description' => 'required|string',
    'target_amount' => 'required|numeric|min:100000',
    'minimum_donation' => 'nullable|numeric|min:10000',
    'id_category' => 'required|exists:categories,id',
    'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    'video_url' => 'nullable|url',
    'end_date' => 'required|date|after:today',
]);
```

### Form Request Classes (Scaffolded)

Form Request classes exist but are not yet implemented:
- `StoreCampaignRequest` — `authorize()` returns `false` (needs fix)
- `UpdateCampaignRequest` — `authorize()` returns `false` (needs fix)
- `StoreDonationRequest` — Empty rules
- `StoreWithdrawalRequest` — Empty rules

**Important**: The scaffolded Form Requests have `authorize()` returning `false`, which will block ALL requests. Fix before using.

### Filament Form Validation

Filament forms use inline validation:

```php
TextInput::make('title')
    ->required()
    ->maxLength(255)

TextInput::make('target_amount')
    ->required()
    ->numeric()
    ->minValue(1000)

TextInput::make('email')
    ->email()
    ->required()
    ->unique(ignoreRecord: true)
```

## File Upload Security

### Current Implementation

```php
// Controller upload
$image = $request->file('image');
$path = $image->store('campaigns', 'public');

// Filament upload
FileUpload::make('banner_image')
    ->image()                          // Only image MIME types
    ->directory('campaign-banners')    // Organized directory
```

### Validation Rules Applied

| Rule | Purpose |
|------|---------|
| `image` | Validates MIME type is an image |
| `mimes:jpeg,png,jpg,gif` | Restricts to specific formats |
| `max:2048` | Limits file size to 2MB |

### Recommendations for Enhancement

1. **Virus scanning**: Consider integrating ClamAV for uploaded files
2. **Image processing**: Resize/compress images after upload
3. **Private storage**: Sensitive documents should use `private` disk
4. **Filename sanitization**: Laravel handles this, but verify no path traversal
5. **Content-Type validation**: Don't rely solely on extension; check actual MIME

## Payment Security

### Server Key Protection

- Server key stored in `.env` (never committed to git)
- Only used server-side in `MidtransService`
- Client key is safe for frontend (Snap.js)

### Webhook Security (To Implement)

```php
// Verify webhook signature
$serverKey = config('midtrans.server_key');
$hashed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

if ($hashed !== $notification->signature_key) {
    return response()->json(['error' => 'Invalid signature'], 403);
}
```

### Amount Validation

Always verify the amount in webhook matches the stored donation:

```php
if ($notification->gross_amount != $donation->amount) {
    Log::warning('Amount mismatch', [...]);
    return response()->json(['error' => 'Amount mismatch'], 400);
}
```

### Idempotency

Handle duplicate webhook notifications:

```php
if ($donation->payment_status === 'success') {
    return response()->json(['status' => 'already processed']);
}
```

## CSRF Protection

### Enabled by Default

Laravel's `PreventRequestForgery` middleware is active on all web routes. The Filament panel also includes it:

```php
// AdminPanelProvider middleware stack
PreventRequestForgery::class,
```

### Webhook Exception

The Midtrans webhook endpoint must be excluded from CSRF verification since it receives POST requests from Midtrans servers:

```php
// In VerifyCsrfToken middleware (or route definition)
// Use api routes or exclude the webhook URL
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\PreventRequestForgery::class]);
```

## XSS Prevention

### Blade Auto-Escaping

All Blade `{{ }}` output is automatically escaped:

```blade
{{ $campaign->title }}          <!-- Escaped -->
{!! $campaign->description !!}  <!-- RAW - use only for trusted HTML (RichEditor content) -->
```

### RichEditor Content

The Filament RichEditor stores HTML. When displaying:
- Use `{!! !!}` for rendering
- Consider sanitizing with HTMLPurifier before storage
- The RichEditor toolbar is restricted to safe elements:

```php
->toolbarButtons([
    'blockquote', 'bold', 'bulletList', 'codeBlock',
    'heading', 'italic', 'link', 'orderedList',
    'redo', 'strike', 'undo',
])
```

No `<script>`, `<iframe>`, or `<style>` tags are available in the toolbar.

## Authentication Security

### Rate Limiting

```php
// Login: 5 attempts per minute per email+IP
RateLimiter::for('login', function (Request $request) {
    $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());
    return Limit::perMinute(5)->by($throttleKey);
});

// Two-factor: 5 attempts per minute per session
RateLimiter::for('two-factor', function (Request $request) {
    return Limit::perMinute(5)->by($request->session()->get('login.id'));
});

// Passkeys: 10 attempts per minute
RateLimiter::for('passkeys', function (Request $request) {
    return Limit::perMinute(10)->by(($credentialId ?: $request->session()->getId()).'|'.$request->ip());
});
```

### Password Security

```php
// From PasswordValidationRules trait (Fortify)
// Enforces: min 8 chars, mixed case, numbers, symbols (configurable)
```

### Session Security

- Database-backed sessions (not file-based)
- `AuthenticateSession` middleware prevents session fixation
- Sessions track IP and user agent

### OAuth Security

- OAuth state parameter handled by Socialite (CSRF protection)
- Random 24-char password generated for OAuth users (prevents password login without explicit set)
- Email from OAuth provider is trusted (marked as verified)

## Soft Deletes

Critical models use soft deletes to prevent data loss:
- `User` — `SoftDeletes` trait
- `Campaign` — `SoftDeletes` trait
- `Donation` — `deleted_at` column in migration

This ensures:
- Audit trail is maintained
- Related data integrity preserved
- Admin can restore accidentally deleted records

## Security Checklist for New Features

- [ ] Add Policy for new model
- [ ] Use Form Request with proper `authorize()` method
- [ ] Validate all user input (type, length, format)
- [ ] Escape output in Blade templates
- [ ] Use `$fillable` (not `$guarded = []`) on models
- [ ] Store sensitive config in `.env`
- [ ] Add rate limiting for public-facing endpoints
- [ ] Use HTTPS for external API calls
- [ ] Log security-relevant actions to `admin_logs`
- [ ] Test authorization with different user roles
