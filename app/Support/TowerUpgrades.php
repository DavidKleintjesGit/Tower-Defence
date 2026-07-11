<?php

namespace App\Support;

use App\Models\TowerType;

/**
 * Computes a 3-level upgrade path from a tower type's base stats. Kept
 * deliberately separate from TowerType/its seeder (no schema changes) so it
 * can't collide with other concurrent work on the tower/enemy data itself —
 * this only derives numbers from whatever base stats already exist.
 */
class TowerUpgrades
{
    private const LEVELS = [
        1 => ['damage' => 1.0, 'range' => 1.0, 'fire_rate' => 1.0, 'cost_multiplier' => 0],
        2 => ['damage' => 1.4, 'range' => 1.1, 'fire_rate' => 1.15, 'cost_multiplier' => 1.2],
        3 => ['damage' => 1.9, 'range' => 1.2, 'fire_rate' => 1.35, 'cost_multiplier' => 1.6],
    ];

    /**
     * @return array<int, array{level: int, damage: float, range_tiles: float, fire_interval: float, rate_per_sec: float, dps: float, upgrade_cost: int}>
     */
    public static function tiers(TowerType $tower): array
    {
        return collect(self::LEVELS)->map(function (array $multiplier, int $level) use ($tower) {
            $damage = round($tower->damage * $multiplier['damage'], 1);
            $fireInterval = round($tower->fire_interval / $multiplier['fire_rate'], 3);
            $ratePerSec = round(1 / $fireInterval, 2);

            return [
                'level' => $level,
                'damage' => $damage,
                'range_tiles' => round($tower->range_tiles * $multiplier['range'], 2),
                'fire_interval' => $fireInterval,
                'rate_per_sec' => $ratePerSec,
                'dps' => round($damage * $ratePerSec, 1),
                'upgrade_cost' => $level === 1 ? 0 : (int) round($tower->cost * $multiplier['cost_multiplier']),
            ];
        })->values()->all();
    }

    public static function maxLevel(): int
    {
        return max(array_keys(self::LEVELS));
    }
}
