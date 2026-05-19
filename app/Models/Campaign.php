<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'target_amount',
        'current_amount',
        'banner_image',
        'status',
    ];

    /**
     * Get the user that owns the campaign.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}

