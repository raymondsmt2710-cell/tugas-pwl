# Feature Roadmap — Autopahala

## Current Features (Implemented)

### ✅ Fully Implemented

| Feature | Components | Status |
|---------|-----------|--------|
| User Registration | Fortify + auto-username generation | Complete |
| OAuth Login | Google + GitHub via Socialite | Complete |
| Role-Based Auth | Admin/User with custom redirects | Complete |
| User Profiles | Public profiles, bio, social links, cover photo | Complete |
| Campaign CRUD | Create, edit, delete with image upload | Complete |
| Campaign Listing | Public listing with active scope, pagination | Complete |
| Campaign Detail | Show page with slug-based URL | Complete |
| Admin Panel | Filament 4 with full resource management | Complete |
| User Management | Filament CRUD with role/status/verification | Complete |
| Campaign Management | Filament with status approval workflow | Complete |
| Admin Logging | Activity tracking in admin_logs table | Complete |
| Static Pages | About, Contact, FAQ | Complete |

### ⚠️ Partially Implemented (Scaffolded)

| Feature | What Exists | What's Missing |
|---------|-------------|----------------|
| Donation System | Model, migration, Filament resource | Controller logic, payment flow, form |
| Midtrans Payment | MidtransService with Snap | Config file, webhook handler, frontend |
| Withdrawal System | Model, migration, Filament resource | Full migration schema, controller, service |
| Campaign Service | Empty file | Business logic implementation |
| Donation Service | Empty file | Business logic implementation |
| Withdrawal Service | Empty file | Business logic implementation |
| Form Requests | 4 scaffolded files | Validation rules, authorize() fix |
| Category Management | Model, migration, seeder, Filament resource | Filament form/table columns |
| Donation Filament | Resource with pages | Form and table columns |

## Planned Features (Not Yet Started)

### Priority 1 — Core Functionality

#### 1. Complete Donation Flow
- [ ] Implement `DonationController@store` with validation
- [ ] Complete `MidtransService` with proper config loading
- [ ] Create `config/midtrans.php` with env variables
- [ ] Build donation form (Blade + Tailwind)
- [ ] Integrate Snap.js for payment page
- [ ] Implement `MidtransWebhookController@handle`
- [ ] Update campaign `collected_amount` on success
- [ ] Add donation success/failure pages
- [ ] Implement `StoreDonationRequest` validation rules

#### 2. Complete Withdrawal System
- [ ] Design full withdrawals migration (amount, bank details, status)
- [ ] Implement `WithdrawalService` with balance validation
- [ ] Build withdrawal request form for campaign owners
- [ ] Admin approval workflow in Filament
- [ ] Update campaign `withdrawal_amount` and `available_balance`
- [ ] Implement `StoreWithdrawalRequest` validation rules

#### 3. Campaign Verification Workflow
- [ ] Add admin notes/reason for rejection
- [ ] Email notification on approval/rejection
- [ ] Resubmission flow after rejection
- [ ] Verification checklist for admin

### Priority 2 — Social Features

#### 4. Follow User System
- [ ] Create `follows` migration (follower_id, following_id)
- [ ] Add Follow model with relationships
- [ ] Follow/unfollow toggle on user profiles
- [ ] Follower/following counts on profile
- [ ] "Following" feed showing campaigns from followed users
- [ ] Notification on new follower

#### 5. Like Campaign System
- [ ] Create `campaign_likes` migration
- [ ] Add CampaignLike model
- [ ] Like/unlike toggle on campaign cards
- [ ] Like count display
- [ ] "Liked campaigns" section on user profile

#### 6. Comment System
- [ ] Create `comments` migration (user_id, campaign_id, body, parent_id)
- [ ] Add Comment model with relationships
- [ ] Comment form on campaign detail page
- [ ] Comment listing with pagination
- [ ] Delete own comments
- [ ] Admin moderation (delete any comment)

### Priority 3 — Trust & Safety

#### 7. Report Campaign
- [ ] Create `reports` migration (reporter_id, campaign_id, reason, status)
- [ ] Add Report model
- [ ] Report button on campaign page
- [ ] Report form with reason selection
- [ ] Admin report review in Filament
- [ ] Auto-flag campaigns with multiple reports
- [ ] Campaign suspension workflow

