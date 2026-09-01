<?php

namespace App\Livewire\Admin\Equipment;

use App\Models\TowerType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.admin')]
class EditWeapon extends Component
{
    public TowerType $towerType;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public ?string $tagline = null;

    #[Validate('nullable|string|max:2000')]
    public ?string $description = null;

    #[Validate('required|numeric|min:0')]
    public $damage = 0;

    #[Validate('required|numeric|min:0')]
    public $range_tiles = 0;

    #[Validate('required|numeric|min:0.01')]
    public $fire_interval = 0;

    #[Validate('required|integer|min:0')]
    public $cost = 0;

    #[Validate('required|numeric|min:0.1')]
    public $render_scale = 1;

    #[Validate('boolean')]
    public bool $splash_damage = false;

    #[Validate('boolean')]
    public bool $multi_target = false;

    #[Validate('boolean')]
    public bool $targets_ground = true;

    #[Validate('boolean')]
    public bool $targets_air = false;

    public bool $justSaved = false;

    public function mount(TowerType $towerType): void
    {
        $this->towerType = $towerType;
        $this->name = $towerType->name;
        $this->tagline = $towerType->tagline;
        $this->description = $towerType->description;
        $this->damage = $towerType->damage;
        $this->range_tiles = $towerType->range_tiles;
        $this->fire_interval = $towerType->fire_interval;
        $this->cost = $towerType->cost;
        $this->render_scale = $towerType->render_scale;
        $this->splash_damage = $towerType->splash_damage;
        $this->multi_target = $towerType->multi_target;
        $this->targets_ground = $towerType->targets_ground;
        $this->targets_air = $towerType->targets_air;
    }

    public function save(): void
    {
        $this->towerType->update($this->validate());
        $this->justSaved = true;
    }

    public function render()
    {
        return view('livewire.admin.equipment.edit-weapon');
    }
}
