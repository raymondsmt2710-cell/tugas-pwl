# Contributing — Autopahala

## Getting Started

1. Clone the repository
2. Follow the [Deployment Guide](./DEPLOYMENT_GUIDE.md) for local setup
3. Read the [Architecture](./ARCHITECTURE.md) to understand the system
4. Review [Agent Guidelines](./AGENT_GUIDELINES.md) for coding standards

## Development Workflow

### 1. Pick a Task

- Check the [Feature Roadmap](./FEATURE_ROADMAP.md) for planned features
- Look for scaffolded but empty files (controllers, services, form requests)
- Review the technical debt section for improvements

### 2. Create a Branch

```bash
git checkout -b feature/your-feature-name
# or
git checkout -b fix/bug-description
```

### 3. Implement

Follow the established patterns:

- **New Model**: Add to `app/Models/`, include relationships, scopes, casts
- **New Controller**: Add to `app/Http/Controllers/`, keep thin, delegate to services
- **New Service**: Add to `app/Services/`, encapsulate business logic
- **New Policy**: Add to `app/Policies/`, follow CampaignPolicy pattern
- **New Filament Resource**: Follow the separated Schemas/Tables pattern
- **New Migration**: Use descriptive names, add indexes and foreign keys

### 4. Test

```bash
php artisan test
```

### 5. Submit

- Create a pull request with clear description
- Reference any related issues
- Ensure all tests pass

## Code Style

### PHP

- Follow PSR-12 coding standard
- Use Laravel Pint for formatting: `./vendor/bin/pint`
- Type hints on method parameters and return types
- DocBlocks for complex methods

### Blade Templates

- Use components for reusable UI elements
- Keep logic minimal — use view composers or controller data
- Follow Tailwind CSS utility-first approach

### Naming Conventions

| Item | Convention | Example |
|------|-----------|---------|
| Model | Singular PascalCase | `Campaign` |
| Controller | Singular + Controller | `CampaignController` |
| Migration | snake_case with action | `create_campaigns_table` |
| Route name | dot notation | `campaigns.show` |
| View file | kebab-case | `campaign-card.blade.php` |
| Service | Singular + Service | `DonationService` |
| Policy | Singular + Policy | `CampaignPolicy` |
| Form Request | Action + Model + Request | `StoreCampaignRequest` |

### Database Conventions

- Table names: plural snake_case (`campaigns`, `admin_logs`)
- Primary keys: `id_{table_singular}` for custom PKs (e.g., `id_campaign`)
- Foreign keys: `id_{referenced_table_singular}` (e.g., `id_user`)
- Timestamps: always include `created_at`, `updated_at`
- Soft deletes: use for important business entities
- Indexes: add for frequently queried columns

### Filament Conventions

- Labels in Indonesian (Bahasa Indonesia)
- Use `->aside()` layout for form sections
- Separate form schemas into `Schemas/{Model}Form.php`
- Separate table definitions into `Tables/{Models}Table.php`
- Use badge colors: success=green, warning=yellow, danger=red

## Architecture Rules

### Where to Put Code

| Logic Type | Location | Example |
|-----------|----------|---------|
| HTTP handling | Controller | Parse request, return response |
| Business logic | Service | Calculate balance, process payment |
| Data access | Model (Eloquent) | Relationships, scopes, accessors |
| Authorization | Policy | Who can do what |
| Validation | Form Request | Input rules and messages |
| Admin UI | Filament Resource | CRUD forms and tables |
| Scheduled tasks | Console commands | Expire old campaigns |

### What NOT to Do

- Don't put business logic in controllers (use services)
- Don't put queries in Blade templates (pass from controller)
- Don't bypass policies with manual auth checks
- Don't use `$guarded = []` on models (use `$fillable`)
- Don't hardcode values that should be in config/env
- Don't create new patterns when existing ones work

## Adding a New Feature (Checklist)

- [ ] Create migration (if new table needed)
- [ ] Create model with relationships, fillable, casts
- [ ] Create policy for authorization
- [ ] Create service for business logic
- [ ] Create form request for validation
- [ ] Create controller (thin, delegates to service)
- [ ] Add routes to `routes/web.php`
- [ ] Create Blade views
- [ ] Create Filament resource (if admin management needed)
- [ ] Add factory for testing
- [ ] Write tests (unit + feature)
- [ ] Update documentation

## Commit Message Format

```
type(scope): description

# Examples:
feat(campaign): add campaign creation form
fix(donation): correct amount calculation on webhook
refactor(auth): extract login response to dedicated class
docs(readme): update installation instructions
style(filament): fix table column alignment
test(policy): add campaign policy authorization tests
```

**Types**: feat, fix, refactor, docs, style, test, chore

## Pull Request Template

```markdown
## Description
Brief description of changes.

## Type of Change
- [ ] New feature
- [ ] Bug fix
- [ ] Refactoring
- [ ] Documentation

## Testing
- [ ] Unit tests added/updated
- [ ] Feature tests added/updated
- [ ] Manual testing performed

## Checklist
- [ ] Code follows project conventions
- [ ] Self-reviewed the code
- [ ] No new warnings introduced
- [ ] Documentation updated if needed
```
