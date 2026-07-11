<?php

namespace App\Livewire\Game;

use App\Models\TowerType;
use App\Support\TowerUpgrades;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class Armory extends Component
{
    public function render()
    {
        $towerTypes = TowerType::all()->map(function ($tower) {
            $tower->rate_per_sec = 1 / $tower->fire_interval;
            $tower->dps = round($tower->damage * $tower->rate_per_sec, 1);
            $tower->sprite_url = 'data:image/svg+xml;base64,'.base64_encode($tower->sprite);
            $tower->base_url = $tower->base_sprite ? 'data:image/svg+xml;base64,'.base64_encode($tower->base_sprite) : null;
            $tower->head_url = $tower->head_sprite ? 'data:image/svg+xml;base64,'.base64_encode($tower->head_sprite) : null;
            $tower->muzzle_url = $tower->muzzle_flash_sprite ? 'data:image/svg+xml;base64,'.base64_encode($tower->muzzle_flash_sprite) : null;
            $tower->upgrade_tiers = TowerUpgrades::tiers($tower);

            return $tower;
        });

        return view('livewire.game.armory', [
            'towerTypes' => $towerTypes,
        ]);
    }
}
