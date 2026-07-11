<?php

namespace App\Livewire\Game;

use App\Models\EnemyType;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class Bestiary extends Component
{
    public function render()
    {
        $enemyTypes = EnemyType::all()->map(function ($enemy) {
            $enemy->threat_score = round($enemy->hp * $enemy->speed_multiplier);
            $enemy->sprite_url = 'data:image/svg+xml;base64,'.base64_encode($enemy->sprite);
            $enemy->frame_urls = $enemy->walk_frames
                ? collect($enemy->walk_frames)->map(fn ($frame) => 'data:image/svg+xml;base64,'.base64_encode($frame))->all()
                : [];

            return $enemy;
        });

        return view('livewire.game.bestiary', [
            'enemyTypes' => $enemyTypes,
            'maxThreat' => $enemyTypes->max('threat_score') ?: 1,
            'hasBosses' => $enemyTypes->contains(fn ($enemy) => $enemy->is_boss),
        ]);
    }
}
