<?php

namespace App\Livewire\Admin\Equipment;

use App\Models\EnemyType;
use App\Models\TowerType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.admin')]
class Index extends Component
{
    #[Url]
    public string $tab = 'weapons';

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.equipment.index', [
            'towerTypes' => TowerType::orderBy('name')->get(),
            'enemyTypes' => EnemyType::orderBy('name')->get(),
        ]);
    }
}
