<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Map extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'width',
        'height',
        'tile_size',
        'tilt_angle',
        'ground_grid',
        'path_grid',
        'fence_grid',
        'object_grid',
        'status',
        'validation_errors',
        'validated_at',
    ];

    protected $casts = [
        'ground_grid' => 'array',
        'path_grid' => 'array',
        'fence_grid' => 'array',
        'object_grid' => 'array',
        'validation_errors' => 'array',
        'validated_at' => 'datetime',
    ];

    public function waypoints(): HasMany
    {
        return $this->hasMany(MapWaypoint::class)->orderBy('sequence');
    }

    public function buildSpots(): HasMany
    {
        return $this->hasMany(MapBuildSpot::class);
    }

    public function objects(): HasMany
    {
        return $this->hasMany(MapObject::class);
    }
}
