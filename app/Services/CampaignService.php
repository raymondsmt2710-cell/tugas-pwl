<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignDocument;
use App\Models\CampaignGallery;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CampaignService
{
    /**
     * Create a new campaign with gallery images.
     */
    public function create(array $data, User $user): Campaign
    {
        return DB::transaction(function () use ($data, $user) {
            // Upload banner image
            $bannerPath = $this->uploadImage($data['banner_image'], 'campaigns/banners');

            // Create campaign
            $campaign = Campaign::create([
                'id_user' => $user->id_user,
                'id_category' => $data['id_category'],
                'title' => $data['title'],
                'slug' => Campaign::generateUniqueSlug($data['title']),
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'target_amount' => $data['target_amount'],
                'minimum_donation' => $data['minimum_donation'] ?? 0,
                'collected_amount' => 0,
                'withdrawal_amount' => 0,
                'available_balance' => 0,
                'banner_image' => $bannerPath,
                'video_url' => $data['video_url'] ?? null,
                'campaign_status' => 'draft',
                'verification_status' => 'draft',
                'status' => 'draft',
                'start_date' => now(),
                'end_date' => $data['end_date'],
            ]);

            // Upload gallery images
            if (!empty($data['gallery'])) {
                $this->uploadGalleryImages($campaign, $data['gallery']);
            }

            // Upload documents
            if (!empty($data['documents'])) {
                $this->uploadDocuments($campaign, $data['documents']);
            }

            return $campaign;
        });
    }

    /**
     * Update an existing campaign.
     */
    public function update(Campaign $campaign, array $data): Campaign
    {
        return DB::transaction(function () use ($campaign, $data) {
            $updateData = [
                'title' => $data['title'],
                'slug' => Campaign::generateUniqueSlug($data['title'], $campaign->id_campaign),
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'target_amount' => $data['target_amount'],
                'minimum_donation' => $data['minimum_donation'] ?? 0,
                'id_category' => $data['id_category'],
                'video_url' => $data['video_url'] ?? null,
                'end_date' => $data['end_date'],
            ];

            // Upload new banner if provided
            if (!empty($data['banner_image']) && $data['banner_image'] instanceof UploadedFile) {
                // Delete old banner
                $this->deleteImage($campaign->banner_image);
                $updateData['banner_image'] = $this->uploadImage($data['banner_image'], 'campaigns/banners');
            }

            // If campaign was rejected, reset to draft on edit
            if ($campaign->isRejected()) {
                $updateData['status'] = 'draft';
                $updateData['campaign_status'] = 'draft';
                $updateData['verification_status'] = 'draft';
            }

            $campaign->update($updateData);

            // Remove gallery images if requested
            if (!empty($data['remove_gallery'])) {
                $this->removeGalleryImages($campaign, $data['remove_gallery']);
            }

            // Upload new gallery images
            if (!empty($data['gallery'])) {
                $this->uploadGalleryImages($campaign, $data['gallery']);
            }

            // Remove documents if requested
            if (!empty($data['remove_documents'])) {
                $this->removeDocuments($campaign, $data['remove_documents']);
            }

            // Upload new documents
            if (!empty($data['documents'])) {
                $this->uploadDocuments($campaign, $data['documents']);
            }

            return $campaign->fresh();
        });
    }

    /**
     * Delete a campaign and its associated files.
     */
    public function delete(Campaign $campaign): bool
    {
        return DB::transaction(function () use ($campaign) {
            // Delete banner image
            $this->deleteImage($campaign->banner_image);

            // Delete all gallery images
            foreach ($campaign->galleries as $gallery) {
                $this->deleteImage($gallery->image_path);
            }
            $campaign->galleries()->delete();

            // Delete all documents
            foreach ($campaign->documents as $document) {
                $this->deleteImage($document->file_path);
            }
            $campaign->documents()->delete();

            // Soft delete the campaign
            return $campaign->delete();
        });
    }

    /**
     * Submit a campaign for admin review.
     */
    public function submitForReview(Campaign $campaign): Campaign
    {
        if (!$campaign->isDraft() && !$campaign->isRejected()) {
            throw new \InvalidArgumentException('Hanya kampanye draft atau ditolak yang dapat diajukan untuk review.');
        }

        $campaign->update([
            'status' => 'pending',
            'campaign_status' => 'draft',
            'verification_status' => 'pending',
        ]);

        return $campaign->fresh();
    }

    /**
     * Approve a campaign (admin action).
     */
    public function approve(Campaign $campaign): Campaign
    {
        $campaign->update([
            'status' => 'approved',
            'campaign_status' => 'active',
            'verification_status' => 'active',
        ]);

        $campaign->user->notify(new \App\Notifications\CampaignStatusChanged($campaign, 'approved'));

        return $campaign->fresh();
    }

    /**
     * Reject a campaign (admin action).
     */
    public function reject(Campaign $campaign): Campaign
    {
        $campaign->update([
            'status' => 'rejected',
            'campaign_status' => 'draft',
            'verification_status' => 'rejected',
        ]);

        $campaign->user->notify(new \App\Notifications\CampaignStatusChanged($campaign, 'rejected'));

        return $campaign->fresh();
    }

    /**
     * Mark a campaign as completed (closed).
     */
    public function complete(Campaign $campaign): Campaign
    {
        $campaign->update([
            'status' => 'closed',
            'campaign_status' => 'closed',
            'closed_at' => now(),
            'closed_by' => auth()->user()?->id_user,
        ]);

        $campaign->user->notify(new \App\Notifications\CampaignStatusChanged($campaign, 'closed'));

        return $campaign->fresh();
    }

    /**
     * Close a campaign (creator or admin action).
     */
    public function close(Campaign $campaign, ?int $closedBy = null): Campaign
    {
        $campaign->update([
            'status' => 'closed',
            'campaign_status' => 'closed',
            'closed_at' => now(),
            'closed_by' => $closedBy ?? auth()->user()?->id_user,
        ]);

        return $campaign->fresh();
    }

    /**
     * Request campaign closure (creator action — needs admin approval).
     */
    public function requestClose(Campaign $campaign): Campaign
    {
        $campaign->update([
            'campaign_status' => 'pending_close',
        ]);

        return $campaign->fresh();
    }

    /**
     * Reopen a closed campaign (admin action).
     */
    public function reopen(Campaign $campaign): Campaign
    {
        $previousStatus = $campaign->hasReachedGoal() ? 'goal_reached' : 'approved';

        $campaign->update([
            'status' => $previousStatus,
            'campaign_status' => 'active',
            'closed_at' => null,
            'closed_by' => null,
        ]);

        return $campaign->fresh();
    }

    /**
     * Archive a campaign (admin action).
     */
    public function archive(Campaign $campaign): Campaign
    {
        $campaign->update([
            'status' => 'archived',
            'campaign_status' => 'closed',
        ]);

        return $campaign->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Private Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Upload an image file to storage.
     */
    private function uploadImage(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    /**
     * Delete an image from storage.
     */
    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Upload multiple gallery images for a campaign.
     *
     * @param Campaign $campaign
     * @param array<UploadedFile> $images
     */
    private function uploadGalleryImages(Campaign $campaign, array $images): void
    {
        $maxOrder = $campaign->galleries()->max('sort_order') ?? 0;

        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $path = $this->uploadImage($image, 'campaigns/gallery');

                CampaignGallery::create([
                    'campaign_id' => $campaign->id_campaign,
                    'image_path' => $path,
                    'sort_order' => $maxOrder + $index + 1,
                ]);
            }
        }
    }

    /**
     * Remove specific gallery images by their IDs.
     *
     * @param Campaign $campaign
     * @param array<int> $galleryIds
     */
    private function removeGalleryImages(Campaign $campaign, array $galleryIds): void
    {
        $galleries = $campaign->galleries()->whereIn('id', $galleryIds)->get();

        foreach ($galleries as $gallery) {
            $this->deleteImage($gallery->image_path);
            $gallery->delete();
        }
    }

    /**
     * Upload multiple documents for a campaign.
     *
     * @param Campaign $campaign
     * @param array<UploadedFile> $files
     */
    private function uploadDocuments(Campaign $campaign, array $files): void
    {
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $path = $file->store('campaigns/documents', 'public');

                CampaignDocument::create([
                    'campaign_id' => $campaign->id_campaign,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
    }

    /**
     * Remove specific documents by their IDs.
     *
     * @param Campaign $campaign
     * @param array<int> $documentIds
     */
    private function removeDocuments(Campaign $campaign, array $documentIds): void
    {
        $documents = $campaign->documents()->whereIn('id', $documentIds)->get();

        foreach ($documents as $document) {
            $this->deleteImage($document->file_path);
            $document->delete();
        }
    }
}
