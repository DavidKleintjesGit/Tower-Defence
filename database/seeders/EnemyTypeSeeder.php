<?php

namespace Database\Seeders;

use App\Models\EnemyType;
use Illuminate\Database\Seeder;

class EnemyTypeSeeder extends Seeder
{
    public function run(): void
    {
        EnemyType::whereNotIn('code', ['alien', 'proefpersoon', 'slijmmonster', 'boss-groene-kolos'])->delete();

        $enemyTypes = [
            [
                'code' => 'alien',
                'name' => 'Alien',
                'tagline' => 'Weg voor je "halt" kan roepen.',
                'description' => 'Ontsnapt uit een neergestort schijfje bij Hangar 3. Grijze huid, veel te grote ogen, en een groene bio-gloed die nooit helemaal uitgaat.',
                'hp' => 40,
                'speed_multiplier' => 1.1,
                'bounty' => 12,
                'render_scale' => 1,
                'color' => '#8b96a3',
                'sprite' => $this->alienFrame(0),
                'walk_frames' => [
                    $this->alienFrame(18),
                    $this->alienFrame(0),
                    $this->alienFrame(-18),
                    $this->alienFrame(0),
                ],
            ],
            [
                'code' => 'proefpersoon',
                'name' => 'Ontsnapte Proefpersoon',
                'tagline' => 'Wat ze injecteerden, werkte te goed.',
                'description' => 'Voormalig testsubject uit Lab 9, opgevoerd met militaire cybernetica. Sterker en gepantserder dan de gemiddelde indringer.',
                'hp' => 55,
                'speed_multiplier' => 1.05,
                'bounty' => 15,
                'render_scale' => 1,
                'color' => '#4b5320',
                'sprite' => $this->proefpersoonFrame(0),
                'walk_frames' => [
                    $this->proefpersoonFrame(16),
                    $this->proefpersoonFrame(0),
                    $this->proefpersoonFrame(-16),
                    $this->proefpersoonFrame(0),
                ],
            ],
            [
                'code' => 'slijmmonster',
                'name' => 'Slijmmonster',
                'tagline' => 'Trage druip. Onmogelijk klein te krijgen.',
                'description' => 'Ontsnapt bio-experiment uit Lab 7, een levende gelatinemassa die zich door ventilatieroosters perst.',
                'hp' => 90,
                'speed_multiplier' => 0.75,
                'bounty' => 20,
                'render_scale' => 1.1,
                'color' => '#16a34a',
                'sprite' => $this->slijmmonsterFrame(0),
                'walk_frames' => [
                    $this->slijmmonsterFrame(0),
                    $this->slijmmonsterFrame(90),
                    $this->slijmmonsterFrame(180),
                    $this->slijmmonsterFrame(270),
                ],
            ],
            [
                'code' => 'boss-groene-kolos',
                'name' => 'Groene Kolos',
                'tagline' => 'De grond dreunt voor hij er is.',
                'description' => 'Het mislukte topexperiment van Lab 1 — een menselijk proefsubject dat de dosis overleefde en er alleen maar groter en woedender van werd.',
                'hp' => 400,
                'speed_multiplier' => 0.55,
                'bounty' => 100,
                'render_scale' => 2.2,
                'color' => '#16a34a',
                'sprite' => $this->bossFrame(0),
                'walk_frames' => [
                    $this->bossFrame(14),
                    $this->bossFrame(0),
                    $this->bossFrame(-14),
                    $this->bossFrame(0),
                ],
            ],
        ];

        foreach ($enemyTypes as $enemyType) {
            EnemyType::updateOrCreate(['code' => $enemyType['code']], $enemyType);
        }
    }

