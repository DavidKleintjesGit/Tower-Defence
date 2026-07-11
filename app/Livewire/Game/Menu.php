<?php

namespace App\Livewire\Game;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class Menu extends Component
{
    public function render()
    {
        return view('livewire.game.menu');
    }
}
