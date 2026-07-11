<?php

namespace App\Livewire\Game;

use Livewire\Component;

class ComingSoonNotice extends Component
{
    public string $label;

    public string $feature;

    public string $icon = 'default';

    public bool $open = false;

    public function mount(string $label, ?string $feature = null, string $icon = 'default'): void
    {
        $this->label = $label;
        $this->feature = $feature ?? $label;
        $this->icon = $icon;
    }

    public function show(): void
    {
        $this->open = true;
    }

    public function hide(): void
    {
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.game.coming-soon-notice');
    }
}
