# Agent Guidelines — Autopahala

## Purpose

This document provides instructions for AI coding assistants working on the Autopahala codebase. Follow these guidelines to maintain consistency and avoid introducing patterns that conflict with the existing architecture.

## Before Creating Code

### 1. Search Existing Implementation

Before writing new code, check if similar functionality already exists:

```
app/Services/          → Business logic (MidtransService is the reference implementation)
app/Http/Controllers/  → Route handlers
app/Models/            → Eloquent models with scopes and relationships
app/Policies/          → Authorization rules
app/Filament/          → Admin panel resources
```

### 2. Reuse Services

The project uses a Service Layer pattern. Check `app/Services/` before adding logic to controllers:

- `CampaignService.php` — Campaign business logic (scaffold)
- `DonationService.php` — Donation processing (scaffold)
- `WithdrawalService.php` — Withdrawal processing (scaffold)
- `MidtransService.php` — Payment gateway integration (implemented)

### 3. Reuse Model Scopes

The `Campaign` model has reusable query scopes:

```php
Campaign::active()          // campaign_status=active, verification_status=active, end_date>now
Campaign::byCategory($id)  // Filter by category
Campaign::approved()        // verification_status=active
```

The `Donation` model has:
```php
$campaign->donations()->successful()  // payment_status=success (referenced in controller)
```

### 4. Reuse Existing Components

- **Blade Components**: Check `resources/views/components/` before creating new ones
- **Filament Form Pattern**: Use the separated `Schemas/` and `Tables/` pattern
- **Auth Responses**: Custom responses exist in `app/Http/Responses/`

## Never Do

### ❌ Put Business Logic in Controllers

```php
// BAD - Logic in controller
public function store(Request $request)
{
    $donation = Donation::create([...]);
    $campaign->collected_amount += $donation->amount;
    $campaign->available_balance = $campaign->collected_amount - $campaign->withdrawal_amount;
    $campaign->save();
    // Send notification...
    // Log activity...
}

// GOOD - Delegate to service
public function store(StoreDonationRequest $request, Campaign $campaign)
{
    $donation = app(DonationService::class)->processDonation($request->validated(), $campaign);
    return redirect()->route('donations.pay', $donation);
}
```

### ❌ Duplicate Functionality

- Don't create a new payment service if `MidtransService` exists
- Don't add authorization checks inline if a Policy exists
- Don't create new query methods if a model scope covers it

### ❌ Bypass Policies

```php
// BAD - Manual auth check
if (auth()->id() !== $campaign->user_id) {
    abort(403);
}

// GOOD - Use policy
$this->authorize('update', $campaign);
```

### ❌ Query Database Inside Blade Templates

```php
// BAD - In Blade template
@foreach(Campaign::where('status', 'active')->get() as $campaign)

// GOOD - Pass from controller
// Controller:
$campaigns = Campaign::active()->get();
return view('campaigns.index', compact('campaigns'));
```

### ❌ Use Default `id` Primary Key Assumptions

This project uses custom primary keys. Always check the model:

```php
// Users: id_user
// Campaigns: id_campaign
// Donations: id_donation
// AdminLogs: id_log
// Categories: id (standard)
// Withdrawals: id (standard)
```

### ❌ Ignore the Dual Status System

Campaigns have THREE status columns. Use the correct one:
- `status` — Used by Filament admin (pending/approved/rejected)
- `campaign_status` — Lifecycle (draft/active/finished/closed/suspended)
- `verification_status` — Admin verification (draft/pending/active/rejected/expired)

## Always Do

### ✅ Use Form Requests for Validation

```php
// Create in app/Http/Requests/{Feature}/
class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Or use policy
    }

    public function rules(): array
    {
        return [
            'donor_name' => 'required|string|max:100',
            'donor_email' => 'required|email|max:100',
            'amount' => 'required|numeric|min:10000',
        ];
    }
}
```

### ✅ Use Policies for Authorization

```php
// Register in AuthServiceProvider or auto-discover
// Follow CampaignPolicy pattern:
public function update(User $user, Campaign $campaign): bool
{
    return $user->role === 'admin' || $campaign->user_id === $user->id_user;
}
```

