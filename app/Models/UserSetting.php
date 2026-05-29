<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id',
        'show_profile_publicly',
        'show_followers_count',
        'show_following_count',
        'notify_donation_received',
        'notify_campaign_approved',
        'notify_withdrawal_approved',
    ];

    protected $casts = [
        'show_profile_publicly' => 'boolean',
        'show_followers_count' => 'boolean',
        'show_following_count' => 'boolean',
        'notify_donation_received' => 'boolean',
        'notify_campaign_approved' => 'boolean',
        'notify_withdrawal_approved' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}
