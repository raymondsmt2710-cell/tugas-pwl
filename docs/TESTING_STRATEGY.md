# Testing Strategy — Autopahala

## Overview

The project uses **PHPUnit 12.5** as its testing framework. Tests are located in the `tests/` directory following Laravel's standard structure.

## Test Framework

```json
// composer.json
"require-dev": {
    "phpunit/phpunit": "^12.5.12",
    "mockery/mockery": "^1.6",
    "fakerphp/faker": "^1.23"
}
```

**Run tests**:
```bash
php artisan test
# or
composer test
```

The `composer test` script clears config cache before running:
```json
"test": [
    "@php artisan config:clear --ansi @no_additional_args",
    "@php artisan test"
]
```

## Test Directory Structure

```
tests/
├── Feature/           # Integration tests (HTTP, database)
│   ├── Auth/          # Authentication flow tests
│   ├── Campaign/      # Campaign CRUD tests
│   ├── Donation/      # Donation flow tests
│   └── Filament/      # Admin panel tests
├── Unit/              # Isolated unit tests
│   ├── Models/        # Model logic tests
│   ├── Services/      # Service layer tests
│   └── Policies/      # Policy authorization tests
├── TestCase.php       # Base test class
└── CreatesApplication.php
```

## Unit Tests

### Model Tests

Test model relationships, scopes, accessors, and business logic:

```php
namespace Tests\Unit\Models;

use App\Models\Campaign;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $campaign = Campaign::factory()->create(['id_user' => $user->id_user]);

        $this->assertInstanceOf(User::class, $campaign->user);
        $this->assertEquals($user->id_user, $campaign->user->id_user);
    }

    public function test_active_scope_filters_correctly(): void
    {
        // Create active campaign
        Campaign::factory()->create([
            'campaign_status' => 'active',
            'verification_status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        // Create inactive campaign
        Campaign::factory()->create([
            'campaign_status' => 'draft',
            'verification_status' => 'draft',
            'end_date' => now()->addDays(30),
        ]);

        $activeCampaigns = Campaign::active()->get();

        $this->assertCount(1, $activeCampaigns);
    }

    public function test_progress_percentage_calculation(): void
    {
        $campaign = Campaign::factory()->create([
            'target_amount' => 1000000,
            'collected_amount' => 250000,
        ]);

        $this->assertEquals(25, $campaign->progress_percentage);
    }
}
```

### Service Tests

Test business logic in isolation:

```php
namespace Tests\Unit\Services;

use App\Models\Donation;
use App\Models\Campaign;
use App\Services\MidtransService;
use Tests\TestCase;
use Mockery;

class MidtransServiceTest extends TestCase
{
    public function test_create_transaction_builds_correct_params(): void
    {
        $campaign = Campaign::factory()->create(['title' => 'Test Campaign']);
        $donation = Donation::factory()->create([
            'id_campaign' => $campaign->id_campaign,
            'donor_name' => 'John Doe',
            'donor_email' => 'john@example.com',
        ]);

        $service = new MidtransService();
        // Mock Snap API call
        // Assert params structure is correct
    }
}
```

### Policy Tests

Test authorization rules:

```php
namespace Tests\Unit\Policies;

use App\Models\Campaign;
use App\Models\User;
use App\Policies\CampaignPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignPolicyTest extends TestCase
{
    use RefreshDatabase;

    private CampaignPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new CampaignPolicy();
    }

    public function test_owner_can_update_campaign(): void
    {
        $user = User::factory()->create();
        $campaign = Campaign::factory()->create(['user_id' => $user->id_user]);

        $this->assertTrue($this->policy->update($user, $campaign));
    }

    public function test_non_owner_cannot_update_campaign(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $campaign = Campaign::factory()->create(['user_id' => $otherUser->id_user]);

        $this->assertFalse($this->policy->update($user, $campaign));
    }

    public function test_admin_can_update_any_campaign(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $campaign = Campaign::factory()->create();

        $this->assertTrue($this->policy->update($admin, $campaign));
    }

    public function test_owner_can_delete_pending_campaign(): void
    {
        $user = User::factory()->create();
        $campaign = Campaign::factory()->create([
            'user_id' => $user->id_user,
            'status' => 'pending',
        ]);

        $this->assertTrue($this->policy->delete($user, $campaign));
    }

    public function test_owner_cannot_delete_approved_campaign(): void
    {
        $user = User::factory()->create();
        $campaign = Campaign::factory()->create([
            'user_id' => $user->id_user,
            'status' => 'approved',
        ]);

        $this->assertFalse($this->policy->delete($user, $campaign));
    }
}
```

## Feature Tests

### Authentication Tests

```php
namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_redirected_to_admin_panel(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
    }

    public function test_user_redirected_to_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_login_rate_limited(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 6; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(429); // Too Many Requests
    }
}
```

### Campaign Tests

