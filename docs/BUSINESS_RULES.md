# Business Rules — Autopahala

## Campaign Lifecycle

### Status Flow

```
                    ┌─────────────────────────────────────────┐
                    │         CAMPAIGN LIFECYCLE               │
                    └─────────────────────────────────────────┘

    ┌────────┐      ┌─────────┐      ┌──────────┐      ┌──────────┐
    │ DRAFT  │─────▶│ PENDING │─────▶│ APPROVED │─────▶│ FINISHED │
    │        │      │ REVIEW  │      │ (Active) │      │          │
    └────────┘      └────┬────┘      └────┬─────┘      └──────────┘
                         │                │
                         ▼                ▼
                   ┌──────────┐    ┌───────────┐
                   │ REJECTED │    │ SUSPENDED │
                   └──────────┘    └───────────┘
```

### Campaign Status Rules

| Status | Trigger | Who Can Trigger | Visible to Public |
|--------|---------|-----------------|-------------------|
| Draft | User creates campaign | User | ❌ |
| Pending | User submits for review | User | ❌ |
| Approved/Active | Admin approves | Admin only | ✅ |
| Rejected | Admin rejects | Admin only | ❌ |
| Finished | End date passes OR target reached | System/Admin | ✅ (read-only) |
| Suspended | Admin suspends (reports) | Admin only | ❌ |
| Closed | Owner closes campaign | Owner/Admin | ❌ |

### Campaign Creation Rules (from `CampaignController@store`)

```php
// Validation rules enforced:
'title'             => 'required|string|max:255'
'short_description' => 'required|string|max:500'
'description'       => 'required|string'
'target_amount'     => 'required|numeric|min:100000'      // Min Rp 100,000
'minimum_donation'  => 'nullable|numeric|min:10000'       // Min Rp 10,000
'id_category'       => 'required|exists:categories,id'
'image'             => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'  // Max 2MB
'video_url'         => 'nullable|url'
'end_date'          => 'required|date|after:today'
```

### Campaign Visibility Rules (from `Campaign::scopeActive()`)

A campaign is publicly visible when ALL conditions are met:
1. `campaign_status = 'active'`
2. `verification_status = 'active'`
3. `end_date > now()`

### Campaign Edit/Delete Rules (from `CampaignPolicy`)

- **Edit**: Only the campaign owner OR admin can edit
- **Delete**: Owner can delete only if `status = 'pending'`; admin can always delete
- **Restore**: Admin only
- **Force Delete**: Admin only

---

## Donation Lifecycle

### Status Flow

```
    ┌─────────┐      ┌─────────┐
    │ PENDING │─────▶│ SUCCESS │
    │         │      │         │
    └────┬────┘      └─────────┘
         │
         ├──────────▶┌─────────┐
         │           │ FAILED  │
         │           └─────────┘
         │
         └──────────▶┌───────────┐
                     │ CANCELLED │
                     └───────────┘
```

### Donation Status Rules

| Status | Meaning | Triggered By |
|--------|---------|-------------|
| `pending` | Payment initiated, awaiting completion | System (on creation) |
| `success` | Payment confirmed by Midtrans | Webhook callback |
| `failed` | Payment failed or expired | Webhook callback |
| `cancelled` | User cancelled payment | Webhook callback / System |

### Donation Business Rules

1. **Anonymous Donations Allowed**: `id_user` is nullable — guests can donate
2. **Donor Info Always Required**: `donor_name` and `donor_email` must be provided regardless of auth status
3. **Minimum Amount**: Enforced by campaign's `minimum_donation` field (default: Rp 10,000)
4. **Campaign Must Be Active**: Donations should only be accepted for active campaigns
5. **Balance Update**: On successful payment, `campaigns.collected_amount` must be incremented and `available_balance` recalculated

### Donation Amount Calculation

```
campaign.collected_amount += donation.amount  (on payment success)
campaign.available_balance = campaign.collected_amount - campaign.withdrawal_amount
```

---

## Verification Workflow

### Admin Campaign Verification Process

1. User creates campaign → status set to `draft`
2. User submits campaign → `verification_status` changes to `pending`
3. Admin reviews in Filament panel (Kelola Kampanye)
4. Admin changes `status` field:
   - `approved` → Campaign becomes publicly visible
   - `rejected` → Campaign hidden, user notified (planned)
5. Admin action logged in `admin_logs` table

### Verification Criteria (Business Logic — to be enforced)

- Campaign has clear title and description
- Banner image is appropriate
- Target amount is reasonable
- Category is correct
- No fraudulent or prohibited content

