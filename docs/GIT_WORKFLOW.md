# Git Workflow — Autopahala

## Branch Strategy

The project uses a **feature branch workflow** with the following branch structure:

```
main (production-ready)
├── feature/campaign-crud
├── feature/auth-profile
├── feature/donation-system
├── feature/homepage
├── fix/payment-webhook
└── refactor/service-layer
```

## Branch Naming

| Prefix | Purpose | Example |
|--------|---------|---------|
| `feature/` | New functionality | `feature/follow-system` |
| `fix/` | Bug fixes | `fix/donation-amount-calc` |
| `refactor/` | Code improvements | `refactor/campaign-service` |
| `docs/` | Documentation only | `docs/api-standards` |
| `hotfix/` | Urgent production fix | `hotfix/payment-webhook` |

## Workflow

### Starting New Work

```bash
# Always start from latest main
git checkout main
git pull origin main

# Create feature branch
git checkout -b feature/your-feature
```

### During Development

```bash
# Stage specific files (preferred over git add .)
git add app/Models/NewModel.php
git add database/migrations/2026_xx_xx_create_table.php

# Commit with descriptive message
git commit -m "feat(model): add NewModel with relationships"

# Push to remote
git push -u origin feature/your-feature
```

### Merging

```bash
# Update from main before merging
git checkout main
git pull origin main
git checkout feature/your-feature
git merge main

# Resolve conflicts if any, then push
git push origin feature/your-feature

# Create pull request via GitHub/GitLab
```

## Commit Guidelines

### Format

```
type(scope): short description (max 72 chars)

Optional longer description explaining the "why" behind the change.
```

### Types

| Type | When to Use |
|------|-------------|
| `feat` | New feature or functionality |
| `fix` | Bug fix |
| `refactor` | Code change that doesn't fix a bug or add a feature |
| `docs` | Documentation changes |
| `style` | Formatting, missing semicolons, etc. |
| `test` | Adding or updating tests |
| `chore` | Maintenance tasks (deps, config) |
| `perf` | Performance improvement |

### Scope Examples

- `auth` — Authentication/authorization
- `campaign` — Campaign feature
- `donation` — Donation feature
- `filament` — Admin panel
- `payment` — Midtrans integration
- `profile` — User profile
- `db` — Database/migrations

### Good Commit Examples

```
feat(campaign): implement campaign creation with image upload
fix(auth): redirect admin to /admin after OAuth login
refactor(donation): extract payment logic to DonationService
docs(readme): add local development setup instructions
test(policy): add CampaignPolicy authorization tests
chore(deps): update filament to 4.12
```

## Branch History

Based on the codebase, the following branches have been merged:

| Branch | Features Merged |
|--------|----------------|
| `main` | OAuth, Filament admin, base models |
| `homepage` | HomeController, public pages, Tailwind UI |
| `feature/auth-profile` | User profiles, cover photos, social links |
| `feature` | Campaign CRUD, dual status system |

**Note**: The dual status system on campaigns (`status` + `campaign_status` + `verification_status`) is a result of merging branches that implemented status differently. This should be consolidated.

## Conflict Resolution

### Common Conflict Areas

1. **`routes/web.php`** — Multiple features add routes
2. **Migrations** — Timestamp ordering matters
3. **`composer.json`** — Dependency additions
4. **Models** — Relationship additions from different branches

### Resolution Strategy

- For routes: Organize by section with clear comments (already done)
- For migrations: Ensure timestamps don't conflict; rename if needed
- For models: Merge all relationships, ensure no duplicates
- For composer.json: Run `composer update` after resolving

## Protected Files

These files should be reviewed carefully before merging:

- `.env.example` — Environment template
- `database/migrations/` — Schema changes are irreversible in production
- `app/Providers/` — Service provider changes affect entire app
- `config/` — Configuration changes affect all environments
- `routes/web.php` — Route changes can break existing URLs

## Release Process (Future)

When ready for production:

1. Ensure all tests pass on `main`
2. Tag release: `git tag -a v1.0.0 -m "Initial release"`
3. Push tag: `git push origin v1.0.0`
4. Deploy from tagged release
5. Monitor logs and error tracking