    private function svg(string $inner): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" shape-rendering="crispEdges">'.$inner.'</svg>';
    }

    /**
     * Walk-cycle frame for the alien. $legAngle rotates each leg around its
     * hip via an SVG <g> transform (front/back legs swing in opposite
     * directions, arms swing gently opposite the legs), so the same body
     * art produces a real 4-frame gait by calling this with 18, 0, -18, 0.
     */
    private function alienFrame(float $legAngle): string
    {
        $armAngle = -$legAngle * 0.5;

        return $this->svg(<<<SVG
            <ellipse cx="16" cy="29" rx="7" ry="2" fill="#000000" opacity="0.25"/>
            <g transform="rotate({$legAngle} 13.5 20)"><rect x="12" y="19" width="3" height="9" fill="#6b7280"/><rect x="11" y="27" width="5" height="2" fill="#4b5563"/></g>
            <g transform="rotate(-{$legAngle} 18.5 20)"><rect x="17" y="19" width="3" height="9" fill="#6b7280"/><rect x="16" y="27" width="5" height="2" fill="#4b5563"/></g>
            <g transform="rotate({$armAngle} 12 13)"><rect x="10.5" y="12" width="2" height="7" fill="#7d8a99"/></g>
            <g transform="rotate(-{$armAngle} 20 13)"><rect x="19.5" y="12" width="2" height="7" fill="#7d8a99"/></g>
            <ellipse cx="16" cy="15" rx="6" ry="7.5" fill="#8b96a3"/>
            <ellipse cx="13.5" cy="11.5" rx="2.2" ry="3" fill="#aab4c0" opacity="0.55"/>
            <circle cx="16" cy="18" r="2" fill="#22c55e" opacity="0.6"/>
            <ellipse cx="16" cy="6.5" rx="5.5" ry="5" fill="#9aa5b1"/>
            <ellipse cx="13.6" cy="6" rx="1.6" ry="2.1" fill="#0d0f12"/>
            <ellipse cx="18.4" cy="6" rx="1.6" ry="2.1" fill="#0d0f12"/>
            <ellipse cx="13.2" cy="5.3" rx="0.5" ry="0.6" fill="#e5f9ff"/>
            <ellipse cx="18" cy="5.3" rx="0.5" ry="0.6" fill="#e5f9ff"/>
            <ellipse cx="16" cy="17.5" rx="6" ry="2.2" fill="#000000" opacity="0.18"/>
            SVG);
    }

    /**
     * Walk-cycle frame for the Ontsnapte Proefpersoon: same hip-pivot leg
     * rotation as the alien, but bulkier armor plating, a gun-arm, and a
     * glowing red visor instead of alien eyes.
     */
    private function proefpersoonFrame(float $legAngle): string
    {
        $armAngle = -$legAngle * 0.4;

        return $this->svg(<<<SVG
            <ellipse cx="16" cy="29" rx="7.5" ry="2" fill="#000000" opacity="0.25"/>
            <g transform="rotate({$legAngle} 13.5 20)"><rect x="11.8" y="19" width="3.4" height="9" fill="#2b2f22"/><rect x="10.8" y="27" width="5.4" height="2.2" fill="#1a1c15"/></g>
            <g transform="rotate(-{$legAngle} 18.5 20)"><rect x="16.8" y="19" width="3.4" height="9" fill="#2b2f22"/><rect x="15.8" y="27" width="5.4" height="2.2" fill="#1a1c15"/></g>
            <ellipse cx="16" cy="15" rx="7.6" ry="8.6" fill="#33391f"/>
            <ellipse cx="16" cy="14.5" rx="7" ry="8" fill="#4b5320"/>
            <ellipse cx="13" cy="10.5" rx="2.6" ry="3.4" fill="#5c6b2a" opacity="0.55"/>
            <ellipse cx="16" cy="19" rx="6.6" ry="2" fill="#000000" opacity="0.2"/>
            <g transform="rotate({$armAngle} 22.4 13)"><rect x="21.2" y="12" width="2.6" height="8.4" fill="#2b2f22"/><rect x="20.6" y="19.6" width="4.2" height="2.4" fill="#1a1c15"/></g>
            <g transform="rotate(-{$armAngle} 9.6 13)"><rect x="8.4" y="12" width="2.6" height="8.4" fill="#2b2f22"/></g>
            <ellipse cx="16" cy="6.5" rx="5.2" ry="4.8" fill="#4b5320"/>
            <ellipse cx="16" cy="6.8" rx="4.2" ry="2.6" fill="#1a1c15"/>
            <ellipse cx="17.4" cy="6.6" rx="1.6" ry="1" fill="#ef4444"/>
            <ellipse cx="17.9" cy="6.4" rx="0.55" ry="0.35" fill="#fecaca"/>
            <path d="M12.5 9h3" stroke="#f97316" stroke-width="0.7" opacity="0.8"/>
            <ellipse cx="16" cy="17.5" rx="7" ry="2.4" fill="#000000" opacity="0.18"/>
            SVG);
    }

    /**
     * Loop frame for the Slijmmonster: no legs — instead the whole body
     * squashes/stretches (rx grows as ry shrinks and vice versa, driven by
     * a single angle parameter) for the classic gelatinous jiggle, with a
     * glossy highlight streak and drips for a genuinely "slimy" read.
     */
    private function slijmmonsterFrame(float $phaseDeg): string
    {
        $rad = deg2rad($phaseDeg);
        $s = sin($rad);
        $wob = 1 + 0.12 * $s;
        $squish = 1 - 0.10 * $s;
        $cy = 20 + (1 - $squish) * 4;

        $bodyLowRx = round(8 * $wob, 2);
        $bodyLowRy = round(6 * $squish, 2);
        $bodyMidRx = round(5.6 * $wob, 2);
        $bodyMidRy = round(5.4 * $squish, 2);
        $bodyTopRx = round(6.5 * $wob, 2);
        $bodyTopRy = round(4.4 * $squish, 2);
        $cyLow = round($cy + 3, 2);
        $cyMid = round($cy - 2, 2);
        $cyTop = round($cy + 2, 2);
        $cyEyes = round($cy, 2);
        $cyHighlight = round($cy - 3, 2);
        $cyHighlightDot = round($cy - 4, 2);
        $dripY = round($cy + 7, 2);

        return $this->svg(<<<SVG
            <ellipse cx="16" cy="27" rx="7.5" ry="1.8" fill="#000000" opacity="0.22"/>
            <ellipse cx="16" cy="{$cyLow}" rx="{$bodyLowRx}" ry="{$bodyLowRy}" fill="#15803d"/>
            <ellipse cx="16" cy="{$cyMid}" rx="{$bodyMidRx}" ry="{$bodyMidRy}" fill="#16a34a"/>
            <ellipse cx="16" cy="{$cyTop}" rx="{$bodyTopRx}" ry="{$bodyTopRy}" fill="#22c55e"/>
            <ellipse cx="13" cy="{$cyHighlight}" rx="2.6" ry="1.6" fill="#86efac" opacity="0.6"/>
            <ellipse cx="12.4" cy="{$cyHighlightDot}" rx="1" ry="0.5" fill="#f0fdf4" opacity="0.85"/>
            <circle cx="13" cy="{$cyEyes}" r="1.2" fill="#052e16"/>
            <circle cx="19" cy="{$cyEyes}" r="1.2" fill="#052e16"/>
            <circle cx="12.6" cy="{$cyEyes}" r="0.35" fill="#dcfce7"/>
            <circle cx="18.6" cy="{$cyEyes}" r="0.35" fill="#dcfce7"/>
            <path d="M 10.5 {$dripY} q 1 2 0 3.4" stroke="#166534" stroke-width="1.4" fill="none" opacity="0.7"/>
            <path d="M 21.5 {$dripY} q -1 2.4 0 4" stroke="#166534" stroke-width="1.2" fill="none" opacity="0.6"/>
            SVG);
    }

    /**
     * Walk-cycle frame for the boss, "Groene Kolos": same hip-pivot leg
     * rotation trick as every other creature, just on a bigger 40x40 canvas
     * with much broader proportions (wide chest/shoulders, bulging arms) and
     * glowing cracks that pulse with the stride. render_scale (2.2, set in
     * the seeder) is what actually makes it tower over normal enemies —
     * the bigger viewBox here is just extra room for its broader silhouette.
     */
    private function bossFrame(float $legAngle): string
    {
        $armAngle = -$legAngle * 0.3;
        $glow = round(0.5 + 0.3 * abs(sin(deg2rad($legAngle))), 2);
        $glowStroke = round($glow * 0.8, 2);

        $inner = <<<SVG
            <ellipse cx="20" cy="36" rx="12" ry="2.6" fill="#000000" opacity="0.3"/>
            <g transform="rotate({$legAngle} 16 27)"><rect x="14" y="25" width="4.6" height="11" fill="#14532d"/><rect x="13" y="35" width="6.6" height="2.6" fill="#052e16"/></g>
            <g transform="rotate(-{$legAngle} 24 27)"><rect x="21.4" y="25" width="4.6" height="11" fill="#14532d"/><rect x="20.4" y="35" width="6.6" height="2.6" fill="#052e16"/></g>
            <ellipse cx="20" cy="21" rx="11" ry="9.5" fill="#166534"/>
            <ellipse cx="20" cy="19" rx="9.6" ry="8" fill="#16a34a"/>
            <ellipse cx="16.5" cy="15.5" rx="4.2" ry="4.6" fill="#22c55e" opacity="0.5"/>
            <ellipse cx="20" cy="24" rx="6.5" ry="3.4" fill="#052e16" opacity="0.5"/>
            <circle cx="20" cy="21" r="2.2" fill="#4ade80" opacity="{$glow}"/>
            <path d="M16 24l2 2M24 24l-2 2M18 27l2 1.6M22 27l-2 1.6" stroke="#4ade80" stroke-width="0.6" opacity="{$glowStroke}"/>
            <g transform="rotate({$armAngle} 8.5 18)"><ellipse cx="8.5" cy="16" rx="3.6" ry="4.4" fill="#166534"/><ellipse cx="7.5" cy="23" rx="3.2" ry="3.6" fill="#14532d"/></g>
            <g transform="rotate(-{$armAngle} 31.5 18)"><ellipse cx="31.5" cy="16" rx="3.6" ry="4.4" fill="#166534"/><ellipse cx="32.5" cy="23" rx="3.2" ry="3.6" fill="#14532d"/></g>
            <ellipse cx="20" cy="9" rx="6" ry="5.4" fill="#166534"/>
            <ellipse cx="20" cy="8" rx="5" ry="4.2" fill="#16a34a"/>
            <path d="M15.5 6.5c1-1.4 2.6-2 4.5-2s3.5 0.6 4.5 2" stroke="#052e16" stroke-width="1" fill="none"/>
            <ellipse cx="17.6" cy="8.4" rx="1" ry="1.3" fill="#052e16"/>
            <ellipse cx="22.4" cy="8.4" rx="1" ry="1.3" fill="#052e16"/>
            <circle cx="17.6" cy="8.4" r="0.4" fill="#bbf7d0"/>
            <circle cx="22.4" cy="8.4" r="0.4" fill="#bbf7d0"/>
            <path d="M17 11.4c1 0.8 2 0.8 3 0" stroke="#052e16" stroke-width="0.8" fill="none"/>
            <ellipse cx="20" cy="22" rx="9" ry="3" fill="#000000" opacity="0.15"/>
            SVG;

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" shape-rendering="crispEdges">'.$inner.'</svg>';
    }
}
