<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Models\TileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapObjectController extends Controller
{
    public function store(Request $request, Map $map): JsonResponse
    {
        $data = $request->validate([
            'tile_code' => ['required', 'string'],
            'x' => ['required', 'integer', 'min:0'],
            'y' => ['required', 'integer', 'min:0'],
        ]);

        $tileTypes = TileType::all()->keyBy('code');

        $tileType = $tileTypes[$data['tile_code']] ?? null;

        if (! $tileType) {
            return response()->json(['message' => 'Onbekend objecttype.'], 422);
        }

        $width = $tileType->footprint_width;
        $height = $tileType->footprint_height;

        if ($data['x'] + $width > $map->width || $data['y'] + $height > $map->height) {
            return response()->json(['message' => 'Object past niet binnen de map.'], 422);
        }

        $existingObjects = $map->objects()->get();

        $hit = $existingObjects->first(function ($object) use ($data, $tileTypes) {
            $objectType = $tileTypes[$object->tile_code] ?? null;
            $ow = $objectType->footprint_width ?? 1;
            $oh = $objectType->footprint_height ?? 1;

            return $data['x'] >= $object->x && $data['x'] < $object->x + $ow
                && $data['y'] >= $object->y && $data['y'] < $object->y + $oh;
        });

        if ($hit) {
            $hit->delete();

            return response()->json(['objects' => $map->objects()->get(['id', 'tile_code', 'x', 'y'])]);
        }

        $overlap = $existingObjects->first(function ($object) use ($data, $width, $height, $tileTypes) {
            $objectType = $tileTypes[$object->tile_code] ?? null;
            $ow = $objectType->footprint_width ?? 1;
            $oh = $objectType->footprint_height ?? 1;

            return $data['x'] < $object->x + $ow && $data['x'] + $width > $object->x
                && $data['y'] < $object->y + $oh && $data['y'] + $height > $object->y;
        });

        if ($overlap) {
            return response()->json(['message' => 'Hier staat al een object.'], 422);
        }

        $map->objects()->create([
            'tile_code' => $data['tile_code'],
            'x' => $data['x'],
            'y' => $data['y'],
        ]);

        return response()->json(['objects' => $map->objects()->get(['id', 'tile_code', 'x', 'y'])]);
    }
}
