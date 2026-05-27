<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'id_donation';

    protected $fillable = [
        'id_campaign',
        'id_user',
        'donor_name',
        'donor_email',
        'donor_message',
        'is_anonymous',
        'donation_amount',
        'payment_status',
        'payment_method',
        'payment_token',
        'order_id',
        'paid_at',
    ];

    protected $casts = [
        'donation_amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'paid_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'id_campaign', 'id_campaign');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getDisplayNameAttribute(): string
    {
        if ($this->is_anonymous) {
            return 'Orang Baik';
        }
        return $this->donor_name;
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->donation_amount, 0, ',', '.');
    }

    /*
    |--------------------------------------------------------------------------
    | Status Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isFailed(): bool
    {
        return $this->payment_status === 'failed';
    }

    public function isExpired(): bool
    {
        return $this->payment_status === 'expired';
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSuccessful($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeForCampaign($query, int $campaignId)
    {
        return $query->where('id_campaign', $campaignId);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('id_user', $userId);
    }

    /*
    |--------------------------------------------------------------------------
    | Order ID Generation
    |--------------------------------------------------------------------------
    */

    public static function generateOrderId(): string
    {
        return 'DON-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -8));
    }
}
