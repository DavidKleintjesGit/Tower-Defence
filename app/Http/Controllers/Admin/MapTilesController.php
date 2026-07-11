<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Models\TileType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MapTilesController extends Controller
{
    public function update(Request $request, Map $map): JsonResponse
    {
        $groundCodes = TileType::where('category', 'ground')->pluck('code')->all();
        $roadCodes = TileType::where('category', 'road')->pluck('code')->all();
        $fenceCodes = TileType::where('category', 'fence')->pluck('code')->all();
        $objectCodes = TileType::where('category', 'decoration')->pluck('code')->all();

        $request->validate([
            'ground_grid' => ['required', 'array', 'size:'.$map->height],
            'ground_grid.*' => ['array', 'size:'.$map->width],
            'ground_grid.*.*' => ['required', 'string', Rule::in($groundCodes)],
            'path_grid' => ['required', 'array', 'size:'.$map->height],
            'path_grid.*' => ['array', 'size:'.$map->width],
            'path_grid.*.*' => ['nullable', 'string', Rule::in($roadCodes)],
            'fence_grid' => ['required', 'array', 'size:'.$map->height],
            'fence_grid.*' => ['array', 'size:'.$map->width],
            'fence_grid.*.*' => ['nullable', 'string', Rule::in($fenceCodes)],
            'object_grid' => ['required', 'array', 'size:'.$map->height],
            'object_grid.*' => ['array', 'size:'.$map->width],
            'object_grid.*.*' => ['array'],
            'object_grid.*.*.*' => ['array'],
            'object_grid.*.*.*.code' => ['required', 'string', Rule::in($objectCodes)],
            'object_grid.*.*.*.sx' => ['required', 'integer', 'min:0', 'max:2'],
            'object_grid.*.*.*.sy' => ['required', 'integer', 'min:0', 'max:2'],
            'tilt_angle' => ['nullable', 'integer', 'min:0', 'max:35'],
        ]);

        // $request->validate()'s reconstructed nested wildcard data can lose list
        // ordering (json_encode then emits objects instead of arrays), so persist
        // the raw, already-validated input instead of the validator's return value.
        $map->update([
            'ground_grid' => $request->input('ground_grid'),
            'path_grid' => $request->input('path_grid'),
            'fence_grid' => $request->input('fence_grid'),
            'object_grid' => $request->input('object_grid'),
            'tilt_angle' => $request->input('tilt_angle', $map->tilt_angle),
        ]);

        return response()->json(['status' => 'saved']);
    }
}
