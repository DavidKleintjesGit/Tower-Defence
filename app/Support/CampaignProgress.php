<?php

namespace App\Support;

/**
 * Anonymous, session-based progress (players aren't authenticated) — a
 * simple list of completed level numbers. Level 1 is always unlocked;
 * level N is unlocked once N-1 is completed.
 */
class CampaignProgress
{
    private const SESSION_KEY = 'campaign.completed_levels';

    public static function completedLevels(): array
    {
        return session(self::SESSION_KEY, []);
    }

    public static function isCompleted(int $order): bool
    {
        return in_array($order, self::completedLevels(), true);
    }

    public static function isUnlocked(int $order): bool
    {
        return $order === 1 || self::isCompleted($order - 1);
    }

    public static function markCompleted(int $order): void
    {
        $completed = self::completedLevels();

        if (! in_array($order, $completed, true)) {
            $completed[] = $order;
            session([self::SESSION_KEY => $completed]);
        }
    }
}
