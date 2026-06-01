<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'id_category',
        'title',
        'slug',
        'short_description',
        'description',
        'target_amount',
        'minimum_donation',
        'collected_amount',
        'withdrawn_amount',
        'available_balance',
        'campaign_status',
        'verification_status',
        'image',
        'video_url',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'minimum_donation' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'withdrawn_amount' => 'decimal:2',
        'available_balance' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the user that owns the campaign (Custom Foreign Key dari branch feature/auth-profile)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        return ($this->collected_amount / $this->target_amount) * 100;
    }

    public function scopeActive($query)
    {
        return $query->where('campaign_status', 'active')
                     ->where('verification_status', 'active')
                     ->where('end_date', '>', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('id_category', $categoryId);
    }

    public function scopeApproved($query)
    {
        return $query->where('verification_status', 'active');
    }

    public function comments() { return $this->morphMany(Comment::class, 'commentable'); }
    public function likes() { return $this->morphMany(Like::class, 'likeable'); }
    public function reports() { return $this->morphMany(Report::class, 'reportable'); }
public function isLikedByUser($userId) {
    return $this->likes()->where('user_id', $userId)->exists();
}
}