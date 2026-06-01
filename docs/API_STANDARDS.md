# API Standards — Autopahala

## Current Architecture

Autopahala is primarily a **server-rendered application** using Blade templates. There is no dedicated REST API layer yet. However, the project includes Laravel Sanctum for API token management, indicating future API support is planned.

## Route Conventions

### URL Structure

| Pattern | Example | Purpose |
|---------|---------|---------|
| `/` | Homepage | Public landing |
| `/{page}` | `/about`, `/faq` | Static pages |
| `/campaigns` | Campaign listing | Resource index |
| `/campaigns/{slug}` | `/campaigns/bantu-korban-banjir` | Resource show (by slug) |
| `/@{username}` | `/@raymond-smt` | Public user profile |
| `/auth/{provider}` | `/auth/google` | OAuth redirect |
| `/auth/{provider}/callback` | `/auth/google/callback` | OAuth callback |
| `/dashboard` | User dashboard | Authenticated area |
| `/admin` | Filament panel | Admin area |

### Route Naming Convention

```php
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/@{username}', [ProfileController::class, 'show'])->name('profile.show.public');
Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('social.login');
```

**Pattern**: `{resource}.{action}` or `{feature}.{action}.{modifier}`

### HTTP Methods

| Method | Usage |
|--------|-------|
| GET | Display pages, list resources |
| POST | Create resources, process forms |
| PUT/PATCH | Update resources |
| DELETE | Remove resources |

## Controller Response Patterns

### Success Responses (Web)

```php
// Redirect with flash message
return redirect()->route('campaigns.index')
    ->with('success', 'Campaign berhasil dibuat!');

// Redirect to specific resource
return redirect()->route('campaigns.show', $campaign->slug)
    ->with('success', 'Campaign berhasil diupdate!');
```

### Error Responses (Web)

```php
// Validation errors (automatic via Form Request or validate())
// Returns back with errors in session

// Authorization failure
$this->authorize('update', $campaign);  // Throws 403

// Not found
Campaign::where('slug', $slug)->firstOrFail();  // Throws 404
```

## Planned API Endpoints (Future)

When implementing a REST API, follow these standards:

### Endpoint Structure

```
GET    /api/v1/campaigns              → List campaigns
GET    /api/v1/campaigns/{id}         → Show campaign
POST   /api/v1/campaigns              → Create campaign
PUT    /api/v1/campaigns/{id}         → Update campaign
DELETE /api/v1/campaigns/{id}         → Delete campaign

POST   /api/v1/campaigns/{id}/donate  → Create donation
GET    /api/v1/donations/{id}         → Check donation status

POST   /api/v1/midtrans/webhook       → Payment webhook (no auth)
```

### Response Format (Recommended)

```json
{
    "success": true,
    "message": "Campaign created successfully",
    "data": {
        "id": 1,
        "title": "Bantu Korban Banjir",
        "slug": "bantu-korban-banjir",
        "target_amount": 100000000,
        "collected_amount": 0,
        "status": "pending"
    }
}
```

### Error Response Format

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "title": ["The title field is required."],
        "target_amount": ["The target amount must be at least 100000."]
    }
}
```

### Authentication (Sanctum)

```php
// API routes should use Sanctum middleware
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('campaigns', Api\CampaignController::class);
});
```

## Webhook Endpoint Standards

### Midtrans Webhook

```
POST /api/midtrans/webhook
```

**Requirements**:
- No authentication (Midtrans sends requests)
- No CSRF protection
- Signature verification via SHA-512 hash
- Idempotent (handle duplicate notifications)
- Return 200 OK quickly (process async if needed)

**Response**:
```json
{"status": "ok"}
```

## Pagination Standard

### Current Implementation

```php
$campaigns = Campaign::active()->paginate(12);
```

### Blade Pagination

```blade
{{ $campaigns->links() }}
```

### API Pagination (Future)

```json
{
    "data": [...],
    "meta": {
        "current_page": 1,
        "per_page": 12,
        "total": 48,
        "last_page": 4
    },
    "links": {
        "first": "/api/v1/campaigns?page=1",
        "last": "/api/v1/campaigns?page=4",
        "next": "/api/v1/campaigns?page=2",
        "prev": null
    }
}
```

## Validation Standards

### Monetary Values

- Always use `numeric` type
- Minimum amounts enforced (100,000 for target, 10,000 for donation)
- Store as `DECIMAL(15,2)` in database
- Display with Indonesian format: `Rp 100.000.000`

### String Lengths

| Field | Max Length | Reason |
|-------|-----------|--------|
| Title | 255 | Standard varchar |
| Short description | 500 | Preview text |
| Email | 100 | Standard email |
| Phone | 20 | International format |
| Name | 100 | Display name |
| URL | 500 | Video/social links |

### File Uploads

| Type | Max Size | Allowed MIME |
|------|----------|-------------|
| Campaign image | 2MB | jpeg, png, jpg, gif |
| Profile photo | (Filament default) | image/* |
| Cover photo | (Filament default) | image/* |

## Slug Convention

Slugs are used for public-facing URLs:

```php
// Generation
$validated['slug'] = Str::slug($validated['title']);

// Lookup
Campaign::where('slug', $slug)->firstOrFail();
```

**Rules**:
- Auto-generated from title
- Unique constraint in database
- Used in public URLs instead of numeric IDs
- Filament auto-generates on create via `afterStateUpdated`
