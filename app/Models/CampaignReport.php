<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignReport extends Model
{
    protected $fillable = [
        'id_campaign',
        'id_user',
        'reason',
        'description',
        'status',
        'admin_notes',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'id_campaign', 'id_campaign');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu',
            'reviewed'  => 'Sedang Ditinjau',
            'resolved'  => 'Diselesaikan',
            'dismissed' => 'Ditolak',
            default     => ucfirst($this->status),
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
