# Codebase Map — Autopahala

## Folder Structure

```
tugas-pwl/
├── app/
│   ├── Actions/
│   │   ├── Fortify/              # Auth actions (register, password reset)
│   │   └── Jetstream/            # User deletion action
│   ├── Filament/
│   │   └── Resources/            # Admin panel resources (CRUD)
│   │       ├── Campaigns/        # Campaign management
│   │       ├── Categories/       # Category management
│   │       ├── Donations/        # Donation management
│   │       ├── Users/            # User management
│   │       └── Withdrawals/      # Withdrawal management
│   ├── Http/
│   │   ├── Controllers/          # Route handlers
│   │   │   └── Auth/             # OAuth controller
│   │   ├── Requests/             # Form request validation (scaffolded)
│   │   │   ├── Campaign/
│   │   │   ├── Donation/
│   │   │   └── Withdrawal/
│   │   └── Responses/            # Custom auth responses
│   ├── Models/                   # Eloquent models
│   ├── Policies/                 # Authorization policies
│   ├── Providers/                # Service providers
│   │   └── Filament/             # Filament panel provider
│   └── Services/                 # Business logic layer (scaffolded)
├── config/
│   ├── midtrans.php              # Midtrans configuration (empty)
│   ├── services.php              # OAuth credentials
│   └── ...                       # Standard Laravel configs
├── database/
│   ├── factories/                # Model factories
│   ├── migrations/               # Database schema
│   └── seeders/                  # Test data seeders
├── resources/
│   ├── css/                      # Tailwind CSS
│   ├── js/                       # JavaScript (Vite)
│   └── views/                    # Blade templates
├── routes/
│   ├── web.php                   # All web routes
│   └── console.php               # Artisan commands
├── public/                       # Web root
├── storage/                      # File uploads, logs, cache
└── tests/                        # PHPUnit tests
```

## Responsibility of Each Directory

### `app/Actions/Fortify/`
Handles authentication lifecycle actions required by Laravel Fortify:
- `CreateNewUser.php` — User registration with auto-generated username
- `UpdateUserProfileInformation.php` — Profile updates
- `UpdateUserPassword.php` — Password changes
- `ResetUserPassword.php` — Password reset flow
- `PasswordValidationRules.php` — Shared password validation trait

### `app/Actions/Jetstream/`
- `DeleteUser.php` — User account deletion

### `app/Filament/Resources/`
Each resource follows a consistent sub-directory pattern:
- `{Resource}Resource.php` — Main resource class (model binding, navigation, access control)
- `Pages/` — List, Create, Edit page classes
- `Schemas/` — Form component definitions (separated from resource)
- `Tables/` — Table column/filter definitions (separated from resource)

### `app/Http/Controllers/`
| Controller | Responsibility |
|-----------|---------------|
| `HomeController` | Homepage with featured campaigns (currently hardcoded) |
| `CampaignController` | Full CRUD for campaigns (public-facing) |
| `DonationController` | Donation processing (empty scaffold) |
| `WithdrawalController` | Withdrawal requests (empty scaffold) |
| `MidtransWebhookController` | Payment webhook handler (empty scaffold) |
| `ProfileController` | Public user profile display |
| `DashboardController` | User dashboard (empty scaffold) |
| `Auth/SocialiteController` | Google/GitHub OAuth flow |

### `app/Http/Responses/`
Custom authentication response overrides:
- `LoginResponse.php` — Role-based redirect after login
- `LogoutResponse.php` — Redirect to `/login` after logout
- `FilamentLogoutResponse.php` — Redirect to `/login` from admin panel

### `app/Models/`
| Model | Primary Key | Key Relationships |
|-------|------------|-------------------|
| `User` | `id_user` | hasMany(AdminLog), campaigns, donations |
| `Campaign` | `id_campaign` | belongsTo(User, Category), hasMany(Donation) |
| `Category` | `id` | hasMany(Campaign) |
| `Donation` | `id_donation` | belongsTo(Campaign, User) |
| `Withdrawal` | `id` | (empty model) |
| `AdminLog` | `id_log` | belongsTo(User as admin) |

### `app/Policies/`
- `CampaignPolicy.php` — Owner/admin authorization for campaign CRUD

### `app/Services/`
Business logic layer (currently scaffolded, not implemented):
- `CampaignService.php` — Campaign business logic
- `DonationService.php` — Donation processing logic
- `WithdrawalService.php` — Withdrawal processing logic
- `MidtransService.php` — **Implemented**: Creates Snap transactions

### `app/Providers/`
- `AppServiceProvider.php` — Binds custom Filament logout response
- `FortifyServiceProvider.php` — Configures auth actions, rate limiting, custom login/logout responses
- `JetstreamServiceProvider.php` — Jetstream configuration
- `Filament/AdminPanelProvider.php` — Filament panel setup (path, colors, middleware, auto-discovery)

## Key Files

| File | Purpose |
|------|---------|
| `routes/web.php` | All application routes (public, auth, profile) |
| `app/Providers/Filament/AdminPanelProvider.php` | Filament panel configuration |
| `app/Providers/FortifyServiceProvider.php` | Auth configuration and custom responses |
| `app/Models/Campaign.php` | Core business model with scopes and relationships |
| `app/Models/User.php` | User model with Filament access, custom PK, accessors |
| `app/Policies/CampaignPolicy.php` | Authorization rules |
| `app/Services/MidtransService.php` | Payment gateway integration |
| `database/seeders/DatabaseSeeder.php` | Default admin and test user |
| `database/seeders/CategorySeeder.php` | Default campaign categories |

## Entry Points

1. **Public Website**: `routes/web.php` → Controllers → Blade views
2. **Admin Panel**: `/admin` → Filament auto-discovery → Resources
3. **OAuth Callback**: `/auth/{provider}/callback` → `SocialiteController`
4. **Payment Webhook**: `/midtrans/webhook` (planned) → `MidtransWebhookController`

## Dependencies Between Modules

```
User ──────────────┐
  │                │
  ├── Campaign ────┼── Category
  │     │          │
  │     ├── Donation
  │     │
  │     └── Withdrawal
  │
  └── AdminLog

MidtransService ──── Donation ──── Campaign
```

**Key Dependency Rules**:
- Campaigns always belong to a User and a Category
- Donations reference a Campaign and optionally a User
- Withdrawals are tied to Campaigns (via available_balance)
- AdminLog tracks admin user activities
- MidtransService operates on Donation records
