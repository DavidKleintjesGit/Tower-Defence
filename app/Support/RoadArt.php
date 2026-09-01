<?php

namespace App\Support;

class RoadArt
{
    // Each skin generates a sprite for all 16 neighbor-presence combinations
    // (mask = "u d l r", 1/0 per direction), so straight/corner/T/cross/end/
    // isolated pieces all fall out of one arm-drawing routine per skin.
    private const ROAD_PALETTES = [
        'road' => ['surface' => '#3a3a40', 'edgeLight' => '#57575f', 'edgeDark' => '#26262a', 'stripe' => '#f2c94c', 'style' => 'paved', 'halfWidth' => 5, 'stripeWidth' => 4],
        'road-cracked' => ['surface' => '#33312c', 'edgeLight' => '#4a463d', 'edgeDark' => '#1c1a17', 'stripe' => '#8a7a5c', 'style' => 'paved', 'halfWidth' => 5, 'stripeWidth' => 4, 'cracked' => true],
        'road-wide' => ['surface' => '#3a3a40', 'edgeLight' => '#57575f', 'edgeDark' => '#26262a', 'stripe' => '#f2c94c', 'style' => 'paved', 'halfWidth' => 10, 'stripeWidth' => 6],
        'road-dirt' => ['surface' => '#b8925a', 'track' => '#8f6d3f', 'style' => 'dirt', 'halfWidth' => 5],
        // Indoor walkway — same gray as concreteFloorSprite()'s base so a
        // painted walking lane reads as "this exact floor", not asphalt.
        'corridor-path' => ['surface' => '#71717a', 'edgeLight' => '#a1a1aa', 'edgeDark' => '#52525b', 'stripe' => '#f2c94c', 'style' => 'paved', 'halfWidth' => 6, 'stripeWidth' => 3],
    ];

    private const FENCE_PALETTES = [
        'fence' => ['type' => 'mesh', 'post' => '#5b5f63', 'postLight' => '#787c80', 'rail' => '#6b6f73', 'mesh' => '#3f4246'],
        'fence-barbed' => ['type' => 'barbed', 'post' => '#5b5f63', 'postLight' => '#787c80', 'rail' => '#6b6f73', 'mesh' => '#3f4246', 'barb' => '#b45309'],
        'concrete-wall' => ['type' => 'wall', 'face' => '#9a9a9a', 'faceLight' => '#b8b8b8', 'faceDark' => '#6f6f6f', 'seam' => '#7f7f7f'],
        'lab-wall' => ['type' => 'wall', 'face' => '#e5e7eb', 'faceLight' => '#f9fafb', 'faceDark' => '#9ca3af', 'seam' => '#cbd5e1'],
        'steel-wall' => ['type' => 'wall', 'face' => '#6b7280', 'faceLight' => '#9ca3af', 'faceDark' => '#374151', 'seam' => '#4b5563'],
        'glass-wall' => ['type' => 'glass', 'frame' => '#374151', 'glass' => '#7dd3fc'],
    ];

    public static function roadAssets(string $code): array
    {
        $palette = self::ROAD_PALETTES[$code] ?? self::ROAD_PALETTES['road'];
        $assets = [];

        foreach (self::allMasks() as $mask) {
            $assets[$mask] = self::dataUri(self::roadJunction(self::armsFromMask($mask), $palette));
        }

        return $assets;
    }

    public static function fenceAssets(string $code): array
    {
        $palette = self::FENCE_PALETTES[$code] ?? self::FENCE_PALETTES['fence'];
        $assets = [];

        foreach (self::allMasks() as $mask) {
            $assets[$mask] = self::dataUri(self::fenceJunction(self::armsFromMask($mask), $palette));
        }

        return $assets;
    }

    /**
     * All 16 combinations of the 4 neighbor directions, encoded as a 4-char
     * "udlr" bitstring (e.g. "1010" = up+left present, down+right absent).
     * JS builds the same key straight from its neighbor booleans.
     */
    private static function allMasks(): array
    {
        return array_map(fn ($i) => sprintf('%04b', $i), range(0, 15));
    }

    private static function armsFromMask(string $mask): array
    {
        return [
            'u' => $mask[0] === '1',
            'd' => $mask[1] === '1',
            'l' => $mask[2] === '1',
            'r' => $mask[3] === '1',
        ];
    }

