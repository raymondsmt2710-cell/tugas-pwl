# Architecture — Autopahala

## Overall System Architecture

Autopahala follows a **monolithic Laravel architecture** with clear separation between the public-facing website and the admin panel (Filament). The system uses the Service Layer pattern (scaffolded but not yet fully implemented) to encapsulate business logic.

```
┌─────────────────────────────────────────────────────────────────┐
│                         HTTP Layer                                │
│  ┌─────────────────┐  ┌──────────────────┐  ┌───────────────┐  │
│  │  Public Routes  │  │ Authenticated    │  │ Filament /admin│  │
│  │  (web.php)      │  │ Routes (web.php) │  │ (auto-discover)│  │
│  └────────┬────────┘  └────────┬─────────┘  └───────┬───────┘  │
│           │                    │                     │           │
│  ┌────────▼────────────────────▼─────────────────────▼───────┐  │
│  │                    Middleware Stack                         │  │
│  │  CSRF → Session → Auth → Verified → SubstituteBindings    │  │
│  └────────────────────────────┬──────────────────────────────┘  │
│                               │                                  │
│  ┌────────────────────────────▼──────────────────────────────┐  │
│  │                     Controllers                            │  │
│  │  HomeController, CampaignController, ProfileController,    │  │
│  │  DonationController, WithdrawalController,                 │  │
│  │  MidtransWebhookController, SocialiteController            │  │
│  └────────────────────────────┬──────────────────────────────┘  │
│                               │                                  │
│  ┌────────────────────────────▼──────────────────────────────┐  │
│  │                   Service Layer (Planned)                  │  │
│  │  CampaignService, DonationService, WithdrawalService,      │  │
│  │  MidtransService                                           │  │
│  └────────────────────────────┬──────────────────────────────┘  │
│                               │                                  │
│  ┌────────────────────────────▼──────────────────────────────┐  │
│  │                    Eloquent Models                          │  │
│  │  User, Campaign, Category, Donation, Withdrawal, AdminLog  │  │
│  └────────────────────────────┬──────────────────────────────┘  │
│                               │                                  │
│  ┌────────────────────────────▼──────────────────────────────┐  │
│  │                      MySQL Database                         │  │
│  └───────────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────────┘
```

## Laravel Request Lifecycle

1. **Entry Point**: `public/index.php` → Bootstrap Laravel
2. **Middleware**: Global middleware (CSRF, session, cookies)
3. **Routing**: `routes/web.php` dispatches to controllers
4. **Controller**: Validates input, calls services/models
5. **Policy**: Authorization checked via `$this->authorize()`
6. **Model**: Eloquent ORM interacts with MySQL
7. **View**: Blade templates rendered with Tailwind CSS
8. **Response**: HTML returned to browser

## Public Website Architecture

The public site uses traditional Blade templates with Tailwind CSS:

```
routes/web.php
    │
    ├── GET /              → HomeController@index      → home.blade.php
    ├── GET /about         → Static view               → about.blade.php
    ├── GET /contact       → Static view               → contact.blade.php
    ├── GET /faq           → Static view               → faq.blade.php
    ├── GET /campaigns     → CampaignController@index  → campaigns/index.blade.php
    ├── GET /campaigns/{slug} → CampaignController@show → campaigns/show.blade.php
    └── GET /@{username}   → ProfileController@show    → profile/public-show.blade.php
```

**Key Pattern**: The `HomeController` currently uses hardcoded campaign data for the homepage. This is a placeholder that should be replaced with actual database queries.

## Filament Dashboard Architecture

The admin panel is powered by Filament 4 and auto-discovered from `app/Filament/`:

```
/admin (Filament Panel)
    │
    ├── Dashboard (default Filament dashboard)
    │
    ├── Navigation Group: "Donasi & Kampanye"
    │   ├── CampaignResource  → Kelola Kampanye
    │   ├── CategoryResource  → Kelola Kategori
    │   ├── DonationResource  → Kelola Donasi
    │   └── WithdrawalResource → Kelola Penarikan Dana
    │
    └── Navigation Group: "Manajemen Sistem"
        └── UserResource      → Kelola Pengguna
```

**Resource Structure Pattern** (consistent across all resources):

