<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\CampaignComment;
use App\Livewire\CampaignComments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CampaignCommentTest extends TestCase
{
    use RefreshDatabase;

    private $category;
    private $user;
    private $campaign;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user with verified email
        $this->user = User::create([
            'full_name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create a category
        $this->category = Category::create([
            'name' => 'Pendidikan',
            'slug' => 'pendidikan',
        ]);

        // Create a campaign
        $this->campaign = Campaign::create([
            'id_user' => $this->user->id_user,
            'id_category' => $this->category->id_category,
            'title' => 'Bantu Sekolah',
            'slug' => 'bantu-sekolah',
            'short_description' => 'Bantu renovasi sekolah rusak',
            'description' => 'Ini adalah deskripsi kampanye untuk renovasi sekolah.',
            'target_amount' => 10000000,
            'end_date' => now()->addDays(30),
            'status' => 'approved',
        ]);
    }

    public function test_user_can_add_comment(): void
    {
        Livewire::actingAs($this->user)
            ->test(CampaignComments::class, ['campaignId' => $this->campaign->id_campaign])
            ->set('newComment', 'Ini komentar uji baru.')
            ->call('storeComment')
            ->assertHasNoErrors()
            ->assertSet('newComment', '');

        $this->assertDatabaseHas('campaign_comments', [
            'id_campaign' => $this->campaign->id_campaign,
            'id_user' => $this->user->id_user,
            'comment' => 'Ini komentar uji baru.',
        ]);
    }

    public function test_user_can_delete_their_own_comment(): void
    {
        $comment = CampaignComment::create([
            'id_campaign' => $this->campaign->id_campaign,
            'id_user' => $this->user->id_user,
            'comment' => 'Komentar saya sendiri.',
        ]);

        Livewire::actingAs($this->user)
            ->test(CampaignComments::class, ['campaignId' => $this->campaign->id_campaign])
            ->call('deleteComment', $comment->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('campaign_comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_user_cannot_delete_other_user_comment(): void
    {
        // User A writes a comment
        $userA = User::create([
            'full_name' => 'User A',
            'username' => 'usera',
            'email' => 'usera@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $comment = CampaignComment::create([
            'id_campaign' => $this->campaign->id_campaign,
            'id_user' => $userA->id_user,
            'comment' => 'Komentar User A.',
        ]);

        // User B (neither comment owner nor campaign owner) tries to delete it
        $userB = User::create([
            'full_name' => 'User B',
            'username' => 'userb',
            'email' => 'userb@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        Livewire::actingAs($userB)
            ->test(CampaignComments::class, ['campaignId' => $this->campaign->id_campaign])
            ->call('deleteComment', $comment->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('campaign_comments', [
            'id' => $comment->id,
            'deleted_at' => null,
        ]);
    }

    public function test_campaign_owner_can_delete_any_comment_on_their_campaign(): void
    {
        $otherUser = User::create([
            'full_name' => 'Jane Doe',
            'username' => 'janedoe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $comment = CampaignComment::create([
            'id_campaign' => $this->campaign->id_campaign,
            'id_user' => $otherUser->id_user,
            'comment' => 'Komentar orang lain di kampanye saya.',
        ]);

        // acting as the campaign owner ($this->user)
        Livewire::actingAs($this->user)
            ->test(CampaignComments::class, ['campaignId' => $this->campaign->id_campaign])
            ->call('deleteComment', $comment->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('campaign_comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_admin_can_delete_any_comment(): void
    {
        $admin = User::create([
            'full_name' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $comment = CampaignComment::create([
            'id_campaign' => $this->campaign->id_campaign,
            'id_user' => $this->user->id_user,
            'comment' => 'Komentar saya.',
        ]);

        Livewire::actingAs($admin)
            ->test(CampaignComments::class, ['campaignId' => $this->campaign->id_campaign])
            ->call('deleteComment', $comment->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('campaign_comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_comment_listing_handles_deleted_user_gracefully(): void
    {
        $commentUser = User::create([
            'full_name' => 'Comment User',
            'username' => 'commentuser',
            'email' => 'commentuser@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'account_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $comment = CampaignComment::create([
            'id_campaign' => $this->campaign->id_campaign,
            'id_user' => $commentUser->id_user,
            'comment' => 'Komentar dari user yang akan didelete.',
        ]);

        // Soft delete the comment author
        $commentUser->delete();

        // Get the campaign page
        $response = $this->get(route('campaigns.show', $this->campaign->slug));

        $response->assertStatus(200);
        $response->assertSee('Akun Dihapus');
        $response->assertSee('Komentar dari user yang akan didelete.');
    }
}