    private static function dataUri(string $svg): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    private static function svg(string $inner): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" shape-rendering="crispEdges">'.$inner.'</svg>';
    }

    private static function rect(float $x, float $y, float $w, float $h, string $fill): string
    {
        return '<rect x="'.$x.'" y="'.$y.'" width="'.$w.'" height="'.$h.'" fill="'.$fill.'"/>';
    }

    private static function rectOp(float $x, float $y, float $w, float $h, string $fill, float $opacity): string
    {
        return '<rect x="'.$x.'" y="'.$y.'" width="'.$w.'" height="'.$h.'" fill="'.$fill.'" opacity="'.$opacity.'"/>';
    }

    // Every arm is drawn as a straight band from the tile's outer edge all
    // the way to the tile CENTER (not to a separate inner "hub" rect) —
    // so opposite arms meet flush with no seam, giving a continuous
    // stripe/track through straight runs instead of a gap over the middle.
    //
    // A dead end (exactly 1 arm) or a fully isolated tile (0 arms) would
    // otherwise only fill half (or none) of the tile, leaving it looking
    // like the road stops short of the actual edge — e.g. at the border of
    // the map. So for fill/stripe purposes only, those two cases are
    // "mirrored" into a full straight tile in their axis (an isolated tile
    // defaults to horizontal); the real neighbor mask is unaffected, this
    // only changes what gets drawn.
    private static function roadJunction(array $arms, array $palette): string
    {
        $center = 16;
        $hw = $palette['halfWidth'] ?? 5;
        $surface = $palette['surface'];
        $style = $palette['style'] ?? 'paved';

        $vertCount = ($arms['u'] ? 1 : 0) + ($arms['d'] ? 1 : 0);
        $horizCount = ($arms['l'] ? 1 : 0) + ($arms['r'] ? 1 : 0);
        $totalCount = $vertCount + $horizCount;

        $fill = $arms;

        if ($totalCount === 0) {
            $fill = ['u' => false, 'd' => false, 'l' => true, 'r' => true];
        } elseif ($totalCount === 1) {
            $fill = $vertCount === 1
                ? ['u' => true, 'd' => true, 'l' => false, 'r' => false]
                : ['u' => false, 'd' => false, 'l' => true, 'r' => true];
        }

        // Edge highlight/shadow lines only read cleanly along an actual
        // straight through-line; on a corner/T/cross they'd be disconnected
        // fragments that don't meet at the joint, so skip them there.
        $throughVertical = $fill['u'] && $fill['d'];
        $throughHorizontal = $fill['l'] && $fill['r'];

        $parts = [];

        if ($fill['u']) {
            $parts[] = self::rect($center - $hw, 0, $hw * 2, $center, $surface);
        }

        if ($fill['d']) {
            $parts[] = self::rect($center - $hw, $center, $hw * 2, $center, $surface);
        }

        if ($fill['l']) {
            $parts[] = self::rect(0, $center - $hw, $center, $hw * 2, $surface);
        }

        if ($fill['r']) {
            $parts[] = self::rect($center, $center - $hw, $center, $hw * 2, $surface);
        }

        if ($style === 'paved') {
            $edgeLight = $palette['edgeLight'];
            $edgeDark = $palette['edgeDark'];
            $stripe = $palette['stripe'];
            $sw = $palette['stripeWidth'] ?? 4;
            $half = $sw / 2;

            if ($fill['u']) {
                if ($throughVertical) {
                    $parts[] = self::rect($center - $hw - 1, 0, 1, $center, $edgeLight);
                    $parts[] = self::rect($center + $hw, 0, 1, $center, $edgeDark);
                }
                $parts[] = self::rect($center - $half, 2, $sw, $center - 2, $stripe);
            }

            if ($fill['d']) {
                if ($throughVertical) {
                    $parts[] = self::rect($center - $hw - 1, $center, 1, $center, $edgeLight);
                    $parts[] = self::rect($center + $hw, $center, 1, $center, $edgeDark);
                }
                $parts[] = self::rect($center - $half, $center, $sw, $center - 2, $stripe);
            }

            if ($fill['l']) {
                if ($throughHorizontal) {
                    $parts[] = self::rect(0, $center - $hw - 1, $center, 1, $edgeLight);
                    $parts[] = self::rect(0, $center + $hw, $center, 1, $edgeDark);
                }
                $parts[] = self::rect(2, $center - $half, $center - 2, $sw, $stripe);
            }

            if ($fill['r']) {
                if ($throughHorizontal) {
                    $parts[] = self::rect($center, $center - $hw - 1, $center, 1, $edgeLight);
                    $parts[] = self::rect($center, $center + $hw, $center, 1, $edgeDark);
                }
                $parts[] = self::rect($center, $center - $half, $center - 2, $sw, $stripe);
            }

            if ($palette['cracked'] ?? false) {
                $parts[] = '<path d="M5 8 L13 14 L9 20 L19 17 L24 25" stroke="'.$edgeDark.'" stroke-width="1" fill="none" opacity="0.65"/>';
                $parts[] = '<path d="M17 5 L14 13" stroke="'.$edgeDark.'" stroke-width="1" fill="none" opacity="0.5"/>';
            }
        } elseif ($style === 'dirt') {
            $track = $palette['track'];
            $offset = max(2, $hw - 2);

            if ($fill['u']) {
                $parts[] = self::rect($center - $offset - 1, 0, 2, $center, $track);
                $parts[] = self::rect($center + $offset - 1, 0, 2, $center, $track);
            }

            if ($fill['d']) {
                $parts[] = self::rect($center - $offset - 1, $center, 2, $center, $track);
                $parts[] = self::rect($center + $offset - 1, $center, 2, $center, $track);
            }

            if ($fill['l']) {
                $parts[] = self::rect(0, $center - $offset - 1, $center, 2, $track);
                $parts[] = self::rect(0, $center + $offset - 1, $center, 2, $track);
            }

            if ($fill['r']) {
                $parts[] = self::rect($center, $center - $offset - 1, $center, 2, $track);
                $parts[] = self::rect($center, $center + $offset - 1, $center, 2, $track);
            }
        }

        return self::svg(implode('', $parts));
    }

    private static function fenceJunction(array $arms, array $palette): string
    {
        return match ($palette['type'] ?? 'mesh') {
            'wall' => self::wallJunction($arms, $palette),
            'glass' => self::glassJunction($arms, $palette),
            'barbed' => self::meshJunction($arms, $palette, true),
            default => self::meshJunction($arms, $palette, false),
        };
    }

    private static function meshJunction(array $arms, array $palette, bool $barbed): string
    {
        $post = $palette['post'];
        $postLight = $palette['postLight'];
        $rail = $palette['rail'];
        $mesh = $palette['mesh'];
        $parts = [
            self::rect(13, 12, 6, 17, $post),
            self::rect(14, 12, 1, 17, $postLight),
        ];

        if ($arms['u']) {
            $parts[] = self::rect(14, 0, 4, 15, $rail);
            $parts[] = self::rect(16, 3, 1, 9, $mesh);

            if ($barbed) {
                $parts[] = '<path d="M11 2 L21 2 M14 0 L18 4 M18 0 L14 4" stroke="'.$palette['barb'].'" stroke-width="1" fill="none"/>';
            }
        }

        if ($arms['d']) {
            $parts[] = self::rect(14, 17, 4, 15, $rail);
            $parts[] = self::rect(16, 20, 1, 9, $mesh);
        }

        if ($arms['l']) {
            $parts[] = self::rect(0, 14, 15, 4, $rail);
            $parts[] = self::rect(3, 16, 9, 1, $mesh);

            if ($barbed) {
                $parts[] = '<path d="M2 11 L2 21 M0 14 L4 18 M0 18 L4 14" stroke="'.$palette['barb'].'" stroke-width="1" fill="none"/>';
            }
        }

        if ($arms['r']) {
            $parts[] = self::rect(17, 14, 15, 4, $rail);
            $parts[] = self::rect(20, 16, 9, 1, $mesh);
        }

        return self::svg(implode('', $parts));
    }

    private static function wallJunction(array $arms, array $palette): string
    {
        $face = $palette['face'];
        $faceLight = $palette['faceLight'];
        $faceDark = $palette['faceDark'];
        $seam = $palette['seam'];
        $parts = [self::rect(10, 10, 12, 12, $face)];

        if ($arms['u']) {
            $parts[] = self::rect(10, 0, 12, 10, $face);
            $parts[] = self::rect(10, 0, 12, 2, $faceLight);
            $parts[] = self::rect(15, 0, 1, 10, $seam);
        }

        if ($arms['d']) {
            $parts[] = self::rect(10, 22, 12, 10, $face);
            $parts[] = self::rect(10, 30, 12, 2, $faceDark);
            $parts[] = self::rect(15, 22, 1, 10, $seam);
        }

        if ($arms['l']) {
            $parts[] = self::rect(0, 10, 10, 12, $face);
            $parts[] = self::rect(0, 10, 10, 2, $faceLight);
            $parts[] = self::rect(0, 15, 10, 1, $seam);
        }

        if ($arms['r']) {
            $parts[] = self::rect(22, 10, 10, 12, $face);
            $parts[] = self::rect(22, 10, 10, 2, $faceLight);
            $parts[] = self::rect(22, 15, 10, 1, $seam);
        }

        return self::svg(implode('', $parts));
    }

    // Same hub-to-edge geometry as wallJunction, but every segment gets a
    // translucent glass pane inset inside a dark frame instead of a solid
    // opaque face — for observation-room walls where you want to see through.
    private static function glassJunction(array $arms, array $palette): string
    {
        $frame = $palette['frame'];
        $glass = $palette['glass'];
        $parts = [
            self::rect(10, 10, 12, 12, $frame),
            self::rectOp(12, 12, 8, 8, $glass, 0.45),
        ];

        if ($arms['u']) {
            $parts[] = self::rect(10, 0, 12, 10, $frame);
            $parts[] = self::rectOp(12, 0, 8, 10, $glass, 0.45);
        }

        if ($arms['d']) {
            $parts[] = self::rect(10, 22, 12, 10, $frame);
            $parts[] = self::rectOp(12, 22, 8, 10, $glass, 0.45);
        }

        if ($arms['l']) {
            $parts[] = self::rect(0, 10, 10, 12, $frame);
            $parts[] = self::rectOp(0, 12, 10, 8, $glass, 0.45);
        }

        if ($arms['r']) {
            $parts[] = self::rect(22, 10, 10, 12, $frame);
            $parts[] = self::rectOp(22, 12, 10, 8, $glass, 0.45);
        }

        return self::svg(implode('', $parts));
    }
}
