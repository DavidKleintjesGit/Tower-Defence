<?php

namespace App\Livewire\Admin\Maps;

use App\Models\EnemyType;
use App\Models\Map;
use App\Models\TileType;
use App\Services\MapPathValidator;
use App\Support\RoadArt;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin-builder')]
class Edit extends Component
{
    public Map $map;

    public string $name = '';

    public int $width = 0;

    public int $height = 0;

    public function mount(Map $map): void
    {
        $this->map = $map;
        $this->name = $map->name;
        $this->width = $map->width;
        $this->height = $map->height;
    }

    public function saveSettings(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'width' => ['required', 'integer', 'min:5', 'max:50'],
            'height' => ['required', 'integer', 'min:5', 'max:50'],
        ]);

        $defaultTile = TileType::where('category', 'ground')->value('code') ?? 'sand';

        $this->map->update([
            'name' => $validated['name'],
            'width' => $validated['width'],
            'height' => $validated['height'],
            'ground_grid' => $this->resizeGrid($this->map->ground_grid ?? [], $validated['width'], $validated['height'], $defaultTile),
            'path_grid' => $this->resizeGrid($this->map->path_grid ?? [], $validated['width'], $validated['height'], null),
            'fence_grid' => $this->resizeGrid($this->map->fence_grid ?? [], $validated['width'], $validated['height'], null),
            'object_grid' => $this->resizeGrid($this->map->object_grid ?? [], $validated['width'], $validated['height'], []),
        ]);

        // Route points and build spots that fall outside the new bounds are no
        // longer valid; drop them and let the validator recompute the status.
        $this->map->waypoints()
            ->where(fn ($query) => $query->where('x', '>=', $validated['width'])->orWhere('y', '>=', $validated['height']))
            ->delete();

        $this->map->buildSpots()
            ->where(fn ($query) => $query->where('x', '>=', $validated['width'])->orWhere('y', '>=', $validated['height']))
            ->delete();

        $tileTypesByCode = TileType::all()->keyBy('code');

        $this->map->objects()->get()->each(function ($object) use ($tileTypesByCode, $validated) {
            $type = $tileTypesByCode[$object->tile_code] ?? null;
            $w = $type->footprint_width ?? 1;
            $h = $type->footprint_height ?? 1;

            if ($object->x + $w > $validated['width'] || $object->y + $h > $validated['height']) {
                $object->delete();
            }
        });

        $this->revalidateRoute();

        // The grid is rendered once and then owned by client-side JS (wire:ignore),
        // so a resize needs a real page load to reflect the new dimensions.
        $this->redirect(route('admin.maps.edit', $this->map));
    }

    public function publish(): void
    {
        $this->revalidateRoute();

        if ($this->map->status !== 'valid') {
            return;
        }

        $this->map->update(['status' => 'published']);
        $this->map->refresh();
    }

    public function unpublish(): void
    {
        if ($this->map->status !== 'published') {
            return;
        }

        $this->map->update(['status' => 'valid']);
        $this->map->refresh();
    }

    public function checkRoute(): void
    {
        $this->revalidateRoute();
    }

    private function revalidateRoute(): void
    {
        $this->map->refresh();

        $result = (new MapPathValidator)->validate($this->map);

        $this->map->update([
            'status' => $result['valid'] ? 'valid' : ($this->map->waypoints()->exists() ? 'invalid' : 'draft'),
            'validation_errors' => $result['errors'],
            'validated_at' => now(),
        ]);

        $this->map->refresh();
    }

    private function resizeGrid(array $grid, int $width, int $height, mixed $default): array
    {
        $resized = [];

        for ($y = 0; $y < $height; $y++) {
            $row = [];

            for ($x = 0; $x < $width; $x++) {
                $row[] = $grid[$y][$x] ?? $default;
            }

            $resized[] = $row;
        }

        return $resized;
    }

    public function render()
    {
        $tileTypes = TileType::orderBy('category')->orderBy('label')->get();

        return view('livewire.admin.maps.edit', [
            'tileTypes' => $tileTypes,
            'tileColors' => $tileTypes->pluck('color', 'code'),
            'tileLabels' => $tileTypes->pluck('label', 'code'),
            'tileSprites' => $tileTypes->mapWithKeys(
                fn ($tile) => [$tile->code => $tile->spriteUrl()]
            ),
            'tileScales' => $tileTypes->pluck('render_scale', 'code'),
            'roadAssets' => $tileTypes->where('category', 'road')->mapWithKeys(
                fn ($tile) => [$tile->code => RoadArt::roadAssets($tile->code)]
            ),
            'fenceAssets' => $tileTypes->where('category', 'fence')->mapWithKeys(
                fn ($tile) => [$tile->code => RoadArt::fenceAssets($tile->code)]
            ),
            'waypoints' => $this->map->waypoints,
            'buildSpots' => $this->map->buildSpots,
            'largeObjects' => $tileTypes->filter(fn ($tile) => $tile->footprint_width > 1 || $tile->footprint_height > 1),
            'mapObjects' => $this->map->objects,
            'enemyTypes' => EnemyType::all(),
        ]);
    }
}
