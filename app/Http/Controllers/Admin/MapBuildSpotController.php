<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Models\TileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapBuildSpotController extends Controller
{
    public function store(Request $request, Map $map): JsonResponse
    {
        $data = $request->validate([
            'x' => ['required', 'integer', 'min:0', 'max:'.($map->width - 1)],
            'y' => ['required', 'integer', 'min:0', 'max:'.($map->height - 1)],
        ]);

        $existing = $map->buildSpots()->where('x', $data['x'])->where('y', $data['y'])->first();

        if ($existing) {
            $existing->delete();

            return response()->json(['build_spots' => $map->buildSpots()->get(['id', 'x', 'y'])]);
        }

        $groundCode = $map->ground_grid[$data['y']][$data['x']] ?? null;
        $isBuildable = TileType::where('code', $groundCode)->value('is_buildable');

        if (! $isBuildable) {
            return response()->json(['message' => 'Bouwplaatsen kunnen alleen op geschikte grond.'], 422);
        }

        $onRoute = $map->waypoints()->where('x', $data['x'])->where('y', $data['y'])->exists();

        if ($onRoute) {
            return response()->json(['message' => 'Hier ligt de route, kies een andere plek.'], 422);
        }

        $map->buildSpots()->create(['x' => $data['x'], 'y' => $data['y']]);

        return response()->json(['build_spots' => $map->buildSpots()->get(['id', 'x', 'y'])]);
    }
}
