# Filament Architecture — Autopahala

## Overview

The admin panel is built with **Filament 4.11** and accessible at `/admin`. It provides full CRUD management for all platform entities. Only users with `role = 'admin'` can access the panel.

## Panel Configuration

**File**: `app/Providers/Filament/AdminPanelProvider.php`

```php
return $panel
    ->default()
    ->id('admin')
    ->path('admin')           // URL: /admin
    ->login()                 // Built-in login page
    ->colors([
        'primary' => Color::Amber,  // Brand color
    ])
    ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
    ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
    ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
```

**Key Configuration**:
- Auto-discovers resources, pages, and widgets from `app/Filament/`
- Uses Filament's built-in login (separate from Jetstream login)
- Primary color: Amber
- Default widgets: AccountWidget, FilamentInfoWidget

## Access Control

Access is controlled at two levels:

### 1. Panel Level (User Model)

```php
// app/Models/User.php
public function canAccessPanel(Panel $panel): bool
{
    return $this->role === 'admin';
}
```

### 2. Resource Level (Each Resource)

```php
// Every resource implements:
public static function canViewAny(): bool
{
    return auth()->user()->role === 'admin';
}
```

## Navigation Groups

| Group | Resources |
|-------|-----------|
| **Donasi & Kampanye** | CampaignResource, CategoryResource, DonationResource, WithdrawalResource |
| **Manajemen Sistem** | UserResource |

## Resources

### CampaignResource

**File**: `app/Filament/Resources/Campaigns/CampaignResource.php`

| Property | Value |
|----------|-------|
| Model | `Campaign` |
| Icon | `heroicon-o-gift` |
| Label | Kampanye |
| Navigation | Kelola Kampanye |
| Group | Donasi & Kampanye |

**Special Behavior**: Non-admin users only see their own campaigns:

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

**Form Fields** (from `CampaignForm.php`):
- Section: "Detail Kampanye" (aside layout)
  - `title` — TextInput, required, auto-generates slug on create
  - `slug` — TextInput, required, unique
  - `target_amount` — TextInput, numeric, prefix "Rp", min 1000
  - `status` — Select (pending/approved/rejected), **disabled for non-admin**
- Section: "Konten & Media" (aside layout)
  - `banner_image` — FileUpload, image, directory: campaign-banners
  - `description` — RichEditor with toolbar buttons

**Table Columns** (from `CampaignsTable.php`):
- `banner_image` — ImageColumn (square)
- `title` — searchable, sortable, wrapped
- `user.full_name` — Penggalang Dana
- `target_amount` — money format (IDR)
- `current_amount` — money format (IDR)
- `status` — badge with colors (approved=success, pending=warning, rejected=danger)
- `created_at` — hidden by default

**Table Filters**: SelectFilter on `status`

---

### CategoryResource

**File**: `app/Filament/Resources/Categories/CategoryResource.php`

| Property | Value |
|----------|-------|
| Model | `Category` |
| Icon | `heroicon-o-tag` |
| Label | Kategori |
| Navigation | Kelola Kategori |
| Group | Donasi & Kampanye |

**Form**: Empty scaffold (needs implementation)
**Table**: Empty scaffold (needs implementation)

---

### DonationResource

**File**: `app/Filament/Resources/Donations/DonationResource.php`

| Property | Value |
|----------|-------|
| Model | `Donation` |
| Icon | `heroicon-o-banknotes` |
| Label | Donasi |
| Navigation | Kelola Donasi |
| Group | Donasi & Kampanye |

**Form**: Empty scaffold (needs implementation)
**Table**: Empty scaffold (needs implementation)

---

### WithdrawalResource

**File**: `app/Filament/Resources/Withdrawals/WithdrawalResource.php`

| Property | Value |
|----------|-------|
| Model | `Withdrawal` |
| Icon | `heroicon-o-credit-card` |
| Label | Penarikan Dana |
| Navigation | Kelola Penarikan Dana |
| Group | Donasi & Kampanye |

**Form**: Empty scaffold (needs implementation)
**Table**: Empty scaffold (needs implementation)

---

### UserResource

**File**: `app/Filament/Resources/Users/UserResource.php`

