<?php

namespace Database\Seeders;

use App\Models\EnemyType;
use Illuminate\Database\Seeder;

class EnemyTypeSeeder extends Seeder
{
    public function run(): void
    {
        EnemyType::whereNotIn('code', ['alien', 'proefpersoon', 'slijmmonster', 'boss-groene-kolos', 'rogue-sentry', 'boss-fusion-horror', 'boss-mothership-drone'])->delete();

        $enemyTypes = [
            [
                'code' => 'alien',
                'name' => 'Alien',
                'tagline' => 'Gone before you can shout "halt."',
                'description' => 'Escaped from a crashed saucer near Hangar 3. Gray skin, eyes far too big, and a green bio-glow that never fully fades.',
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
                'name' => 'Escaped Test Subject',
                'tagline' => 'Whatever they injected worked too well.',
                'description' => 'Former test subject from Lab 9, augmented with military cybernetics. Stronger and better armored than your average intruder.',
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
                'name' => 'Slime Monster',
                'tagline' => 'Slow drip. Impossible to wear down.',
                'description' => 'Escaped bio-experiment from Lab 7, a living mass of gelatin that squeezes through ventilation grates.',
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
                'name' => 'Green Colossus',
                'tagline' => 'The ground shakes before he arrives.',
                'description' => 'Lab 1\'s failed flagship experiment — a human test subject who survived the dose and only grew bigger and angrier because of it.',
                'is_boss' => true,
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
            [
                'code' => 'rogue-sentry',
                'name' => 'Rogue Sentry',
                'tagline' => 'Its orders expired. It didn\'t.',
                'description' => 'A base-security drone that stopped taking commands from anyone but itself. Still patrols like it means it.',
                'hp' => 65,
                'speed_multiplier' => 0.95,
                'bounty' => 16,
                'render_scale' => 1,
                'color' => '#4b5563',
                'sprite' => $this->rogueSentryFrame(0),
                'walk_frames' => [
                    $this->rogueSentryFrame(10),
                    $this->rogueSentryFrame(0),
                    $this->rogueSentryFrame(-10),
                    $this->rogueSentryFrame(0),
                ],
            ],
            [
                'code' => 'boss-fusion-horror',
                'name' => 'Fusion Horror',
                'tagline' => 'Three subjects went in. One thing came out.',
                'description' => 'Lab 1\'s other failed experiment — several test subjects fused into a single furious mass, memories included.',
                'is_boss' => true,
                'hp' => 350,
                'speed_multiplier' => 0.5,
                'bounty' => 90,
                'render_scale' => 2,
                'color' => '#7c2d5e',
                'sprite' => $this->fusionHorrorFrame(0),
                'walk_frames' => [
                    $this->fusionHorrorFrame(14),
                    $this->fusionHorrorFrame(0),
                    $this->fusionHorrorFrame(-14),
                    $this->fusionHorrorFrame(0),
                ],
            ],
            [
                'code' => 'boss-mothership-drone',
                'name' => 'Mothership Drone',
                'tagline' => 'It didn\'t come alone, and it isn\'t leaving that way either.',
                'description' => 'A recovered saucer core, reactivated and airborne again. Every few seconds it beams down another passenger.',
                'is_boss' => true,
                'domain' => 'air',
                'spawns_code' => 'alien',
                'spawn_interval' => 7,
                'hp' => 300,
                'speed_multiplier' => 0.5,
                'bounty' => 120,
                'render_scale' => 2,
                'color' => '#6d28d9',
                'sprite' => $this->mothershipDroneFrame(0),
                'walk_frames' => [
                    $this->mothershipDroneFrame(0),
                    $this->mothershipDroneFrame(90),
                    $this->mothershipDroneFrame(180),
                    $this->mothershipDroneFrame(270),
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

    /**
     * Walk-cycle frame for the Rogue Sentry: a boxy security-robot torso on
     * two mechanical legs (same hip-pivot rotation trick, just rectangular
     * "servo" legs instead of organic ones), with a single red optical
     * sensor and a spark that flickers brighter mid-stride.
     */
    private function rogueSentryFrame(float $legAngle): string
    {
        $spark = abs($legAngle) > 5 ? 1 : 0.3;

        return $this->svg(<<<SVG
            <ellipse cx="16" cy="28" rx="7" ry="2" fill="#000000" opacity="0.25"/>
            <g transform="rotate({$legAngle} 13 22)"><rect x="11.5" y="21" width="3" height="7" fill="#4b5563"/><rect x="10.5" y="27" width="5" height="1.6" fill="#374151"/></g>
            <g transform="rotate(-{$legAngle} 19 22)"><rect x="17.5" y="21" width="3" height="7" fill="#4b5563"/><rect x="16.5" y="27" width="5" height="1.6" fill="#374151"/></g>
            <rect x="10" y="10" width="12" height="12" rx="1" fill="#4b5563"/>
            <rect x="10" y="10" width="12" height="3" fill="#6b7280"/>
            <rect x="10" y="10" width="12" height="12" rx="1" fill="none" stroke="#1f2937" stroke-width="0.6"/>
            <circle cx="16" cy="16" r="2.4" fill="#111827"/>
            <circle cx="16" cy="16" r="1.3" fill="#ef4444" opacity="0.9"/>
            <rect x="12" y="19" width="3" height="1.2" fill="#1f2937"/>
            <polygon points="19,18 21,19 19.5,19.6 20.5,20.6" fill="#fbbf24" opacity="{$spark}"/>
            <ellipse cx="16" cy="20.5" rx="6.5" ry="2.2" fill="#000000" opacity="0.18"/>
            SVG);
    }

    /**
     * Walk-cycle frame for the Fusion Horror boss: an asymmetric fused mass
     * on a bigger 40x40 canvas (same convention as the Groene Kolos) —
     * mismatched arm sizes and multiple glowing eyes at different scales
     * sell the "several subjects merged together" idea, in a completely
     * different palette (dark red-purple) from the green colossus.
     */
    private function fusionHorrorFrame(float $legAngle): string
    {
        $glow = round(0.5 + 0.3 * abs(sin(deg2rad($legAngle))), 2);
        $armAngle = round($legAngle * 0.6, 2);

        $inner = <<<SVG
            <ellipse cx="20" cy="36" rx="12" ry="2.6" fill="#000000" opacity="0.3"/>
            <g transform="rotate({$legAngle} 15 27)"><rect x="13" y="25" width="4.4" height="10" fill="#4a1030"/><rect x="12" y="34" width="6.4" height="2.4" fill="#2e0a1e"/></g>
            <g transform="rotate(-{$legAngle} 25 27)"><rect x="22.6" y="25" width="4.4" height="10" fill="#5b1a3d"/><rect x="21.6" y="34" width="6.4" height="2.4" fill="#2e0a1e"/></g>
            <ellipse cx="19" cy="21" rx="12" ry="9" fill="#5b1a3d"/>
            <ellipse cx="22" cy="20" rx="8" ry="7" fill="#7c2d5e"/>
            <path d="M14 23l3 2M26 22l-2.5 2.5M18 27l2 1.6" stroke="#f97316" stroke-width="0.6" opacity="{$glow}"/>
            <g transform="rotate({$armAngle} 7 20)"><ellipse cx="7" cy="18" rx="3.2" ry="4.6" fill="#4a1030"/><ellipse cx="6" cy="25" rx="2.8" ry="3.4" fill="#2e0a1e"/></g>
            <g transform="rotate(-{$armAngle} 33 19)"><ellipse cx="33" cy="17" rx="3" ry="4.2" fill="#5b1a3d"/><ellipse cx="34" cy="24" rx="2.6" ry="3.2" fill="#2e0a1e"/></g>
            <ellipse cx="14" cy="13" rx="3.6" ry="3.2" fill="#5b1a3d"/>
            <ellipse cx="13.2" cy="12.6" rx="0.9" ry="1.1" fill="#a3e635"/>
            <ellipse cx="25" cy="10" rx="5.2" ry="4.6" fill="#7c2d5e"/>
            <ellipse cx="23.4" cy="9.6" rx="1.1" ry="1.4" fill="#a3e635"/>
            <ellipse cx="27" cy="9.6" rx="1.1" ry="1.4" fill="#a3e635"/>
            <circle cx="23.4" cy="9.6" r="0.35" fill="#f7fee7"/>
            <circle cx="27" cy="9.6" r="0.35" fill="#f7fee7"/>
            <ellipse cx="20" cy="22" rx="9" ry="3" fill="#000000" opacity="0.2"/>
            SVG;

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" shape-rendering="crispEdges">'.$inner.'</svg>';
    }

    /**
     * Hover-loop frame for the Mothership Drone boss: no legs — instead the
     * whole saucer bobs vertically (same idea as a small flying enemy would,
     * just much bigger), with a pulsing green underbelly core and glowing
     * cockpit dome. This is the enemy the spawner mechanic (spawns_code
     * 'alien' every spawn_interval seconds, see EnemyTypeSeeder/run()) is
     * attached to, and the first 'air' domain enemy in the roster.
     */
    private function mothershipDroneFrame(float $phaseDeg): string
    {
        $rad = deg2rad($phaseDeg);
        $bob = round(sin($rad) * 1.2, 2);
        $glow = round(0.5 + 0.3 * abs(sin($rad)), 2);
        $cy = round(18 + $bob, 2);
        $cyGlow = round($cy + 12, 2);
        $cyRimLow = round($cy + 4.5, 2);
        $cyRimHigh = round($cy + 3, 2);
        $cyDomeLow = round($cy - 2, 2);
        $cyDome = round($cy - 3.5, 2);

        $inner = <<<SVG
            <ellipse cx="20" cy="34" rx="11" ry="2.4" fill="#000000" opacity="0.3"/>
            <ellipse cx="20" cy="{$cyGlow}" rx="7" ry="2.2" fill="#4ade80" opacity="{$glow}"/>
            <ellipse cx="20" cy="{$cyRimLow}" rx="16" ry="4.4" fill="#2e1065"/>
            <ellipse cx="20" cy="{$cyRimHigh}" rx="15" ry="4" fill="#4c1d95"/>
            <ellipse cx="20" cy="{$cy}" rx="10" ry="5.5" fill="#6d28d9"/>
            <ellipse cx="16" cy="{$cyDomeLow}" rx="4" ry="2.6" fill="#a78bfa" opacity="0.5"/>
            <ellipse cx="20" cy="{$cyDome}" rx="6" ry="5" fill="#1e0838"/>
            <circle cx="20" cy="{$cyDome}" r="2.6" fill="#4ade80" opacity="{$glow}"/>
            <circle cx="10" cy="{$cyRimLow}" r="0.7" fill="#f97316" opacity="0.8"/>
            <circle cx="30" cy="{$cyRimLow}" r="0.7" fill="#f97316" opacity="0.8"/>
            SVG;

        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" shape-rendering="crispEdges">'.$inner.'</svg>';
    }
}
