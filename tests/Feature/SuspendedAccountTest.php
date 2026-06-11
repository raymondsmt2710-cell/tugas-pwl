<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuspendedAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_user_can_access_dashboard(): void
    {
        $user = User::factory()->create([
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_suspended_user_is_redirected_to_suspended_page(): void
    {
        $user = User::factory()->create([
            'account_status' => 'suspended',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/suspended');
    }

    public function test_suspended_user_can_access_suspended_page(): void
    {
        $user = User::factory()->create([
            'account_status' => 'suspended',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/suspended');

        $response->assertStatus(200);
        $response->assertSee('Akun Ditangguhkan');
        $response->assertSee($user->full_name);
    }

    public function test_active_user_cannot_access_suspended_page(): void
    {
        $user = User::factory()->create([
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/suspended');

        $response->assertRedirect('/');
    }

    public function test_suspended_admin_cannot_access_filament_panel(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'account_status' => 'suspended',
        ]);

        // Mock a panel instance or just call the method on User
        $panel = \Filament\Facades\Filament::getPanel('admin');
        $this->assertFalse($admin->canAccessPanel($panel));
    }

    public function test_active_admin_can_access_filament_panel(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'account_status' => 'active',
        ]);

        $panel = \Filament\Facades\Filament::getPanel('admin');
        $this->assertTrue($admin->canAccessPanel($panel));
    }
}
