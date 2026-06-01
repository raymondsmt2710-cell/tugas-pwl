# Database Guide — Autopahala

## Entity Relationship Diagram (Textual)

```
┌──────────────┐       ┌──────────────┐       ┌──────────────┐
│    users     │       │  categories  │       │  admin_logs  │
│──────────────│       │──────────────│       │──────────────│
│ PK: id_user  │◀──┐   │ PK: id       │       │ PK: id_log   │
│ full_name    │   │   │ name         │       │ FK: admin_id │──▶ users.id_user
│ username     │   │   │ slug         │       │ activity     │
│ email        │   │   │ description  │       │ created_at   │
│ role         │   │   │ timestamps   │       └──────────────┘
│ account_status│   │   └──────┬───────┘
│ ...          │   │          │
└──────┬───────┘   │          │
       │           │          │
       │           │   ┌──────▼───────┐
       │           │   │  campaigns   │
       │           └───│──────────────│
       │               │ PK: id_campaign│
       │               │ FK: id_user   │──▶ users.id_user
       │               │ FK: id_category│──▶ categories.id
       │               │ title, slug   │
       │               │ target_amount │
       │               │ collected_amount│
       │               │ status fields │
       │               └──────┬───────┘
       │                      │
       │               ┌──────▼───────┐
       │               │  donations   │
       └───────────────│──────────────│
                       │ PK: id_donation│
                       │ FK: id_campaign│──▶ campaigns.id_campaign
                       │ FK: id_user   │──▶ users.id (nullable)
                       │ donor_name    │
                       │ payment_status│
                       └──────────────┘

┌──────────────┐
│ withdrawals  │  (scaffold only - empty migration)
│──────────────│
│ PK: id       │
│ timestamps   │
└──────────────┘
```

## All Tables

### `users`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id_user` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Custom primary key |
| `full_name` | VARCHAR(100) | NOT NULL | User's display name |
| `username` | VARCHAR(255) | UNIQUE, NULLABLE | URL-friendly identifier |
| `email` | VARCHAR(100) | UNIQUE, NOT NULL | Login credential |
| `email_verified_at` | TIMESTAMP | NULLABLE | Email verification timestamp |
| `phone_number` | VARCHAR(20) | NULLABLE | Contact number |
| `password` | VARCHAR(255) | NOT NULL | Hashed password |
| `role` | ENUM('admin','user') | DEFAULT 'user' | Access level |
| `account_status` | ENUM('active','suspended','pending') | DEFAULT 'active' | Account state |
| `profile_photo` | VARCHAR(255) | NULLABLE | Avatar file path |
| `bio` | TEXT | NULLABLE | User biography |
| `address` | TEXT | NULLABLE | Physical address |
| `last_login` | TIMESTAMP | NULLABLE | Last login tracking |
| `remember_token` | VARCHAR(100) | NULLABLE | Session persistence |
| `current_team_id` | BIGINT UNSIGNED | NULLABLE | Jetstream teams (unused) |
| `cover_photo_path` | VARCHAR(2048) | NULLABLE | Profile cover image |
| `social_links` | JSON | NULLABLE | Social media links |
| `is_verified` | BOOLEAN | DEFAULT false | Blue checkmark status |
| `google_id` | VARCHAR(255) | NULLABLE | Google OAuth identifier |
| `github_id` | VARCHAR(255) | NULLABLE | GitHub OAuth identifier |
| `created_at` | TIMESTAMP | NULLABLE | Record creation |
| `updated_at` | TIMESTAMP | NULLABLE | Last modification |
| `deleted_at` | TIMESTAMP | NULLABLE | Soft delete marker |

**Business Rules**:
- `username` is auto-generated from `full_name` on registration (slug format with collision handling)
- `role` determines access: 'admin' can access Filament panel
- `account_status = 'suspended'` should block login (not yet enforced in middleware)
- `is_verified` is a manual admin-granted badge (blue checkmark)
- Soft deletes enabled — users are never permanently removed

