# Area 51 Tower Defense

A browser-based tower defense game with an Area 51 theme, built with Laravel + Livewire for everything server-driven (admin tooling, menus, page structure) and a vanilla JS canvas engine for the actual game loop.

## Stack

- **Laravel 12** — application framework, routing, models/migrations.
- **Livewire 3** — every page (menu, campaign map, admin screens, the game shell itself) is a Livewire full-page component. No SPA framework, no API layer between server and browser — Livewire re-renders Blade server-side and `wire:navigate` gives it SPA-style transitions between pages.
- **Alpine.js** (bundled with Livewire) — small client-side interactivity inside Blade views: flip cards in the Armory/Bestiary, toggle switches in Settings, keyboard nav on the main menu.
- **Tailwind CSS v4** + **Vite** — styling and asset bundling.
- **Vanilla JS canvas engine** (`resources/js/game/index.js`) — the actual tower-defense simulation (game loop, rendering, input) is hand-rolled `<canvas>` 2D, not a game framework. Livewire hands it a JSON blob of map/tower/enemy data on page load; from there it's a self-contained `requestAnimationFrame` loop until the player navigates away.
- **MySQL** via Laravel Herd locally.

Why this split: Livewire is a good fit for anything server-authoritative and form-heavy (admin CRUD, menus, map metadata) with zero API boilerplate. A 60fps game loop with continuous mouse/pan/zoom input is not a good fit for server round-trips, so once a map loads, the canvas engine takes over entirely client-side and only talks back to the server for two things: campaign-completion reporting (`POST /campaign/{order}/complete`) and, indirectly, whatever the admin/mapbuilder side persists between sessions.

## Directory map

```
app/
  Http/Controllers/
    Admin/              Map editor actions (tiles, route/waypoints, build spots, objects)
    CampaignController  Marks a campaign level completed (session-based, see below)
  Livewire/
    Admin/              Auth-gated admin screens: Dashboard, Maps (builder), TileTypes (object
                         palette), Equipment (tower/enemy stat editor)
    Game/                Public-facing pages: Menu, Campaign, Play, FreePlay, Sandbox(+Select),
                         Bestiary, Armory, Settings
  Models/                Map, MapWaypoint, MapBuildSpot, MapObject, TileType, TowerType,
                         EnemyType, CampaignLevel, User
  Support/
    RoadArt              Generates the 16-sprite-per-skin connector set for roads/fences
    TowerUpgrades        Pure computation of a tower's level 1-3 stat tiers (no DB columns)
    CampaignProgress     Session-based "which campaign levels are completed" tracker
  Services/
    MapPathValidator     Validates a map's waypoint route (exactly one entrance/exit, in bounds)

resources/
  js/game/
    index.js             The whole game engine: rendering, input, combat, waves, campaign
                         reporting — one big `initGame()` closure, no external game framework
    audio.js             Procedurally-synthesized sound effects + music (Web Audio API,
                         no audio files) — a singleton AudioManager
  views/
    layouts/
      admin.blade.php     Light, standard admin chrome
      game.blade.php      Dark, full-bleed game chrome
    livewire/admin/**     Blade views for the admin screens above
    livewire/game/**      Blade views for the public game pages

database/
  migrations/              Additive, one-column-at-a-time migrations (see "Schema notes" below)
  seeders/                 TileTypeSeeder, TowerTypeSeeder, EnemyTypeSeeder — all game content
                         (including every SVG sprite) is generated PHP code, not binary assets
```

## Core concepts

### Maps are data, not levels baked into code

A `Map` row holds a `ground_grid`/`path_grid`/`fence_grid`/`object_grid` (2D arrays of tile codes) plus `tile_size`/`width`/`height`/`tilt_angle`. The **route** is a separate, sparse list of `MapWaypoint` corner points (entrance → corners → exit, in `sequence` order) — enemies walk straight lines between consecutive waypoints, so a route doesn't need to be traced tile-by-tile. `MapBuildSpot` rows are the individual tiles a tower can be placed on. A map only becomes playable once `MapPathValidator` finds exactly one entrance, one exit, and no out-of-bounds/duplicate points — its `status` column (`draft`/`invalid`/`valid`/`published`) records that state, and only `published` maps are servable to players.

