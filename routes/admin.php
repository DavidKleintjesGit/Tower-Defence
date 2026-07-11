<?php

use App\Http\Controllers\Admin\MapBuildSpotController;
use App\Http\Controllers\Admin\MapObjectController;
use App\Http\Controllers\Admin\MapRouteController;
use App\Http\Controllers\Admin\MapTilesController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Maps\Edit as MapsEdit;
use App\Livewire\Admin\Maps\Index as MapsIndex;
use App\Livewire\Admin\TileTypes\Index as TileTypesIndex;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('objects', TileTypesIndex::class)->name('admin.tile-types.index');

    Route::get('maps', MapsIndex::class)->name('admin.maps.index');
    Route::get('maps/{map}', MapsEdit::class)->name('admin.maps.edit');
    Route::put('maps/{map}/tiles', [MapTilesController::class, 'update'])->name('admin.maps.tiles.update');
    Route::post('maps/{map}/route', [MapRouteController::class, 'store'])->name('admin.maps.route.store');
    Route::delete('maps/{map}/route', [MapRouteController::class, 'destroy'])->name('admin.maps.route.destroy');
    Route::post('maps/{map}/build-spots', [MapBuildSpotController::class, 'store'])->name('admin.maps.build-spots.store');
    Route::post('maps/{map}/objects', [MapObjectController::class, 'store'])->name('admin.maps.objects.store');

    Route::view('profile', 'profile')->name('profile');
});
