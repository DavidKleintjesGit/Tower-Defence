<?php

namespace Database\Seeders;

use App\Models\TowerType;
use Illuminate\Database\Seeder;

class TowerTypeSeeder extends Seeder
{
    public function run(): void
    {
        TowerType::whereNotIn('code', ['machine-gun', 'raketwerper', 'tesla-arcstation', 'frost-cannon', 'laser'])->delete();

        $towerTypes = [
            [
                'code' => 'machine-gun',
                'name' => 'Machine Gun Nest',
                'tagline' => 'Rattles on until the enemy falls.',
                'description' => 'Converted security weapon from the old Area 51 depot. Rotates freely and keeps any target within range under fire.',
                'damage' => 6,
                'range_tiles' => 2.6,
                'fire_interval' => 0.18,
                'splash_damage' => false,
                'multi_target' => false,
                'targets_ground' => true,
                'targets_air' => true,
                'cost' => 60,
                'render_scale' => 1.05,
                'color' => '#6b7178',
                'sprite' => $this->svg($this->machineGunBaseInner().$this->machineGunHeadInner()),
                'base_sprite' => $this->svg($this->machineGunBaseInner()),
                'head_sprite' => $this->svg($this->machineGunHeadInner()),
                'muzzle_flash_sprite' => $this->svg($this->machineGunMuzzleFlashInner()),
                'projectile_sprite' => $this->machineGunProjectileSprite(),
                'projectile_style' => 'sprite',
            ],
            [
                'code' => 'raketwerper',
                'name' => 'Rocket Launcher',
                'tagline' => 'One bang. No more questions.',
                'description' => 'Heavy ordnance from the old munitions depot. Slow to reload, but whatever it hits doesn\'t get back up.',
                'damage' => 45,
                'range_tiles' => 4.8,
                'fire_interval' => 1.8,
                'splash_damage' => true,
                'multi_target' => false,
                'targets_ground' => true,
                'targets_air' => true,
                'cost' => 140,
                'render_scale' => 1.4,
                'color' => '#4d4d30',
                'sprite' => $this->svg($this->rocketBaseInner().$this->rocketHeadInner()),
                'base_sprite' => $this->svg($this->rocketBaseInner()),
                'head_sprite' => $this->svg($this->rocketHeadInner()),
                'muzzle_flash_sprite' => $this->svg($this->rocketMuzzleFlashInner()),
                'projectile_sprite' => $this->rocketProjectileSprite(),
                'projectile_style' => 'sprite',
            ],
            [
                'code' => 'tesla-arcstation',
                'name' => 'Tesla Arc Station',
                'tagline' => 'Quick to spark. Quicker to strike again.',
                'description' => 'Experimental power tower from Lab 2. Short range, but a fire rate that makes the air crackle.',
                'damage' => 5,
                'range_tiles' => 1.7,
                'fire_interval' => 0.12,
                'splash_damage' => false,
                'multi_target' => true,
                'targets_ground' => true,
                'targets_air' => true,
                'cost' => 90,
                'render_scale' => 1,
                'color' => '#7c3aed',
                'sprite' => $this->svg($this->teslaBaseInner().$this->teslaHeadInner()),
                'base_sprite' => $this->svg($this->teslaBaseInner()),
                'head_sprite' => $this->svg($this->teslaHeadInner()),
                'muzzle_flash_sprite' => $this->svg($this->teslaMuzzleFlashInner()),
                'projectile_sprite' => $this->teslaProjectileSprite(),
                'projectile_style' => 'bolt',
            ],
            [
                'code' => 'frost-cannon',
                'name' => 'Frost Cannon',
                'tagline' => 'Cold enough to argue with.',
                'description' => 'Cryogenic containment cannon repurposed from Lab 4. Every shot leaves the air brittle and white.',
                'damage' => 16,
                'range_tiles' => 2.8,
                'fire_interval' => 0.9,
                'splash_damage' => false,
                'multi_target' => false,
                'targets_ground' => true,
                'targets_air' => true,
                'cost' => 100,
                'render_scale' => 1.05,
                'color' => '#0e7490',
                'sprite' => $this->svg($this->frostBaseInner().$this->frostHeadInner()),
                'base_sprite' => $this->svg($this->frostBaseInner()),
                'head_sprite' => $this->svg($this->frostHeadInner()),
                'muzzle_flash_sprite' => $this->svg($this->frostMuzzleFlashInner()),
                'projectile_sprite' => $this->frostProjectileSprite(),
                'projectile_style' => 'sprite',
            ],
            [
                'code' => 'laser',
                'name' => 'Laser Emitter',
                'tagline' => 'Never stops. Never has to.',
                'description' => 'Continuous-beam optics from the Lab 2 annex. Locks on and simply keeps cutting for as long as anything stays in range.',
                'damage' => 14,
                'range_tiles' => 2.2,
                'fire_interval' => 0.1,
                'splash_damage' => false,
                'multi_target' => false,
                'targets_ground' => true,
                'targets_air' => true,
                'cost' => 110,
                'render_scale' => 1,
                'color' => '#22d3ee',
                'sprite' => $this->svg($this->laserBaseInner().$this->laserHeadInner()),
                'base_sprite' => $this->svg($this->laserBaseInner()),
                'head_sprite' => $this->svg($this->laserHeadInner()),
                'muzzle_flash_sprite' => $this->svg($this->laserGlowInner()),
                'projectile_sprite' => $this->laserProjectileSprite(),
                'projectile_style' => 'beam',
            ],
        ];

        foreach ($towerTypes as $towerType) {
            TowerType::updateOrCreate(['code' => $towerType['code']], $towerType);
        }
    }