---

## Withdrawal Rules (Planned)

### Withdrawal Eligibility

1. Campaign must have `available_balance > 0`
2. Campaign must be owned by the requesting user
3. Campaign should be in `active` or `finished` status
4. User account must be in `active` status

### Withdrawal Process

```
┌──────────┐     ┌─────────┐     ┌──────────┐     ┌───────────┐
│ Requested│────▶│ Pending │────▶│ Approved │────▶│ Completed │
│          │     │ Review  │     │          │     │           │
└──────────┘     └────┬────┘     └──────────┘     └───────────┘
                      │
                      ▼
                ┌──────────┐
                │ Rejected │
                └──────────┘
```

### Withdrawal Business Rules (Planned)

- Withdrawal amount cannot exceed `available_balance`
- Admin must approve all withdrawals
- Bank account details required (name, number, bank)
- On approval: `campaign.withdrawal_amount += amount`, recalculate `available_balance`
- Minimum withdrawal amount: TBD
- Processing time: TBD

---

## Reporting Workflow (Planned)

### Report Reasons (Suggested)

- Fraud / Penipuan
- Inappropriate Content / Konten Tidak Pantas
- Duplicate Campaign / Kampanye Duplikat
- Misleading Information / Informasi Menyesatkan
- Other / Lainnya

### Report Processing

1. User submits report with reason and description
2. Report stored with `reporter_id`, `campaign_id`, `reason`, `status`
3. Admin reviews in Filament panel
4. Actions: Dismiss report, Warn campaign owner, Suspend campaign
5. Threshold: If campaign receives X reports, auto-flag for review

---

## Leaderboard Rules (Planned)

### Ranking Criteria (Suggested)

- **Top Donors**: Ranked by total donation amount across all campaigns
- **Top Campaigns**: Ranked by `collected_amount` or number of donors
- **Top Fundraisers**: Users ranked by total funds raised across their campaigns

### Leaderboard Visibility

- Public leaderboard on homepage/dedicated page
- Time-based filters: All-time, Monthly, Weekly
- Anonymous donors excluded from leaderboard

---

## Follow System Rules (Planned)

### Follow Mechanics

- Users can follow other users
- Following shows campaigns from followed users in feed
- Follower count displayed on public profile
- Notification sent when followed user creates new campaign

### Database Schema (Suggested)

```sql
CREATE TABLE follows (
    id BIGINT UNSIGNED PRIMARY KEY,
    follower_id BIGINT UNSIGNED,  -- FK → users.id_user
    following_id BIGINT UNSIGNED, -- FK → users.id_user
    created_at TIMESTAMP,
    UNIQUE(follower_id, following_id)
);
```

---

## Like System Rules (Planned)

### Like Mechanics

- Users can like campaigns (one like per user per campaign)
- Like count displayed on campaign card/page
- Liked campaigns accessible from user profile

### Database Schema (Suggested)

```sql
CREATE TABLE campaign_likes (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id BIGINT UNSIGNED,     -- FK → users.id_user
    campaign_id BIGINT UNSIGNED, -- FK → campaigns.id_campaign
    created_at TIMESTAMP,
    UNIQUE(user_id, campaign_id)
);
```

---

## Comment Moderation Rules (Planned)

### Comment Rules

- Only authenticated users can comment
- Comments on campaigns (campaign updates in future)
- Admin can delete any comment
- Campaign owner can delete comments on their campaigns
- No nested replies in v1 (flat comments)

### Moderation Workflow

1. User posts comment → immediately visible
2. If reported → flagged for admin review
3. Admin can: approve, delete, or ban commenter
4. Profanity filter (optional, future)

---

## User Account Rules

### Registration (from `CreateNewUser`)

1. Name, email, password required
2. Username auto-generated: `Str::slug(name)` with collision handling
3. Default role: `user`
4. Default account_status: `active`
5. Terms acceptance required (if Jetstream feature enabled)

### Account Status Effects

| Status | Can Login | Can Create Campaign | Can Donate | Visible Profile |
|--------|-----------|--------------------:|:----------:|:---------------:|
| active | ✅ | ✅ | ✅ | ✅ |
| suspended | ❌ (planned) | ❌ | ❌ | ✅ (read-only) |
| pending | ✅ | ❌ (planned) | ✅ | ✅ |

### Admin Privileges

- Access Filament panel at `/admin`
- Approve/reject campaigns
- Manage all users (CRUD, suspend, verify)
- View all donations and withdrawals
- Process withdrawal requests
- All actions logged in `admin_logs`