### ✅ Follow Existing Filament Resource Pattern

```
app/Filament/Resources/{ModelName}/
├── {ModelName}Resource.php
├── Pages/
│   ├── Create{Model}.php
│   ├── Edit{Model}.php
│   └── List{Models}.php
├── Schemas/
│   └── {Model}Form.php          ← Static configure() method
└── Tables/
    └── {Models}Table.php        ← Static configure() method
```

### ✅ Use Indonesian Labels in Filament

All Filament UI labels are in Indonesian (Bahasa Indonesia):
- Navigation labels: "Kelola Kampanye", "Kelola Pengguna"
- Form labels: "Judul Kampanye", "Target Donasi"
- Status options: "Menunggu Persetujuan", "Disetujui", "Ditolak"

### ✅ Follow the Custom Primary Key Pattern

When creating relationships, always specify the custom keys:

```php
// In model
public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id', 'id_user');
}

// In migration
$table->foreign('id_user')
    ->references('id_user')->on('users')
    ->onDelete('cascade');
```

### ✅ Use Soft Deletes for Important Models

Users, Campaigns, and Donations all use `SoftDeletes`. New important models should too.

### ✅ Store Files in Organized Directories

```php
// Campaign images
$photo->store('campaigns', 'public');

// Profile photos
$photo->store('profile-photos', 'public');

// Cover photos
$photo->store('cover-photos', 'public');

// Campaign banners (Filament)
FileUpload::make('banner_image')->directory('campaign-banners');
```

### ✅ Log Admin Actions

When implementing admin actions, log them:

```php
AdminLog::create([
    'admin_id' => auth()->id(),
    'activity' => "Approved campaign: {$campaign->title}",
]);
```

### ✅ Use Role-Based Redirects

After authentication actions, redirect based on role:

```php
if (auth()->user()->role === 'admin') {
    return redirect('/admin');
}
return redirect('/dashboard');
```

## Coding Patterns Reference

### Controller Pattern

```php
class FeatureController extends Controller
{
    public function index()
    {
        $items = Model::active()->paginate(12);
        return view('feature.index', compact('items'));
    }

    public function store(StoreFeatureRequest $request)
    {
        $validated = $request->validated();
        // Handle file uploads
        // Set defaults
        Model::create($validated);
        return redirect()->route('feature.index')->with('success', 'Berhasil dibuat!');
    }

    public function edit(Model $model)
    {
        $this->authorize('update', $model);
        return view('feature.edit', compact('model'));
    }
}
```

### Service Pattern (Reference: MidtransService)

```php
class FeatureService
{
    public function processAction(array $data, Model $model): Result
    {
        // Business logic here
        // Database operations
        // External API calls
        // Return result
    }
}
```

### Model Pattern

```php
class NewModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_model';  // If custom PK

    protected $fillable = [...];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}
```

## File Naming Conventions

| Type | Convention | Example |
|------|-----------|---------|
| Model | Singular PascalCase | `Campaign.php` |
| Controller | Singular + Controller | `CampaignController.php` |
| Form Request | Action + Model + Request | `StoreCampaignRequest.php` |
| Policy | Model + Policy | `CampaignPolicy.php` |
| Service | Model + Service | `CampaignService.php` |
| Migration | timestamp_action_table | `2026_05_15_create_campaigns_table.php` |
| Seeder | Model + Seeder | `CategorySeeder.php` |
| Filament Form | Model + Form | `CampaignForm.php` |
| Filament Table | Models + Table | `CampaignsTable.php` |

## Common Gotchas

1. **User PK is `id_user`**, not `id` — always use `auth()->user()->id_user` for user FK
2. **Campaign has 3 status columns** — use the right one for context
3. **Categories migration uses `$table->id()`** but campaigns FK references `id_category` — there's a mismatch
4. **Donation model uses different column names** than the migration (`campaign_id` vs `id_campaign`)
5. **HomeController uses hardcoded data** — not connected to database yet
6. **Form Requests are scaffolded but empty** — `authorize()` returns `false` by default
