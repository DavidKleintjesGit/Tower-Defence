<?php

namespace App\Livewire\Admin\Equipment;

use App\Models\EnemyType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.admin')]
class EditMonster extends Component
{
    public EnemyType $enemyType;

    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public ?string $tagline = null;

    #[Validate('nullable|string|max:2000')]
    public ?string $description = null;

    #[Validate('required|numeric|min:1')]
    public $hp = 0;

    #[Validate('required|numeric|min:0.1')]
    public $speed_multiplier = 1;

    #[Validate('required|in:ground,air')]
    public string $domain = 'ground';

    #[Validate('required|integer|min:0')]
    public $bounty = 0;

    #[Validate('required|numeric|min:0.1')]
    public $render_scale = 1;

    #[Validate('boolean')]
    public bool $is_boss = false;

    public bool $justSaved = false;

    public function mount(EnemyType $enemyType): void
    {
        $this->enemyType = $enemyType;
        $this->name = $enemyType->name;
        $this->tagline = $enemyType->tagline;
        $this->description = $enemyType->description;
        $this->hp = $enemyType->hp;
        $this->speed_multiplier = $enemyType->speed_multiplier;
        $this->domain = $enemyType->domain;
        $this->bounty = $enemyType->bounty;
        $this->render_scale = $enemyType->render_scale;
        $this->is_boss = $enemyType->is_boss;
    }

    public function save(): void
    {
        $this->enemyType->update($this->validate());
        $this->justSaved = true;
    }

    public function render()
    {
        return view('livewire.admin.equipment.edit-monster');
    }
}
