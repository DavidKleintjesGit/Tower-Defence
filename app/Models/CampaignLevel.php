<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'map_id',
        'order',
        'title',
        'area',
        'tagline',
        'icon_code',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}
