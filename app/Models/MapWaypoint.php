<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapWaypoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id',
        'sequence',
        'x',
        'y',
        'type',
        'lane',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}