    private function svg(string $inner): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" shape-rendering="crispEdges">'.$inner.'</svg>';
    }

    /**
     * The static, non-rotating part of the turret: ground shadow, an
     * isometric-shaded box (front face darker than its top face, so it
     * reads as a 3D block rather than a flat top-down square) plus a
     * vertically squashed dome (ellipses instead of circles, as if the
     * dome were viewed from an elevated tilted camera).
     */
    private function machineGunBaseInner(): string
    {
        return <<<'SVG'
            <ellipse cx="16" cy="27" rx="10" ry="3" fill="#000000" opacity="0.25"/>
            <polygon points="9,20 23,20 21.5,25 10.5,25" fill="#33353a"/>
            <polygon points="9,20 23,20 22,21 10,21" fill="#474b52"/>
            <ellipse cx="16" cy="19.3" rx="7.2" ry="3.1" fill="#52565d"/>
            <polygon points="4.3,20.2 7.6,20.2 7.1,21.6 3.9,21.6" fill="#7a6530"/>
            <polygon points="3.9,21.6 7.1,21.6 7.1,24.3 3.9,24.3" fill="#5a4a1f"/>
            <ellipse cx="16" cy="20" rx="6.3" ry="3.5" fill="#2f3236"/>
            <ellipse cx="16" cy="20" rx="4.5" ry="2.5" fill="#454951"/>
            <ellipse cx="16" cy="20" rx="2.2" ry="1.2" fill="#6b7178" opacity="0.7"/>
            <ellipse cx="14.3" cy="19" rx="1" ry="0.55" fill="#9aa0a8" opacity="0.5"/>
            <path d="M 10.8 19.4 Q 16 17 21.2 19.4" stroke="#9aa0a8" stroke-width="0.4" fill="none" opacity="0.4"/>
            SVG;
    }

    /**
     * The rotating twin-barrel head, drawn canonically pointing east
     * (angle 0) around pivot (16, 20) so it lines up with the base's dome
     * and with the game's atan2-based aim angle (0 = east) with no extra
     * conversion needed. The game applies rotate(angle) + a fixed
     * vertical scale(1, K) around this same pivot every frame, so one
     * sprite covers a full 360° aim with real perspective foreshortening
     * (shorter/rounder pointed at the camera, longer/thinner pointed away)
     * instead of needing a separate frame per direction.
     */
    private function machineGunHeadInner(): string
    {
        return <<<'SVG'
            <rect x="16" y="17.9" width="10.5" height="1.4" fill="#26282b"/>
            <rect x="16" y="17.9" width="10.5" height="0.5" fill="#5a5e63"/>
            <rect x="16" y="20.7" width="10.5" height="1.4" fill="#26282b"/>
            <rect x="16" y="20.7" width="10.5" height="0.5" fill="#5a5e63"/>
            <rect x="15.2" y="17.6" width="2" height="5" fill="#1f2123"/>
            <ellipse cx="26.4" cy="18.6" rx="0.75" ry="0.75" fill="#0d0e10"/>
            <ellipse cx="26.4" cy="21.4" rx="0.75" ry="0.75" fill="#0d0e10"/>
            SVG;
    }

    /**
     * Muzzle burst overlay, same canvas/pivot as the head sprite so it
     * rotates identically — the game draws this on top of the head only
     * for the ~0.12s after a shot fires.
     */
    private function machineGunMuzzleFlashInner(): string
    {
        return <<<'SVG'
            <circle cx="27.5" cy="20" r="3.2" fill="#fff7cc" opacity="0.9"/>
            <circle cx="27.5" cy="20" r="1.6" fill="#ffffff"/>
            <polygon points="31,20 27.5,18.4 27,20 27.5,21.6" fill="#ffe066" opacity="0.85"/>
            <polygon points="27.5,15 26,20 28.5,20" fill="#ffe066" opacity="0.7"/>
            <polygon points="27.5,25 26,20 28.5,20" fill="#ffe066" opacity="0.7"/>
            SVG;
    }

    /**
     * The tracer round fired at the target, drawn pointing east; the game
     * rotates it to match its actual travel direction each shot.
     */
    private function machineGunProjectileSprite(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 8" shape-rendering="crispEdges">'
            .'<rect x="0" y="3" width="10" height="2" fill="#ffcf4d" opacity="0.5"/>'
            .'<rect x="5" y="3" width="7" height="2" fill="#fff3b0"/>'
            .'<circle cx="12" cy="4" r="1.6" fill="#ffffff"/>'
            .'<circle cx="12" cy="4" r="0.8" fill="#fff7cc"/>'
            .'</svg>';
    }

    /**
     * Static launcher mount — a flat swivel plate (no dome; a rocket rack
     * doesn't need one) plus an ammo crate on the side. render_scale is set
     * high on this tower (1.4) so it visibly dwarfs the other turrets, per
     * "mag bijna een hele tegel groot worden".
     */
    private function rocketBaseInner(): string
    {
        return <<<'SVG'
            <ellipse cx="16" cy="27.5" rx="11" ry="3.2" fill="#000000" opacity="0.28"/>
            <polygon points="6,20 26,20 24,26.5 8,26.5" fill="#33331f"/>
            <polygon points="6,20 26,20 24.6,21.4 7.4,21.4" fill="#4d4d30"/>
            <ellipse cx="16" cy="19.5" rx="8.6" ry="3" fill="#5c5c3a"/>
            <ellipse cx="16" cy="20" rx="6.4" ry="2.4" fill="#6b6b45"/>
            <ellipse cx="16" cy="20" rx="3.2" ry="1.3" fill="#82824f" opacity="0.7"/>
            <polygon points="4,21 8,21 7.4,23 4.6,23" fill="#7a6530"/>
            <polygon points="4.6,23 7.4,23 7.4,26 4.6,26" fill="#5a4a1f"/>
            SVG;
    }

    /**
     * Rotating twin-tube launcher, canonically east-pointing around the
     * same (16, 20) pivot as every other tower head.
     */
    private function rocketHeadInner(): string
    {
        return <<<'SVG'
            <rect x="16" y="16.5" width="11" height="4" fill="#2a2a1c"/>
            <rect x="16" y="16.5" width="11" height="1.3" fill="#5a5a3c" opacity="0.8"/>
            <rect x="16" y="21.5" width="11" height="4" fill="#2a2a1c"/>
            <rect x="16" y="21.5" width="11" height="1.3" fill="#5a5a3c" opacity="0.8"/>
            <rect x="15" y="19.5" width="3" height="7" fill="#1c1c12"/>
            <circle cx="26.5" cy="18.5" r="1" fill="#7f1d1d"/>
            <circle cx="26.5" cy="23.5" r="1" fill="#7f1d1d"/>
            SVG;
    }

    private function rocketMuzzleFlashInner(): string
    {
        return <<<'SVG'
            <circle cx="27" cy="18.5" r="3.4" fill="#fed7aa" opacity="0.9"/>
            <circle cx="27" cy="23.5" r="3.4" fill="#fed7aa" opacity="0.9"/>
            <circle cx="27" cy="18.5" r="1.6" fill="#fff7ed"/>
            <circle cx="27" cy="23.5" r="1.6" fill="#fff7ed"/>
            <circle cx="27.5" cy="18.5" r="4.4" fill="#9ca3af" opacity="0.25"/>
            <circle cx="27.5" cy="23.5" r="4.4" fill="#9ca3af" opacity="0.25"/>
            SVG;
    }

    private function rocketProjectileSprite(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 10" shape-rendering="crispEdges">'
            .'<circle cx="2" cy="5" r="2.6" fill="#fed7aa" opacity="0.7"/>'
            .'<circle cx="0.5" cy="5" r="1.4" fill="#fff7ed"/>'
            .'<polygon points="0,2 4,0.5 4,3 1,3.6" fill="#2a2a1c"/>'
            .'<polygon points="0,8 4,9.5 4,7 1,6.4" fill="#2a2a1c"/>'
            .'<rect x="3" y="3" width="14" height="4" fill="#4d4d30"/>'
            .'<rect x="3" y="3" width="14" height="1.2" fill="#6b6b45"/>'
            .'<polygon points="17,2.4 22,5 17,7.6" fill="#7f1d1d"/>'
            .'</svg>';
    }

    /**
     * Static base — same iso-box + squashed-dome trick as the machine gun,
     * just re-tinted violet for the tesla's electric theme.
     */
    private function teslaBaseInner(): string
    {
        return <<<'SVG'
            <ellipse cx="16" cy="27" rx="9" ry="2.6" fill="#000000" opacity="0.25"/>
            <polygon points="10,20 22,20 21,25 11,25" fill="#2e1065"/>
            <polygon points="10,20 22,20 21.2,20.9 10.8,20.9" fill="#4c1d95"/>
            <ellipse cx="16" cy="19.3" rx="6.6" ry="2.9" fill="#5b21b6"/>
            <ellipse cx="16" cy="20" rx="5.6" ry="3.1" fill="#6d28d9"/>
            <ellipse cx="16" cy="20" rx="3.6" ry="2" fill="#7c3aed"/>
            <ellipse cx="16" cy="20" rx="1.6" ry="0.9" fill="#c4b5fd" opacity="0.7"/>
            SVG;
    }

    /**
     * Rotating coil rod, canonical east-pointing around pivot (16, 20).
     */
    private function teslaHeadInner(): string
    {
        return <<<'SVG'
            <rect x="16" y="18.7" width="10" height="2.6" fill="#4c1d95"/>
            <rect x="18.4" y="17" width="1.4" height="6" fill="#7c3aed"/>
            <rect x="22.4" y="17" width="1.4" height="6" fill="#7c3aed"/>
            <circle cx="26.5" cy="20" r="2.4" fill="#c4b5fd"/>
            <circle cx="26.5" cy="20" r="1.1" fill="#f5f3ff"/>
            SVG;
    }

    private function teslaMuzzleFlashInner(): string
    {
        return <<<'SVG'
            <circle cx="26.5" cy="20" r="3.6" fill="#e9d5ff" opacity="0.9"/>
            <circle cx="26.5" cy="20" r="1.8" fill="#ffffff"/>
            SVG;
    }

    /**
     * Only used as a fallback icon (Armory/drag-preview) — the real fire
     * effect is a procedurally jittered bolt drawn straight in JS (see
     * projectile_style 'bolt' in game/index.js), regenerated every frame it
     * lives so it crackles instead of looking like a fixed static zap.
     */
    private function teslaProjectileSprite(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" shape-rendering="crispEdges">'
            .'<path d="M9 1 L4 9 L7 9 L5 15 L12 6 L8 6 Z" fill="#c4b5fd"/>'
            .'<path d="M9 1 L4 9 L7 9 L5 15 L12 6 L8 6 Z" fill="none" stroke="#f5f3ff" stroke-width="0.6"/>'
            .'</svg>';
    }

    /**
     * Static base — same iso-box + squashed-dome trick, re-tinted icy blue,
     * with a pair of icicle spikes jutting off the front rim for flavor.
     */
    private function frostBaseInner(): string
    {
        return <<<'SVG'
            <ellipse cx="16" cy="27" rx="10" ry="3" fill="#000000" opacity="0.25"/>
            <polygon points="9,20 23,20 21.5,25 10.5,25" fill="#0c4a6e"/>
            <polygon points="9,20 23,20 22,21 10,21" fill="#155e75"/>
            <ellipse cx="16" cy="19.3" rx="7.2" ry="3.1" fill="#0e7490"/>
            <polygon points="9.5,20 11,17 11.8,20" fill="#cffafe" opacity="0.85"/>
            <polygon points="21,20 22.2,17.4 22.8,20" fill="#cffafe" opacity="0.85"/>
            <ellipse cx="16" cy="20" rx="6.3" ry="3.5" fill="#0891b2"/>
            <ellipse cx="16" cy="20" rx="4.5" ry="2.5" fill="#22d3ee"/>
            <ellipse cx="16" cy="20" rx="2.2" ry="1.2" fill="#e0f2fe" opacity="0.8"/>
            SVG;
    }

    /**
     * Rotating barrel, canonical east-pointing around pivot (16, 20), with a
     * cluster of ice-crystal shards permanently frozen onto the muzzle.
     */
    private function frostHeadInner(): string
    {
        return <<<'SVG'
            <rect x="16" y="18.3" width="10" height="3.4" fill="#0c4a6e"/>
            <rect x="16" y="18.3" width="10" height="1" fill="#22d3ee"/>
            <polygon points="25,18 27.5,19.2 25.5,19.6" fill="#e0f2fe" opacity="0.9"/>
            <polygon points="25,22 27.2,20.8 25.5,20.4" fill="#e0f2fe" opacity="0.9"/>
            <polygon points="26,19 28.5,20 26,21" fill="#bae6fd" opacity="0.8"/>
            SVG;
    }

    private function frostMuzzleFlashInner(): string
    {
        return <<<'SVG'
            <circle cx="28" cy="20" r="2.8" fill="#e0f2fe" opacity="0.9"/>
            <circle cx="28" cy="20" r="1.3" fill="#ffffff"/>
            <path d="M28 16.5v2.2M28 21.3v2.2M24.5 20h2.2M29.3 20h2.2" stroke="#cffafe" stroke-width="0.6" opacity="0.8"/>
            SVG;
    }

    private function frostProjectileSprite(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 8" shape-rendering="crispEdges">'
            .'<polygon points="0,3.2 10,3.2 14,4 10,4.8 0,4.8" fill="#67e8f9" opacity="0.7"/>'
            .'<polygon points="4,3.6 12,3.6 15,4 12,4.4 4,4.4" fill="#e0f2fe"/>'
            .'</svg>';
    }

    /**
     * Static base — dark charcoal housing with a glowing cyan energy core,
     * sleeker than the other mounts to read as precision optics rather than
     * a gun.
     */
    private function laserBaseInner(): string
    {
        return <<<'SVG'
            <ellipse cx="16" cy="27" rx="9" ry="2.6" fill="#000000" opacity="0.25"/>
            <polygon points="10,20 22,20 21,25 11,25" fill="#0f172a"/>
            <polygon points="10,20 22,20 21.2,20.9 10.8,20.9" fill="#1e293b"/>
            <ellipse cx="16" cy="19.3" rx="6.6" ry="2.9" fill="#334155"/>
            <ellipse cx="16" cy="20" rx="5.6" ry="3.1" fill="#0e7490"/>
            <ellipse cx="16" cy="20" rx="3.6" ry="2" fill="#22d3ee"/>
            <ellipse cx="16" cy="20" rx="1.6" ry="0.9" fill="#a5f3fc" opacity="0.8"/>
            SVG;
    }

    /**
     * Rotating emitter, canonical east-pointing around pivot (16, 20) — a
     * slim housing ending in a lens, in contrast to the other towers' gun
     * barrels.
     */
    private function laserHeadInner(): string
    {
        return <<<'SVG'
            <rect x="16" y="19" width="9" height="2" fill="#1e293b"/>
            <rect x="16" y="19.3" width="9" height="0.5" fill="#22d3ee" opacity="0.9"/>
            <circle cx="26" cy="20" r="1.8" fill="#083344"/>
            <circle cx="26" cy="20" r="1" fill="#67e8f9"/>
            SVG;
    }

    /**
     * Emitter glow — the game keeps this drawn for as long as the beam is
     * actively firing (see projectile_style 'beam' handling in
     * game/index.js), not just a brief flash, since there's no discrete
     * shot moment to flash for.
     */
    private function laserGlowInner(): string
    {
        return <<<'SVG'
            <circle cx="26" cy="20" r="3" fill="#a5f3fc" opacity="0.85"/>
            <circle cx="26" cy="20" r="1.4" fill="#ffffff"/>
            SVG;
    }

    /**
     * Fallback icon only (Armory card, drag preview) — the actual weapon
     * effect is a continuous line drawn straight in JS, not a travelling
     * projectile, so this sprite is never spawned in play.
     */
    private function laserProjectileSprite(): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 8" shape-rendering="crispEdges">'
            .'<rect x="0" y="3.4" width="16" height="1.2" fill="#22d3ee"/>'
            .'<rect x="0" y="3.7" width="16" height="0.6" fill="#f0fdfa"/>'
            .'</svg>';
    }
}
