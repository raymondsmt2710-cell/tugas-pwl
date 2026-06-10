<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HowItWork extends Model
{
    protected $table = 'how_it_works';

    protected $fillable = [
        'type',
        'step_number',
        'title',
        'description',
        'icon',
        'color',
        'icon_color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'step_number' => 'integer',
    ];
}
