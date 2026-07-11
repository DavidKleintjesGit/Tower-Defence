<?php

namespace App\Services;

use App\Models\Map;

class MapPathValidator
{
    /**
     * @return array{valid: bool, errors: array<int, string>}
     */
    public function validate(Map $map): array
    {
        $waypoints = $map->waypoints()->get();

        if ($waypoints->isEmpty()) {
            return ['valid' => false, 'errors' => ['Er is nog geen route getekend.']];
        }

        $errors = [];

        $entrances = $waypoints->where('type', 'entrance');
        $exits = $waypoints->where('type', 'exit');

        if ($entrances->count() !== 1) {
            $errors[] = 'Er moet precies één ingang zijn.';
        } elseif ($waypoints->first()->type !== 'entrance') {
            $errors[] = 'De ingang moet het eerste punt van de route zijn.';
        }

        if ($exits->count() !== 1) {
            $errors[] = 'Er moet precies één uitgang zijn.';
        } elseif ($waypoints->last()->type !== 'exit') {
            $errors[] = 'De uitgang moet het laatste punt van de route zijn.';
        }

        if ($waypoints->count() < 2) {
            $errors[] = 'De route heeft minstens een ingang en een uitgang nodig.';
        }

        // Waypoints no longer need to be grid-adjacent: enemies walk a
        // straight line between consecutive points, so a route can be just
        // a handful of corner points instead of every tile in between.
        $seen = [];

        foreach ($waypoints as $waypoint) {
            $key = $waypoint->x.','.$waypoint->y;

            if (isset($seen[$key])) {
                $errors[] = "Punt ({$waypoint->x}, {$waypoint->y}) komt dubbel voor in de route.";
            }

            $seen[$key] = true;

            if ($waypoint->x < 0 || $waypoint->x >= $map->width || $waypoint->y < 0 || $waypoint->y >= $map->height) {
                $errors[] = "Punt ({$waypoint->x}, {$waypoint->y}) valt buiten de map.";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => array_values(array_unique($errors)),
        ];
    }
}
