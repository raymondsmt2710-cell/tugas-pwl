<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_campaign';

    protected $fillable = [
        'id_user',
        'id_category',
        'title',
        'slug',
        'short_description',
        'description',
        'target_amount',
        'minimum_donation',
        'collected_amount',
        'withdrawal_amount',
        'available_balance',
        'banner_image',
        'video_url',
        'campaign_status',
        'verification_status',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'minimum_donation' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'withdrawal_amount' => 'decimal:2',
        'available_balance' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_category', 'id_category');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'id_campaign', 'id_campaign');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(CampaignGallery::class, 'campaign_id', 'id_campaign')
            ->orderBy('sort_order');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CampaignDocument::class, 'campaign_id', 'id_campaign');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount == 0) {
            return 0;
        }

        return min(100, ($this->collected_amount / $this->target_amount) * 100);
    }

    public function getBannerImageUrlAttribute(): ?string
    {
        return $this->banner_image ? asset('storage/' . $this->banner_image) : null;
    }

    public function getDaysRemainingAttribute(): int
    {
        if (!$this->end_date || $this->end_date->isPast()) {
            return 0;
        }

        return (int) now()->diffInDays($this->end_date, false);
    }

    public function getDonorCountAttribute(): int
    {
        return $this->donations()->where('payment_status', 'success')->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the campaign is editable by its owner.
     * Only draft and rejected campaigns can be edited.
     */
    public function isEditable(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    /**
     * Check if the campaign is deletable by its owner.
     * Only draft and rejected campaigns can be deleted.
     */
    public function isDeletable(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    /**
     * Check if the campaign can accept donations.
     */
    public function canAcceptDonations(): bool
    {
        return $this->status === 'approved'
            && $this->end_date->isFuture()
            && $this->collected_amount < $this->target_amount;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
                     ->where('end_date', '>', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('id_category', $categoryId);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('id_user', $userId);
    }

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('short_description', 'like', "%{$search}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Slug Generation
    |--------------------------------------------------------------------------
    */

    /**
     * Generate a unique slug from the title.
     */
    public static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);

        if (empty($slug)) {
            $slug = 'campaign';
        }

        $originalSlug = $slug;
        $counter = 1;

        $query = static::withTrashed()->where('slug', $slug);
        if ($excludeId) {
            $query->where('id_campaign', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;

            $query = static::withTrashed()->where('slug', $slug);
            if ($excludeId) {
                $query->where('id_campaign', '!=', $excludeId);
            }
        }

        return $slug;
    }
}