```php
namespace Tests\Feature\Campaign;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CampaignCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_active_campaigns(): void
    {
        Campaign::factory()->create([
            'campaign_status' => 'active',
            'verification_status' => 'active',
            'end_date' => now()->addDays(30),
        ]);

        $response = $this->get('/campaigns');

        $response->assertStatus(200);
        $response->assertViewHas('campaigns');
    }

    public function test_authenticated_user_can_create_campaign(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post('/campaigns', [
            'title' => 'Test Campaign',
            'short_description' => 'A test campaign description',
            'description' => 'Full description of the campaign',
            'target_amount' => 1000000,
            'id_category' => $category->id,
            'image' => UploadedFile::fake()->image('campaign.jpg'),
            'end_date' => now()->addDays(30)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('campaigns.index'));
        $this->assertDatabaseHas('campaigns', [
            'title' => 'Test Campaign',
            'user_id' => $user->id_user,
            'campaign_status' => 'draft',
        ]);
    }

    public function test_user_cannot_edit_others_campaign(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $campaign = Campaign::factory()->create(['user_id' => $owner->id_user]);

        $response = $this->actingAs($otherUser)->get("/campaigns/{$campaign->id_campaign}/edit");

        $response->assertStatus(403);
    }
}
```

## Filament Tests

### Admin Panel Access Tests

```php
namespace Tests\Feature\Filament;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_user_cannot_access_panel(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }
}
```

### Resource Tests (Using Filament Testing Utilities)

```php
namespace Tests\Feature\Filament;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Filament\Resources\Campaigns\Pages\ListCampaigns;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);
    }

    public function test_can_list_campaigns(): void
    {
        Campaign::factory()->count(3)->create();

        Livewire::test(ListCampaigns::class)
            ->assertSuccessful()
            ->assertCanSeeTableRecords(Campaign::all());
    }

    public function test_can_filter_by_status(): void
    {
        $pending = Campaign::factory()->create(['status' => 'pending']);
        $approved = Campaign::factory()->create(['status' => 'approved']);

        Livewire::test(ListCampaigns::class)
            ->filterTable('status', 'pending')
            ->assertCanSeeTableRecords([$pending])
            ->assertCanNotSeeTableRecords([$approved]);
    }
}
```

## Payment Flow Tests

```php
namespace Tests\Feature\Payment;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MidtransWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_payment_updates_donation_status(): void
    {
        $campaign = Campaign::factory()->create(['collected_amount' => 0]);
        $donation = Donation::factory()->create([
            'id_campaign' => $campaign->id_campaign,
            'payment_status' => 'pending',
        ]);

        // Simulate Midtrans webhook
        $response = $this->post('/api/midtrans/webhook', [
            'order_id' => 'DONATION-' . $donation->id_donation,
            'status_code' => '200',
            'transaction_status' => 'settlement',
            'gross_amount' => $donation->amount,
            'signature_key' => $this->generateSignature($donation),
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('donations', [
            'id_donation' => $donation->id_donation,
            'payment_status' => 'success',
        ]);
    }

    public function test_failed_payment_marks_donation_failed(): void
    {
        $donation = Donation::factory()->create(['payment_status' => 'pending']);

        $response = $this->post('/api/midtrans/webhook', [
            'order_id' => 'DONATION-' . $donation->id_donation,
            'status_code' => '202',
            'transaction_status' => 'deny',
            'signature_key' => $this->generateSignature($donation),
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('donations', [
            'id_donation' => $donation->id_donation,
            'payment_status' => 'failed',
        ]);
    }

    private function generateSignature(Donation $donation): string
    {
        return hash('sha512', 
            'DONATION-' . $donation->id_donation . 
            '200' . 
            $donation->amount . 
            config('midtrans.server_key')
        );
    }
}
```

## Authorization Tests

```php
namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_campaign(): void
    {
        $response = $this->get('/campaigns/create');
        $response->assertRedirect('/login');
    }

    public function test_owner_can_delete_pending_campaign(): void
    {
        $user = User::factory()->create();
        $campaign = Campaign::factory()->create([
            'user_id' => $user->id_user,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->delete("/campaigns/{$campaign->id_campaign}");

        $response->assertRedirect(route('campaigns.index'));
        $this->assertSoftDeleted('campaigns', ['id_campaign' => $campaign->id_campaign]);
    }
}
```

## Test Data Setup

### Seeders for Testing

```php
// DatabaseSeeder creates:
// - Admin: admin@example.com / password (role: admin)
// - User: test@example.com / password (role: user)

// CategorySeeder creates:
// - Kesehatan, Pendidikan, Bencana Alam, Anak-anak, Program Sosial
```

### Factories Needed

The project needs factories for:
- `UserFactory` (exists via Jetstream)
- `CampaignFactory` (needs creation)
- `DonationFactory` (needs creation)
- `CategoryFactory` (needs creation)

### Example Factory

```php
// database/factories/CampaignFactory.php
namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        $title = fake()->sentence(4);
        return [
            'id_user' => User::factory(),
            'id_category' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title),
            'short_description' => fake()->paragraph(),
            'description' => fake()->paragraphs(3, true),
            'target_amount' => fake()->numberBetween(1000000, 100000000),
            'minimum_donation' => 10000,
            'collected_amount' => 0,
            'withdrawal_amount' => 0,
            'available_balance' => 0,
            'campaign_status' => 'draft',
            'verification_status' => 'draft',
            'status' => 'pending',
            'start_date' => now(),
            'end_date' => now()->addMonths(3),
        ];
    }

    public function active(): static
    {
        return $this->state([
            'campaign_status' => 'active',
            'verification_status' => 'active',
            'status' => 'approved',
        ]);
    }
}
```

## Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=CampaignPolicyTest

# Run specific method
php artisan test --filter=test_owner_can_update_campaign

# Run with coverage
php artisan test --coverage

# Run in parallel
php artisan test --parallel
```
