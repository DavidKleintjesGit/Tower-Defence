<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Services\MapPathValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MapRouteController extends Controller
{
    public function store(Request $request, Map $map): JsonResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(['entrance', 'path', 'exit'])],
            'x' => ['required', 'integer', 'min:0', 'max:'.($map->width - 1)],
            'y' => ['required', 'integer', 'min:0', 'max:'.($map->height - 1)],
        ]);

        $waypoints = $map->waypoints()->get();

        if ($data['type'] !== 'entrance' && ! $waypoints->contains('type', 'entrance')) {
            return response()->json(['message' => 'Plaats eerst een ingang.'], 422);
        }

        if ($data['type'] === 'path' && $waypoints->contains('type', 'exit')) {
            return response()->json(['message' => 'Verwijder eerst de uitgang om het pad te verlengen.'], 422);
        }

        DB::transaction(function () use ($map, $data, $waypoints) {
            if ($data['type'] === 'entrance') {
                $map->waypoints()->delete();

                $map->waypoints()->create([
                    'sequence' => 0,
                    'x' => $data['x'],
                    'y' => $data['y'],
                    'type' => 'entrance',
                    'lane' => 'main',
                ]);

                return;
            }

            if ($data['type'] === 'path') {
                $last = $waypoints->last();

                if ($last && $last->type !== 'entrance' && $last->x === $data['x'] && $last->y === $data['y']) {
                    $last->delete();

                    return;
                }

                $map->waypoints()->create([
                    'sequence' => $waypoints->max('sequence') + 1,
                    'x' => $data['x'],
                    'y' => $data['y'],
                    'type' => 'path',
                    'lane' => 'main',
                ]);

                return;
            }

            // exit
            $waypoints->firstWhere('type', 'exit')?->delete();

            $maxSequence = $waypoints->where('type', '!=', 'exit')->max('sequence') ?? -1;

            $map->waypoints()->create([
                'sequence' => $maxSequence + 1,
                'x' => $data['x'],
                'y' => $data['y'],
                'type' => 'exit',
                'lane' => 'main',
            ]);
        });

        return response()->json($this->respondWithValidatedState($map));
    }

    public function destroy(Map $map): JsonResponse
    {
        $map->waypoints()->delete();

        return response()->json($this->respondWithValidatedState($map));
    }

    private function respondWithValidatedState(Map $map): array
    {
        $map->refresh();

        $result = (new MapPathValidator)->validate($map);

        $map->update([
            'status' => $result['valid'] ? 'valid' : ($map->waypoints()->exists() ? 'invalid' : 'draft'),
            'validation_errors' => $result['errors'],
            'validated_at' => now(),
        ]);

        return [
            'waypoints' => $map->waypoints()->get(['x', 'y', 'type', 'sequence']),
            'status' => $map->status,
            'errors' => $result['errors'],
        ];
    }
}
