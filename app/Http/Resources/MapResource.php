<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MapResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'width' => $this->width,
            'height' => $this->height,
            'tile_size' => $this->tile_size,
            'tilt_angle' => $this->tilt_angle,
            'status' => $this->status,
            'ground_grid' => $this->ground_grid,
            'path_grid' => $this->path_grid,
            'fence_grid' => $this->fence_grid,
            'object_grid' => $this->object_grid,
            'waypoints' => $this->waypoints->map(fn ($waypoint) => [
                'x' => $waypoint->x,
                'y' => $waypoint->y,
                'type' => $waypoint->type,
                'sequence' => $waypoint->sequence,
            ]),
            'build_spots' => $this->buildSpots->map(fn ($spot) => [
                'id' => $spot->id,
                'x' => $spot->x,
                'y' => $spot->y,
            ]),
            'objects' => $this->objects->map(fn ($object) => [
                'tile_code' => $object->tile_code,
                'x' => $object->x,
                'y' => $object->y,
            ]),
        ];
    }
}
