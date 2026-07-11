<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TileType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'category',
        'label',
        'color',
        'sprite',
        'image_path',
        'footprint_width',
        'footprint_height',
        'render_scale',
        'is_buildable',
    ];

    protected $casts = [
        'is_buildable' => 'boolean',
        'render_scale' => 'float',
    ];

    public function spriteUrl(): string
    {
        if ($this->image_path) {
            return asset($this->image_path);
        }

        return 'data:image/svg+xml;base64,'.base64_encode($this->sprite);
    }
}
