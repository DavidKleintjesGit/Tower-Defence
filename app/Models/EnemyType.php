<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnemyType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'tagline',
        'description',
        'hp',
        'speed_multiplier',
        'domain',
        'spawns_code',
        'spawn_interval',
        'bounty',
        'color',
        'sprite',
        'walk_frames',
        'render_scale',
        'is_boss',
    ];

    protected $casts = [
        'walk_frames' => 'array',
    ];
}