| Property | Value |
|----------|-------|
| Model | `User` |
| Icon | `heroicon-o-users` |
| Label | Pengguna |
| Navigation | Kelola Pengguna |
| Group | Manajemen Sistem |

**Form Fields** (from `UserForm.php`):
- Section: "Informasi Utama" (aside layout)
  - `full_name` — required, max 100
  - `username` — unique
  - `email` — required, unique, email format
  - `phone_number` — tel format
  - `password` — dehydrated only when filled, required on create only
  - `role` — Select (admin/user)
  - `account_status` — Select (active/suspended/pending)
  - `is_verified` — Toggle (blue checkmark)
- Section: "Informasi Tambahan & Profil" (aside layout)
  - `bio` — Textarea
  - `address` — Textarea
  - `profile_photo` — FileUpload, directory: profile-photos
  - `cover_photo_path` — FileUpload, directory: cover-photos
  - `social_links` — KeyValue component
- Section: "Integrasi Single Sign-On" (collapsed, aside)
  - `google_id` — disabled, read-only
  - `github_id` — disabled, read-only

**Table Columns** (from `UsersTable.php`):
- `profile_photo` — circular ImageColumn with UI Avatars fallback
- `full_name` — searchable, sortable
- `username` — searchable, copyable
- `email` — searchable, copyable
- `phone_number` — searchable
- `role` — badge (admin=danger, user=success)
- `account_status` — badge (active=success, suspended=danger, pending=warning)
- `is_verified` — IconColumn (boolean)
- `created_at` / `updated_at` — hidden by default

**Table Filters**: SelectFilter on `role`, SelectFilter on `account_status`

## Pages

Each resource has three standard pages:

```php
public static function getPages(): array
{
    return [
        'index'  => List{Models}::route('/'),
        'create' => Create{Model}::route('/create'),
        'edit'   => Edit{Model}::route('/{record}/edit'),
    ];
}
```

No custom pages have been created beyond the defaults.

## Widgets

No custom widgets implemented. Default Filament widgets are used:
- `AccountWidget` — Shows logged-in user info
- `FilamentInfoWidget` — Shows Filament version info

## Custom Logout Response

The Filament logout is overridden to redirect to `/login` instead of the Filament login page:

```php
// app/Providers/AppServiceProvider.php
$this->app->bind(
    \Filament\Auth\Http\Responses\Contracts\LogoutResponse::class,
    \App\Http\Responses\FilamentLogoutResponse::class
);
```

## Architecture Pattern: Separated Form/Table Classes

This project uses a **separated schema pattern** where form and table definitions are extracted into dedicated classes:

```
Resources/{Name}/
├── {Name}Resource.php          ← Delegates to Form/Table classes
├── Schemas/{Name}Form.php      ← Static configure(Schema $schema) method
└── Tables/{Names}Table.php     ← Static configure(Table $table) method
```

**Why this pattern**:
- Keeps resource files small and focused
- Form logic is reusable across create/edit pages
- Table logic is isolated and testable
- Follows single responsibility principle

**How to use**:

```php
// In Resource class:
public static function form(Schema $schema): Schema
{
    return CampaignForm::configure($schema);
}

public static function table(Table $table): Table
{
    return CampaignsTable::configure($table);
}
```

## How to Add a New Filament Resource

1. Create the directory structure:
   ```
   app/Filament/Resources/{NewModel}/
   ├── {NewModel}Resource.php
   ├── Pages/
   │   ├── Create{NewModel}.php
   │   ├── Edit{NewModel}.php
   │   └── List{NewModels}.php
   ├── Schemas/
   │   └── {NewModel}Form.php
   └── Tables/
       └── {NewModels}Table.php
   ```

2. Resource class must:
   - Extend `Filament\Resources\Resource`
   - Set `$model`, `$navigationIcon`, `$navigationLabel`, `$navigationGroup`
   - Implement `canViewAny()` for access control
   - Delegate `form()` and `table()` to schema/table classes

3. Form class must:
   - Have a static `configure(Schema $schema): Schema` method
   - Use `Section` with `->aside()` layout for consistency

4. Table class must:
   - Have a static `configure(Table $table): Table` method
   - Include `EditAction` in recordActions
   - Include `DeleteBulkAction` in toolbarActions

5. The resource will be **auto-discovered** — no registration needed.