```
app/Filament/Resources/{ResourceName}/
    ├── {ResourceName}Resource.php    ← Main resource class
    ├── Pages/
    │   ├── Create{Model}.php         ← Create page
    │   ├── Edit{Model}.php           ← Edit page
    │   └── List{Models}.php          ← List/index page
    ├── Schemas/
    │   └── {Model}Form.php           ← Form schema (separated)
    └── Tables/
        └── {Models}Table.php         ← Table schema (separated)
```

**Access Control**: All Filament resources use `canViewAny()` to restrict access to admin users only:

```php
public static function canViewAny(): bool
{
    return auth()->user()->role === 'admin';
}
```

## Authentication Flow

```
┌──────────┐     ┌─────────────┐     ┌──────────────────┐
│  User    │────▶│  /login     │────▶│  Fortify Auth    │
│          │     │  /register  │     │  (rate limited)  │
└──────────┘     └─────────────┘     └────────┬─────────┘
                                              │
                 ┌─────────────┐              │
                 │  OAuth      │◀─────────────┤
                 │  Google/    │              │
                 │  GitHub     │              │
                 └──────┬──────┘              │
                        │                     │
                        ▼                     ▼
              ┌─────────────────────────────────────┐
              │         LoginResponse               │
              │  if (admin) → redirect('/admin')    │
              │  else       → redirect('/dashboard')│
              └─────────────────────────────────────┘
```

**Custom Responses**:
- `LoginResponse`: Redirects admin to `/admin`, users to `/dashboard`
- `LogoutResponse`: Redirects to `/login` (not homepage)
- `FilamentLogoutResponse`: Also redirects to `/login`

**OAuth Flow** (`SocialiteController`):
1. User clicks "Login with Google/GitHub"
2. Redirect to provider → callback URL
3. Find existing user by provider ID or email
4. If found: update provider ID if missing, login
5. If not found: create new user with auto-generated username, login
6. Redirect based on role (admin → `/admin`, user → `/dashboard`)

## Authorization Flow

Authorization uses **Laravel Policies**:

```php
// In CampaignController
$this->authorize('update', $campaign);
```

**CampaignPolicy Rules**:
| Action | Admin | Owner | Other Users |
|--------|-------|-------|-------------|
| viewAny | ✅ | ✅ | ✅ |
| view | ✅ | ✅ | ❌ |
| create | ✅ | ✅ | ✅ |
| update | ✅ | ✅ | ❌ |
| delete | ✅ | ✅ (if pending) | ❌ |
| restore | ✅ | ❌ | ❌ |
| forceDelete | ✅ | ❌ | ❌ |

## Donation Flow (Planned Architecture)

```
┌──────┐    ┌──────────────┐    ┌────────────────┐    ┌──────────┐
│Donor │───▶│DonationController│──▶│MidtransService │───▶│ Midtrans │
│      │    │  store()     │    │createTransaction│    │  Snap    │
└──────┘    └──────────────┘    └────────────────┘    └────┬─────┘
                                                           │
                                                           ▼
┌──────────────────┐    ┌────────────────────┐    ┌──────────────┐
│ Update Campaign  │◀───│MidtransWebhook     │◀───│  Webhook     │
│ collected_amount │    │Controller@handle   │    │  Callback    │
└──────────────────┘    └────────────────────┘    └──────────────┘
```

**Transaction Statuses**: `pending` → `success` | `failed` | `cancelled`

## Campaign Verification Flow

```
┌────────┐     ┌─────────┐     ┌──────────┐     ┌──────────┐
│ Draft  │────▶│ Pending │────▶│ Approved │────▶│ Finished │
│        │     │ Review  │     │ (Active) │     │          │
└────────┘     └────┬────┘     └──────────┘     └──────────┘
                    │
                    ▼
              ┌──────────┐
              │ Rejected │
              └──────────┘
```

**Status Fields** (campaigns table has dual status tracking):
- `campaign_status`: `draft` | `active` | `finished` | `closed` | `suspended`
- `verification_status`: `draft` | `pending` | `active` | `rejected` | `expired`
- `status`: `pending` | `approved` | `rejected` (simplified, used in Filament)

## Notification Flow (Planned)

Not yet implemented. Planned architecture:
- Laravel Notifications with database + mail channels
- Notify campaign owner on new donation
- Notify donor on payment status change
- Notify user on campaign approval/rejection

## Reporting Flow (Planned)

Not yet implemented. Will require:
- `reports` table with `reporter_id`, `campaign_id`, `reason`, `status`
- Admin review workflow in Filament
- Campaign suspension on threshold
