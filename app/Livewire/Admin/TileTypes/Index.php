<?php

namespace App\Livewire\Admin\TileTypes;

use App\Models\TileType;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class Index extends Component
{
    use WithFileUploads;

    /** @var array<string, mixed> */
    public array $uploads = [];

    public string $newLabel = '';

    public string $newCode = '';

    public $newImage;

    public bool $newIsLarge = false;

    public const SIZE_STEPS = [
        1 => ['scale' => 1.0, 'label' => 'XS'],
        2 => ['scale' => 1.3, 'label' => 'S'],
        3 => ['scale' => 1.6, 'label' => 'M'],
        4 => ['scale' => 2.0, 'label' => 'L'],
        5 => ['scale' => 2.4, 'label' => 'XL'],
        6 => ['scale' => 3.0, 'label' => 'XXL'],
    ];

    public function stepForScale(float $scale): int
    {
        $closest = 1;
        $smallestDiff = null;

        foreach (self::SIZE_STEPS as $step => $data) {
            $diff = abs($data['scale'] - $scale);

            if ($smallestDiff === null || $diff < $smallestDiff) {
                $smallestDiff = $diff;
                $closest = $step;
            }
        }

        return $closest;
    }

    public function setScale(string $code, int $step): void
    {
        $step = max(1, min(6, $step));

        TileType::where('code', $code)->update([
            'render_scale' => self::SIZE_STEPS[$step]['scale'],
        ]);
    }

    public function saveUpload(string $code): void
    {
        $file = $this->uploads[$code] ?? null;

        if (! $file) {
            return;
        }

        $this->validate([
            "uploads.{$code}" => ['image', 'max:4096'],
        ]);

        $path = $file->storeAs('tiles/uploads', $code.'-'.time().'.'.$file->getClientOriginalExtension(), 'public');

        TileType::where('code', $code)->update([
            'image_path' => 'storage/'.$path,
        ]);

        unset($this->uploads[$code]);
    }

    public function clearImage(string $code): void
    {
        TileType::where('code', $code)->update(['image_path' => null]);
    }

    public function createTileType(): void
    {
        $validated = $this->validate([
            'newLabel' => ['required', 'string', 'max:255'],
            'newCode' => [
                'required', 'string', 'max:255', 'alpha_dash',
                function ($attribute, $value, $fail) {
                    if (TileType::where('code', $value)->exists()) {
                        $fail('Deze code bestaat al.');
                    }
                },
            ],
            'newImage' => ['required', 'image', 'max:4096'],
        ]);

        $path = $this->newImage->storeAs('tiles/uploads', $validated['newCode'].'-'.time().'.'.$this->newImage->getClientOriginalExtension(), 'public');

        TileType::create([
            'code' => $validated['newCode'],
            'category' => 'decoration',
            'label' => $validated['newLabel'],
            'color' => '#8a8f94',
            'sprite' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"></svg>',
            'image_path' => 'storage/'.$path,
            'footprint_width' => $this->newIsLarge ? 2 : 1,
            'footprint_height' => $this->newIsLarge ? 2 : 1,
            'render_scale' => 1.0,
            'is_buildable' => false,
        ]);

        $this->reset(['newLabel', 'newCode', 'newImage', 'newIsLarge']);
    }

    public function render()
    {
        $tileTypes = TileType::orderBy('category')->orderBy('label')->get();

        return view('livewire.admin.tile-types.index', [
            'grouped' => $tileTypes->groupBy('category'),
            'sizeSteps' => self::SIZE_STEPS,
        ]);
    }
}
