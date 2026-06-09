<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'site_name',
        'email',
        'phone',
        'address',
        'social_media',
        'footer_text',
    ];

    protected $casts = [
        'social_media' => 'array',
    ];
}