---

### `categories`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Standard primary key |
| `name` | VARCHAR(255) | NOT NULL | Category display name |
| `slug` | VARCHAR(255) | UNIQUE | URL-friendly identifier |
| `description` | TEXT | NULLABLE | Category description |
| `created_at` | TIMESTAMP | NULLABLE | Record creation |
| `updated_at` | TIMESTAMP | NULLABLE | Last modification |

**Seeded Categories**: Kesehatan, Pendidikan, Bencana Alam, Anak-anak, Program Sosial

---

### `campaigns`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id_campaign` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Custom primary key |
| `id_user` | BIGINT UNSIGNED | FK → users.id_user, CASCADE | Campaign creator |
| `id_category` | BIGINT UNSIGNED | FK → categories.id_category, CASCADE | Campaign category |
| `title` | VARCHAR(255) | NOT NULL | Campaign title |
| `slug` | VARCHAR(255) | UNIQUE | URL identifier |
| `short_description` | VARCHAR(500) | NOT NULL | Brief summary |
| `description` | LONGTEXT | NOT NULL | Full campaign description |
| `target_amount` | DECIMAL(15,2) | NOT NULL | Fundraising goal |
| `minimum_donation` | DECIMAL(15,2) | DEFAULT 0 | Minimum donation amount |
| `collected_amount` | DECIMAL(15,2) | DEFAULT 0 | Total received |
| `withdrawal_amount` | DECIMAL(15,2) | DEFAULT 0 | Total withdrawn |
| `available_balance` | DECIMAL(15,2) | DEFAULT 0 | Available for withdrawal |
| `banner_image` | VARCHAR(255) | NULLABLE | Campaign cover image |
| `video_url` | VARCHAR(500) | NULLABLE | Optional video link |
| `campaign_status` | ENUM | DEFAULT 'draft' | Lifecycle status |
| `verification_status` | ENUM | DEFAULT 'draft' | Admin verification |
| `status` | ENUM('pending','approved','rejected') | DEFAULT 'pending' | Simplified status |
| `start_date` | DATETIME | NULLABLE | Campaign start |
| `end_date` | DATETIME | NOT NULL | Campaign deadline |
| `created_at` | TIMESTAMP | NULLABLE | Record creation |
| `updated_at` | TIMESTAMP | NULLABLE | Last modification |
| `deleted_at` | TIMESTAMP | NULLABLE | Soft delete marker |

**Indexes**: `id_user`, `id_category`, `campaign_status`, `verification_status`

**Status Enums**:
- `campaign_status`: draft, active, finished, closed, suspended
- `verification_status`: draft, pending, active, rejected, expired
- `status`: pending, approved, rejected (used by Filament admin)

**Business Rules**:
- `available_balance = collected_amount - withdrawal_amount`
- `target_amount` minimum is 100,000 IDR (enforced in controller validation)
- `minimum_donation` minimum is 10,000 IDR
- `end_date` must be after today
- Soft deletes enabled

---

### `donations`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id_donation` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Custom primary key |
| `id_campaign` | BIGINT UNSIGNED | FK → campaigns.id_campaign, CASCADE | Target campaign |
| `id_user` | BIGINT UNSIGNED | FK → users.id, SET NULL, NULLABLE | Donor (if logged in) |
| `donor_name` | VARCHAR(100) | NOT NULL | Donor display name |
| `donor_email` | VARCHAR(100) | NOT NULL | Donor contact email |
| `donor_message` | TEXT | NULLABLE | Message to campaign owner |
| `payment_status` | ENUM | DEFAULT 'pending' | Transaction state |
| `payment_method` | VARCHAR(50) | NULLABLE | credit_card, bank_transfer, e_wallet |
| `payment_token` | VARCHAR(255) | NULLABLE | Midtrans snap token |
| `admin_flag` | BOOLEAN | DEFAULT false | Admin attention flag |
| `reward_pledges` | VARCHAR(255) | NULLABLE | Reward tier (future) |
| `next_payment` | DATETIME | NULLABLE | Recurring donation (future) |
| `created_at` | TIMESTAMP | NULLABLE | Record creation |
| `updated_at` | TIMESTAMP | NULLABLE | Last modification |
| `deleted_at` | TIMESTAMP | NULLABLE | Soft delete marker |

