<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapObject extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id',
        'tile_code',
        'x',
        'y',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}
