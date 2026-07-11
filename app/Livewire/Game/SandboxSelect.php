<?php

namespace App\Livewire\Game;

use App\Models\Map;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class SandboxSelect extends Component
{
    public function render()
    {
        return view('livewire.game.sandbox-select', [
            'maps' => Map::orderBy('name')->get(),
        ]);
    }
}