#### 8. Campaign Updates
- [ ] Create `campaign_updates` migration (campaign_id, title, body)
- [ ] Add CampaignUpdate model
- [ ] Update posting form for campaign owners
- [ ] Updates timeline on campaign detail page
- [ ] Notify donors on new update

### Priority 4 — Engagement

#### 9. Leaderboard
- [ ] Top donors page (by total amount)
- [ ] Top campaigns page (by collected amount)
- [ ] Top fundraisers page (by campaigns created)
- [ ] Time-based filters (all-time, monthly, weekly)
- [ ] Homepage leaderboard widget

#### 10. Notifications
- [ ] Create notification classes (Laravel Notifications)
- [ ] Database notification channel
- [ ] Email notification channel
- [ ] Notification bell in navbar
- [ ] Mark as read functionality
- [ ] Notification types:
  - New donation received
  - Campaign approved/rejected
  - Withdrawal processed
  - New follower
  - Campaign update from followed user

## Technical Debt

### Critical (Should Fix Soon)

| Issue | Location | Impact |
|-------|----------|--------|
| Dual/triple status system on campaigns | `campaigns` migration | Confusing, potential bugs |
| Form Requests `authorize()` returns false | `app/Http/Requests/` | Will block all requests if used |
| Category FK mismatch | Migration uses `id`, FK references `id_category` | Potential migration failure |
| Donation model column name mismatch | Model uses `campaign_id`, migration uses `id_campaign` | Query failures |
| HomeController uses hardcoded data | `HomeController.php` | Not connected to real campaigns |
| MidtransService class name lowercase | `midtransService` | PSR-4 violation |
| Empty config/midtrans.php | `config/midtrans.php` | Service can't load config |

### Medium (Should Address)

| Issue | Location | Impact |
|-------|----------|--------|
| No account_status enforcement | Middleware | Suspended users can still login |
| No email verification enforcement | Routes | Unverified users have full access |
| Withdrawals migration is empty scaffold | Migration | Feature non-functional |
| Category model is empty | `Category.php` | No fillable, relationships, or scopes |
| No factories for Campaign, Donation, Category | `database/factories/` | Can't write proper tests |
| Services are empty | `app/Services/` | Business logic in controllers |

### Low (Nice to Have)

| Issue | Location | Impact |
|-------|----------|--------|
| No API versioning | Routes | Future API changes harder |
| No caching strategy | — | Performance at scale |
| No image optimization | Upload handling | Large file sizes |
| No search functionality | — | Users can't find campaigns |
| Jetstream teams feature unused | User model | Dead code |

## Future Improvements

### Performance
- [ ] Add Redis caching for campaign listings
- [ ] Implement eager loading for N+1 query prevention
- [ ] Add database query caching for leaderboards
- [ ] Image optimization pipeline (resize, compress, WebP)
- [ ] CDN for static assets and uploaded images

### Developer Experience
- [ ] Complete all model factories
- [ ] Add comprehensive test suite
- [ ] Set up CI/CD pipeline
- [ ] Add code quality tools (PHPStan, Larastan)
- [ ] API documentation (if REST API added)

### User Experience
- [ ] Campaign search with filters
- [ ] Campaign sharing (social media)
- [ ] Donation receipt/certificate generation
- [ ] Multi-language support (i18n)
- [ ] Dark mode
- [ ] PWA support (offline access)
- [ ] Real-time donation counter (WebSockets)

### Infrastructure
- [ ] Horizontal scaling preparation
- [ ] Database read replicas
- [ ] Queue optimization (Redis driver)
- [ ] Error tracking (Sentry/Bugsnag)
- [ ] Application monitoring (New Relic/Datadog)
- [ ] Automated backups
- [ ] Blue-green deployment

## Version Planning

### v1.0 — MVP (Current Sprint)
- Complete donation flow with Midtrans
- Fix technical debt (status consolidation, FK mismatches)
- Basic withdrawal system
- Connect homepage to real data

### v1.1 — Social Features
- Follow system
- Like system
- Comment system
- Basic notifications

### v1.2 — Trust & Safety
- Report system
- Campaign updates
- Admin moderation tools
- Email notifications

### v2.0 — Scale & Polish
- REST API
- Leaderboard
- Search
- Performance optimization
- Mobile-responsive improvements
