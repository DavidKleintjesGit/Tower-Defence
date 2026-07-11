<?php

namespace App\Livewire\Admin\Maps;

use App\Models\Map;
use App\Models\TileType;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Index extends Component
{
    public function createBlank(): void
    {
        $width = 15;
        $height = 10;
        $defaultTile = TileType::where('category', 'ground')->value('code') ?? 'sand';

        $map = Map::create([
            'name' => 'Nieuwe map',
            'width' => $width,
            'height' => $height,
            'ground_grid' => array_fill(0, $height, array_fill(0, $width, $defaultTile)),
            'path_grid' => array_fill(0, $height, array_fill(0, $width, null)),
            'object_grid' => array_fill(0, $height, array_fill(0, $width, [])),
        ]);

        $this->redirect(route('admin.maps.edit', $map), navigate: true);
    }

    public function delete(int $mapId): void
    {
        Map::destroy($mapId);
    }

    public function render()
    {
        return view('livewire.admin.maps.index', [
            'maps' => Map::latest()->get(),
        ]);
    }
}
