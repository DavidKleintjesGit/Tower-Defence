<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TowerType extends Model
{
    protected $fillable = [
        'code',
        'name',
        'tagline',
        'description',
        'damage',
        'range_tiles',
        'fire_interval',
        'cost',
        'color',
        'sprite',
        'base_sprite',
        'head_sprite',
        'muzzle_flash_sprite',
        'projectile_sprite',
        'projectile_style',
        'render_scale',
    ];
}
