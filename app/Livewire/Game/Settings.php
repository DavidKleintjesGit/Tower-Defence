<?php

namespace App\Livewire\Game;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class Settings extends Component
{
    public function render()
    {
        return view('livewire.game.settings');
    }
}
