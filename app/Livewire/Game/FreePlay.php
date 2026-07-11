<?php

namespace App\Livewire\Game;

use App\Models\Map;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class FreePlay extends Component
{
    public function render()
    {
        return view('livewire.game.free-play', [
            'maps' => Map::where('status', 'published')->orderBy('name')->get(),
        ]);
    }
}
