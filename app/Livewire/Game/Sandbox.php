<?php

namespace App\Livewire\Game;

use App\Http\Resources\MapResource;
use App\Models\EnemyType;
use App\Models\Map;
use App\Models\TileType;
use App\Models\TowerType;
use App\Support\RoadArt;
use App\Support\TowerUpgrades;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class Sandbox extends Component
{
    public ?Map $map = null;

    public function mount(?int $mapId = null): void
    {
        $this->map = $mapId
            ? Map::find($mapId)
            : Map::latest()->first();
    }

    public function render()
    {
        $mapData = null;
        $enemyTypes = [];
        $towerTypes = [];

        if ($this->map) {
            $tileTypes = TileType::all();

            $mapData = (new MapResource($this->map))->resolve();
            $mapData['tile_colors'] = $tileTypes->pluck('color', 'code');
            $mapData['tile_sprites'] = $tileTypes->mapWithKeys(
                fn ($tile) => [$tile->code => $tile->spriteUrl()]
            );
            $mapData['tile_footprints'] = $tileTypes->mapWithKeys(
                fn ($tile) => [$tile->code => ['width' => $tile->footprint_width, 'height' => $tile->footprint_height]]
            );
            $mapData['tile_scales'] = $tileTypes->mapWithKeys(
                fn ($tile) => [$tile->code => (float) $tile->render_scale]
            );
            $mapData['road_assets'] = $tileTypes->where('category', 'road')->mapWithKeys(
                fn ($tile) => [$tile->code => RoadArt::roadAssets($tile->code)]
            );
            $mapData['fence_assets'] = $tileTypes->where('category', 'fence')->mapWithKeys(
                fn ($tile) => [$tile->code => RoadArt::fenceAssets($tile->code)]
            );

            $enemyTypes = EnemyType::all()->map(fn ($enemy) => [
                'code' => $enemy->code,
                'name' => $enemy->name,
                'hp' => $enemy->hp,
                'speed_multiplier' => $enemy->speed_multiplier,
                'bounty' => $enemy->bounty,
                'render_scale' => (float) $enemy->render_scale,
                'sprite' => 'data:image/svg+xml;base64,'.base64_encode($enemy->sprite),
                'walk_frames' => $enemy->walk_frames
                    ? collect($enemy->walk_frames)->map(fn ($frame) => 'data:image/svg+xml;base64,'.base64_encode($frame))->all()
                    : null,
            ]);

            $towerTypes = TowerType::all()->map(fn ($tower) => [
                'code' => $tower->code,
                'name' => $tower->name,
                'damage' => $tower->damage,
                'range_tiles' => $tower->range_tiles,
                'fire_interval' => $tower->fire_interval,
                'cost' => $tower->cost,
                'render_scale' => (float) $tower->render_scale,
                'projectile_style' => $tower->projectile_style,
                'sprite' => 'data:image/svg+xml;base64,'.base64_encode($tower->sprite),
                'base_sprite' => $tower->base_sprite ? 'data:image/svg+xml;base64,'.base64_encode($tower->base_sprite) : null,
                'head_sprite' => $tower->head_sprite ? 'data:image/svg+xml;base64,'.base64_encode($tower->head_sprite) : null,
                'muzzle_flash_sprite' => $tower->muzzle_flash_sprite ? 'data:image/svg+xml;base64,'.base64_encode($tower->muzzle_flash_sprite) : null,
                'projectile_sprite' => $tower->projectile_sprite ? 'data:image/svg+xml;base64,'.base64_encode($tower->projectile_sprite) : null,
                'upgrade_tiers' => TowerUpgrades::tiers($tower),
            ]);

            $mapData['enemy_types'] = $enemyTypes;
            $mapData['tower_types'] = $towerTypes;
            $mapData['sandbox'] = true;
        }

        return view('livewire.game.sandbox', [
            'mapData' => $mapData,
            'towerTypes' => $towerTypes,
            'enemyTypes' => $enemyTypes,
        ]);
    }
}