### Campaign vs. Free Play vs. Sandbox — same maps, different rules

All three modes route through `App\Livewire\Game\Play` (or `Sandbox`), which just loads a `Map` and ships it to the same JS engine. What differs:
- **Sandbox** sets `mapData.sandbox = true` — infinite gold, free upgrades, no lives lost, manual enemy spawning from a palette. Used for testing/tinkering, not "real" play.
- **Campaign** is the only mode that can advance progress. `CampaignLevel` rows map an `order` (1-10) to a `map_id` plus cosmetic fields (title/area/tagline/icon). Progress itself lives in `App\Support\CampaignProgress`, which is **session-based, not per-user** (there's no player auth) — a simple array of completed level numbers in the Laravel session, with level N unlocked once N-1 is completed.
- **Free Play** lists every published map with no progress tracking at all.

Because Campaign and Free Play/direct links can point at the exact same map, `Play` only reports campaign completion when it was reached via an explicit `?campaign=1` query flag (`#[Url] public bool $campaign`) — set only by the Campaign screen's own links. This exists specifically so playing a campaign map from Free Play doesn't silently unlock the next campaign level.

### Combat is data-driven, not hardcoded per tower

Early on, weapon special-behavior (splash damage, multi-target) was hardcoded as `tower.typeCode === 'raketwerper'` string checks in the JS. That's since been replaced: `tower_types` carries real boolean columns — `splash_damage`, `multi_target`, `targets_ground`, `targets_air` — editable from the admin Equipment screen, and `enemy_types` carries a `domain` (`ground`/`air`). The JS engine reads these off the type object at combat time; a tower simply can't see/target an enemy whose domain isn't in its `targets_*` set, regardless of range. This means a genuinely new weapon archetype (e.g. an anti-air-only tower) needs zero JS changes — just new data.

Tower upgrades (levels 1-3) are computed on the fly by `App\Support\TowerUpgrades` from hardcoded multipliers against a tower's *base* stats — there's no `tower_upgrades` table. Leveling up only changes stats client-side (no new sprite per level yet; a colored glow stands in for "this tower is stronger" visually).

### All art is procedurally generated SVG, no image assets

Every tower/enemy/tile sprite in the seeders is a PHP method returning inline SVG markup (`shape-rendering="crispEdges"` pixel-art style), stored as text in the `sprite`/`base_sprite`/`head_sprite`/etc. columns and served to the browser as base64 `data:image/svg+xml` URIs. `RoadArt` similarly generates the full 16-variant (4-direction neighbor mask) connector sprite set per road/fence skin in code. There is no `public/images` asset pipeline for game content — the only exception is the static main-menu background image.

### Sound is synthesized, not sampled

`resources/js/game/audio.js` is a small `AudioManager` built entirely on the Web Audio API — oscillator blips for shots/explosions/coins/upgrades/etc. and a looping 6-note ambient pad for music. No `.mp3`/`.wav` files anywhere. Mute state for SFX and music are independent, stored in `localStorage` (`a51-sfx-enabled` / `a51-music-enabled`), and read fresh on every page load — so the Settings page (`/settings`) doesn't need to talk to whatever game page is or isn't currently open.

## Admin

Everything under `/admin` requires an authenticated (`auth`+`verified`) user — there's exactly one intended admin, no roles/permissions system.

- **Maps** — the map builder: paint ground/road/fence tiles, place large objects, draw the entrance→exit route, mark build spots, publish/unpublish.
- **Objects** (`TileTypes`) — the palette of paintable tiles/props: set a size tier or upload a custom image per object.
- **Weapons & Monsters** (`Equipment`) — edit existing tower/enemy stats and combat-type flags (splash/multi-target/targets ground+air/domain/is_boss). Creating a genuinely *new* tower or enemy type still requires adding it in the relevant seeder — there's no "create new type" UI yet.

## Local setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Create a MySQL database matching `.env`'s `DB_DATABASE` (defaults to `area51_tower_defense`), then:

```bash
php artisan migrate --seed
npm run build   # or `npm run dev` for a live Vite dev server
php artisan serve
```

Register a user via `/register` to reach `/admin` (there's no seeded admin account).
