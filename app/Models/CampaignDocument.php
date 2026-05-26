<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignDocument extends Model
{
    protected $fillable = [
        'campaign_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id_campaign');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        return number_format($bytes / 1024, 0) . ' KB';
    }
}