**Indexes**: `id_campaign`, `id_user`, `payment_status`, `created_at`

**Payment Status Enum**: pending, success, failed, cancelled

**Business Rules**:
- Anonymous donations allowed (`id_user` is nullable)
- `donor_name` and `donor_email` are always required (even for logged-in users)
- `payment_token` stores the Midtrans Snap token for payment page
- Soft deletes enabled

---

### `withdrawals`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Standard primary key |
| `created_at` | TIMESTAMP | NULLABLE | Record creation |
| `updated_at` | TIMESTAMP | NULLABLE | Last modification |

**Note**: This is a scaffold migration. The full schema needs to be implemented with fields like: `campaign_id`, `user_id`, `amount`, `bank_name`, `account_number`, `account_holder`, `status`, `processed_at`, `admin_notes`.

---

### `admin_logs`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id_log` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Custom primary key |
| `admin_id` | BIGINT UNSIGNED | FK → users.id_user, CASCADE | Admin who performed action |
| `activity` | TEXT | NOT NULL | Description of action |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | When action occurred |

**Note**: No `updated_at` — logs are immutable (append-only).

---

### `sessions`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | VARCHAR(255) | PK | Session identifier |
| `user_id` | BIGINT UNSIGNED | FK → users.id_user, CASCADE, NULLABLE | Authenticated user |
| `ip_address` | VARCHAR(45) | NULLABLE | Client IP |
| `user_agent` | TEXT | NULLABLE | Browser info |
| `payload` | LONGTEXT | NOT NULL | Session data |
| `last_activity` | INTEGER | INDEXED | Last activity timestamp |

---

### `password_reset_tokens`

| Column | Type | Constraints |
|--------|------|-------------|
| `email` | VARCHAR(255) | PK |
| `token` | VARCHAR(255) | NOT NULL |
| `created_at` | TIMESTAMP | NULLABLE |

---

### `personal_access_tokens` (Sanctum)

Standard Laravel Sanctum table for API token management.

---

### `passkeys` (WebAuthn)

Stores WebAuthn/passkey credentials for passwordless authentication.

---

### `cache` / `cache_locks`

Standard Laravel cache tables for database cache driver.

---

### `jobs` / `job_batches` / `failed_jobs`

Standard Laravel queue tables for background job processing.

## Foreign Key Relationships

```
users.id_user          ←── campaigns.id_user (CASCADE)
users.id_user          ←── donations.id_user (SET NULL)
users.id_user          ←── admin_logs.admin_id (CASCADE)
users.id_user          ←── sessions.user_id (CASCADE)
categories.id_category ←── campaigns.id_category (CASCADE)
campaigns.id_campaign  ←── donations.id_campaign (CASCADE)
```

## Important Notes

1. **Custom Primary Keys**: The project uses custom PK names (`id_user`, `id_campaign`, `id_donation`, `id_log`) instead of Laravel's default `id`. This requires explicit `$primaryKey` declarations in models.

2. **Dual Status System**: Campaigns have THREE status columns due to branch merging. The `status` column (pending/approved/rejected) is used by Filament, while `campaign_status` and `verification_status` are used by the public site. This should be consolidated.

3. **Foreign Key Mismatch**: The donations migration references `users.id` but the users table PK is `id_user`. This may cause issues and should be verified.

4. **Category PK Inconsistency**: The categories migration uses `$table->id()` (creates `id` column), but the campaigns FK references `categories.id_category`. This needs alignment.
