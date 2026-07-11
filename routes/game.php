<?php

use App\Livewire\Game\Armory;
use App\Livewire\Game\Bestiary;
use App\Livewire\Game\FreePlay;
use App\Livewire\Game\Menu;
use App\Livewire\Game\Play;
use App\Livewire\Game\Sandbox;
use App\Livewire\Game\SandboxSelect;
use Illuminate\Support\Facades\Route;

Route::get('/', Menu::class)->name('home');
Route::get('play/{mapId?}', Play::class)->name('game.play');
Route::get('free-play', FreePlay::class)->name('game.free-play');
Route::get('sandbox-select', SandboxSelect::class)->name('game.sandbox-select');
Route::get('sandbox/{mapId?}', Sandbox::class)->name('game.sandbox');
Route::get('bestiary', Bestiary::class)->name('game.bestiary');
Route::get('armory', Armory::class)->name('game.armory');
