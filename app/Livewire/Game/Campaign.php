<?php

namespace App\Livewire\Game;

use App\Models\CampaignLevel;
use App\Models\TileType;
use App\Support\CampaignProgress;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.game')]
class Campaign extends Component
{
    /**
     * Per-level backdrop so each node hints at what it's about, from open
     * desert through the lab wing to the final reactor core.
     */
    private const THEMES = [
        1 => 'from-amber-900/40 to-slate-900 border-amber-500/40',
        2 => 'from-amber-800/40 to-slate-900 border-amber-500/30',
        3 => 'from-slate-600/40 to-slate-900 border-slate-400/40',
        4 => 'from-red-900/30 to-slate-900 border-red-500/40',
        5 => 'from-sky-900/30 to-slate-900 border-sky-500/30',
        6 => 'from-sky-800/30 to-slate-900 border-sky-500/30',
        7 => 'from-teal-900/30 to-slate-900 border-teal-500/30',
        8 => 'from-red-950/50 to-slate-900 border-red-600/50',
        9 => 'from-purple-900/40 to-slate-900 border-purple-500/40',
        10 => 'from-orange-900/50 to-slate-950 border-orange-500/50',
    ];

    public function render()
    {
        $levels = CampaignLevel::orderBy('order')->get()->map(fn ($level) => [
            'order' => $level->order,
            'title' => $level->title,
            'area' => $level->area,
            'tagline' => $level->tagline,
            'icon_code' => $level->icon_code,
            'map_id' => $level->map_id,
            'completed' => CampaignProgress::isCompleted($level->order),
            'unlocked' => CampaignProgress::isUnlocked($level->order),
            'theme' => self::THEMES[$level->order] ?? self::THEMES[1],
        ]);

        $iconSprites = TileType::whereIn('code', $levels->pluck('icon_code')->unique())
            ->get()
            ->mapWithKeys(fn ($tile) => [$tile->code => $tile->spriteUrl()]);

        return view('livewire.game.campaign', [
            'levels' => $levels,
            'iconSprites' => $iconSprites,
            'completedCount' => $levels->filter(fn ($l) => $l['completed'])->count(),
        ]);
    }
}
