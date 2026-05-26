<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Campaign $campaign): bool
    {
        // Approved campaigns are publicly visible
        if ($campaign->isApproved() || $campaign->isCompleted()) {
            return true;
        }

        // Non-public campaigns visible only to owner or admin
        if (!$user) {
            return false;
        }

        return $user->isAdmin() || $campaign->id_user === $user->id_user;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->account_status === 'active';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Campaign $campaign): bool
    {
        // Admin can always update
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can only update draft or rejected campaigns
        return $campaign->id_user === $user->id_user && $campaign->isEditable();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        // Admin can always delete
        if ($user->isAdmin()) {
            return true;
        }

        // Owner can only delete draft or rejected campaigns
        return $campaign->id_user === $user->id_user && $campaign->isDeletable();
    }

    /**
     * Determine whether the user can submit the campaign for review.
     */
    public function submit(User $user, Campaign $campaign): bool
    {
        return $campaign->id_user === $user->id_user
            && ($campaign->isDraft() || $campaign->isRejected());
    }

    /**
     * Determine whether the user can approve/reject the campaign.
     */
    public function moderate(User $user, Campaign $campaign): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Campaign $campaign): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Campaign $campaign): bool
    {
        return $user->isSuperAdmin();
    }
}
