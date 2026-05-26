<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $primaryKey = 'id_category';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'id_category', 'id_category');
    }

    /**
     * Get the count of active campaigns in this category.
     */
    public function getActiveCampaignsCountAttribute(): int
    {
        return $this->campaigns()->where('campaign_status', 'active')->count();
    }
}
