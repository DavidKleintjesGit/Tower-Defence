<div class="flex h-full flex-col overflow-hidden bg-slate-950">
    @php
        $statusStyles = match ($map->status) {
            'published' => 'bg-emerald-500/10 text-emerald-400 ring-emerald-500/30',
            'valid' => 'bg-sky-500/10 text-sky-400 ring-sky-500/30',
            'invalid' => 'bg-red-500/10 text-red-400 ring-red-500/30',
            default => 'bg-slate-700/40 text-slate-300 ring-slate-500/30',
        };
    @endphp

    <div class="flex shrink-0 items-center justify-between border-b border-slate-800 bg-slate-900 px-4 py-2.5">
        <a href="{{ route('admin.maps.index') }}" wire:navigate class="flex items-center gap-2 text-slate-200 hover:text-white">
            <x-menu-icon name="alien" class="h-6 w-6 text-emerald-400" />
            <span class="text-xs font-bold uppercase tracking-wide text-slate-400">Area 51 Map Builder</span>
        </a>

        <div class="flex items-center gap-2">
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" @click="open = !open" class="flex items-center gap-2 rounded-md border border-slate-700 bg-slate-800 px-3 py-1.5 text-sm font-medium text-slate-200 hover:bg-slate-700">
                    <span>{{ $map->name }}</span>
                    <svg class="h-3.5 w-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4h2M12 4v16M4 12h16"/></svg>
                    <span class="rounded border border-slate-600 bg-slate-900 px-1.5 py-0.5 text-xs text-slate-400">{{ $map->width }}x{{ $map->height }}</span>
                </button>

                <div x-show="open" x-cloak class="absolute left-0 top-full z-20 mt-2 w-64 rounded-md border border-slate-700 bg-slate-800 p-4 shadow-xl shadow-black/40">
                    <form wire:submit="saveSettings" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-400">Naam</label>
                            <input wire:model="name" type="text" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-900 text-sm text-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-slate-400">Breedte</label>
                                <input wire:model.live="width" type="number" min="5" max="50" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-900 text-sm text-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('width') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex-1">
                                <label class="block text-xs font-medium text-slate-400">Hoogte</label>
                                <input wire:model.live="height" type="number" min="5" max="50" class="mt-1 block w-full rounded-md border-slate-600 bg-slate-900 text-sm text-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                @error('height') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <button
                            type="submit"
                            @if ($width < $map->width || $height < $map->height)
                                wire:confirm="Verkleinen verwijdert tegels en routepunten buiten de nieuwe afmetingen. Doorgaan?"
                            @endif
                            class="w-full rounded-md bg-emerald-600 px-3 py-2 text-xs font-medium text-white transition hover:bg-emerald-500"
                        >
                            Instellingen opslaan
                        </button>
                    </form>
                </div>
            </div>

            <div class="relative" x-data="{ routeOpen: false }" @click.outside="routeOpen = false">
                <button
                    type="button"
                    id="map-status-badge"
                    wire:click="checkRoute"
                    @click="routeOpen = true"
                    class="rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $statusStyles }}"
                >
                    {{ $map->status }}
                </button>

                <div x-show="routeOpen" x-cloak class="absolute left-0 top-full z-20 mt-2 w-72 rounded-md border border-slate-700 bg-slate-800 p-4 text-xs shadow-xl shadow-black/40">
                    @if (in_array($map->status, ['valid', 'published']))
                        <p class="font-medium text-emerald-400">Route is geldig.</p>
                    @elseif ($map->status === 'draft')
                        <p class="text-slate-400">Er is nog geen route ingesteld (ingang, pad, uitgang).</p>
                    @else
                        <p class="mb-2 font-semibold text-red-400">Route ongeldig:</p>
                        <ul class="list-disc space-y-1 pl-4 text-slate-300">
                            @forelse (($map->validation_errors ?? []) as $error)
                                <li>{{ $error }}</li>
                            @empty
                                <li>Onbekende fout &mdash; klik nogmaals om opnieuw te controleren.</li>
                            @endforelse
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button id="test-wave-btn" type="button" class="flex items-center gap-1.5 rounded-md border border-slate-700 bg-slate-800 px-3 py-1.5 text-sm font-medium text-slate-200 transition hover:bg-slate-700">
                <svg class="h-4 w-4 text-sky-400" viewBox="0 0 24 24" fill="currentColor"><path d="M6 4l14 8-14 8V4z"/></svg>
                Test Wave
            </button>

            <button id="grid-toggle-btn" type="button" class="flex items-center gap-1.5 rounded-md border border-slate-700 bg-slate-800 px-3 py-1.5 text-sm font-medium text-slate-200 transition hover:bg-slate-700">
                <svg class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Grid
            </button>

            <button id="map-save-btn" type="button" class="flex items-center gap-1.5 rounded-md border border-slate-700 bg-slate-800 px-3 py-1.5 text-sm font-medium text-slate-200 transition hover:bg-slate-700">
                <svg class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                Save
            </button>

            @if ($map->status === 'published')
                <button
                    type="button"
                    wire:click="unpublish"
                    wire:confirm="Map depubliceren? Spelers kunnen hem dan niet meer spelen."
                    class="rounded-md bg-red-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-red-500"
                >
                    Unpublish
                </button>
            @else
                <button
                    type="button"
                    wire:click="publish"
                    wire:confirm="Map publiceren? Dit maakt de map speelbaar."
                    class="rounded-md bg-emerald-600 px-3 py-1.5 text-sm font-medium text-white transition hover:bg-emerald-500"
                >
                    Publish
                </button>
            @endif

            <div class="relative" x-data="{ settingsOpen: false }" @click.outside="settingsOpen = false">
                <button
                    type="button"
                    title="Instellingen"
                    @click="settingsOpen = !settingsOpen"
                    class="rounded-md border border-slate-700 bg-slate-800 p-1.5 text-slate-400 transition hover:bg-slate-700 hover:text-slate-200"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 11-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 11-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 112.83-2.83l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 112.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                </button>

                <div x-show="settingsOpen" x-cloak class="absolute right-0 top-full z-30 mt-2 w-72 space-y-4 rounded-md border border-slate-700 bg-slate-800 p-4 text-xs shadow-xl shadow-black/40">
                    <div>
                        <p class="mb-2 font-semibold uppercase tracking-wide text-slate-400">Achtergrond</p>
                        <div class="grid grid-cols-3 gap-2" id="map-bg-options">
                            <button type="button" data-bg-mode="dark" class="map-bg-option rounded-md border border-emerald-400 bg-slate-900 py-2 text-white transition hover:border-emerald-400">Donker</button>
                            <button type="button" data-bg-mode="darker" class="map-bg-option rounded-md border border-slate-600 bg-slate-900 py-2 text-slate-300 transition hover:border-emerald-400">Effen</button>
                            <button type="button" data-bg-mode="light" class="map-bg-option rounded-md border border-slate-600 bg-slate-900 py-2 text-slate-300 transition hover:border-emerald-400">Licht</button>
                        </div>
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <p class="font-semibold uppercase tracking-wide text-slate-400">Tilthoek</p>
                            <span id="tilt-angle-value" class="text-slate-300">{{ $map->tilt_angle }}°</span>
                        </div>
                        <input id="tilt-angle-range" type="range" min="0" max="35" value="{{ $map->tilt_angle }}" class="w-full accent-emerald-500">
                    </div>

                    <label class="flex items-center gap-2 text-slate-300">
                        <input id="show-coords-checkbox" type="checkbox" class="rounded border-slate-600 bg-slate-900 text-emerald-500 focus:ring-emerald-500">
                        Toon coördinaten bij hover
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="flex shrink-0 items-center justify-end gap-2 border-b border-slate-800 bg-slate-900 px-4 py-1.5">
        <button id="undo-btn" type="button" title="Ongedaan maken (Ctrl+Z)" class="rounded-md border border-slate-700 bg-slate-800 p-1.5 text-slate-300 transition hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-40">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 14L4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 010 11H11"/></svg>
        </button>

        <button id="redo-btn" type="button" title="Opnieuw doen (Ctrl+Y)" class="rounded-md border border-slate-700 bg-slate-800 p-1.5 text-slate-300 transition hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-40">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 14l5-5-5-5"/><path d="M20 9H9.5a5.5 5.5 0 000 11H13"/></svg>
        </button>

        <button id="pan-tool-btn" type="button" title="Slepen om te navigeren" class="rounded-md border border-slate-700 bg-slate-800 p-1.5 text-slate-300 transition hover:bg-slate-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 3v7h7M13 3L21 11M9 21v-7H2M9 21L1 13M21 13v7h-7M21 21l-8-8M3 11V4h7M3 11L11 3"/></svg>
        </button>

        <button id="delete-tool-btn" type="button" title="Klik op een tegel om te verwijderen" class="rounded-md border border-slate-700 bg-slate-800 p-1.5 text-slate-300 transition hover:bg-slate-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2m3 0v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6h14z"/></svg>
        </button>

        <button id="cancel-tool-btn" type="button" title="Selectie annuleren (Esc)" class="rounded-md border border-slate-700 bg-slate-800 p-1.5 text-slate-300 transition hover:bg-slate-700">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>

    <div id="toast-container" class="pointer-events-none fixed bottom-4 right-4 z-50 flex w-80 max-w-[calc(100vw-2rem)] flex-col-reverse gap-2"></div>

    <div class="flex flex-1 overflow-hidden" x-data="{ tab: 'tiles' }">
        <aside class="flex w-72 shrink-0 flex-col overflow-y-auto border-r border-slate-800 bg-slate-900">
            <div class="flex border-b border-slate-800">
                <button type="button" @click="tab = 'tiles'" :class="tab === 'tiles' ? 'border-b-2 border-emerald-400 text-emerald-400' : 'border-b-2 border-transparent text-slate-500 hover:text-slate-300'" class="flex-1 py-2.5 text-xs font-semibold uppercase tracking-wide transition">
                    Tiles
                </button>
                <button type="button" @click="tab = 'objects'" :class="tab === 'objects' ? 'border-b-2 border-emerald-400 text-emerald-400' : 'border-b-2 border-transparent text-slate-500 hover:text-slate-300'" class="flex-1 py-2.5 text-xs font-semibold uppercase tracking-wide transition">
                    Objects
                </button>
                <button type="button" @click="tab = 'markers'" :class="tab === 'markers' ? 'border-b-2 border-emerald-400 text-emerald-400' : 'border-b-2 border-transparent text-slate-500 hover:text-slate-300'" class="flex-1 py-2.5 text-xs font-semibold uppercase tracking-wide transition">
                    Markers
                </button>
            </div>

            <div class="flex-1 space-y-6 p-4">
                <div x-show="tab === 'tiles'" class="space-y-6">
                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Terrain</h3>
                        <div class="mt-2 grid grid-cols-5 gap-2">
                            @foreach ($tileTypes->where('category', 'ground') as $tile)
                                <button
                                    type="button"
                                    class="tile-palette-btn aspect-square rounded-md border border-slate-700 bg-slate-800 bg-cover transition hover:ring-2 hover:ring-emerald-500/60"
                                    data-tile-code="{{ $tile->code }}"
                                    data-tile-category="ground"
                                    data-tile-sprite="{{ $tileSprites[$tile->code] }}"
                                    title="{{ $tile->label }}"
                                    style="background-image: url('{{ $tileSprites[$tile->code] }}')"
                                >
                                    <span class="sr-only">{{ $tile->label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Road</h3>
                        <p class="mt-1 text-xs text-slate-500">Verbindt automatisch met aangrenzende wegtegels.</p>
                        <div class="mt-2 grid grid-cols-5 gap-2">
                            @foreach ($tileTypes->where('category', 'road') as $tile)
                                <button
                                    type="button"
                                    class="tile-palette-btn aspect-square rounded-md border border-slate-700 bg-slate-800 bg-cover transition hover:ring-2 hover:ring-emerald-500/60"
                                    data-tile-code="{{ $tile->code }}"
                                    data-tile-category="road"
                                    data-tile-sprite="{{ $tileSprites[$tile->code] }}"
                                    data-tile-color="{{ $tile->color }}"
                                    title="{{ $tile->label }}"
                                    style="background-image: url('{{ $tileSprites[$tile->code] }}')"
                                >
                                    <span class="sr-only">{{ $tile->label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Fences</h3>
                        <p class="mt-1 text-xs text-slate-500">Vormt een aaneengesloten muur met buren.</p>
                        <div class="mt-2 grid grid-cols-5 gap-2">
                            @foreach ($tileTypes->where('category', 'fence') as $tile)
                                <button
                                    type="button"
                                    class="tile-palette-btn aspect-square rounded-md border border-slate-700 bg-slate-800 bg-cover transition hover:ring-2 hover:ring-emerald-500/60"
                                    data-tile-code="{{ $tile->code }}"
                                    data-tile-category="fence"
                                    data-tile-sprite="{{ $tileSprites[$tile->code] }}"
                                    data-tile-color="{{ $tile->color }}"
                                    title="{{ $tile->label }}"
                                    style="background-image: url('{{ $tileSprites[$tile->code] }}')"
                                >
                                    <span class="sr-only">{{ $tile->label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'objects'" x-cloak class="space-y-6">
                    @php
                        $propGroups = [
                            'Natuur' => ['plant', 'boulder', 'bones'],
                            'Bouw & Infrastructuur' => ['crate', 'barrier', 'tent', 'lamp', 'watertank', 'fence-corner-post', 'campfire', 'rubble'],
                            'Gevaar' => ['barrel', 'cone', 'sign'],
                            'Alien / Tech' => ['crystal', 'satellite-dish', 'turret-cannon', 'reactor-core', 'antenna'],
                        ];
                        $smallProps = $tileTypes->where('category', 'decoration')->where('footprint_width', 1)->where('footprint_height', 1);
                        $groupedCodes = collect($propGroups)->flatten();
                        $ungrouped = $smallProps->reject(fn ($tile) => $groupedCodes->contains($tile->code));
                    @endphp

                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Props</h3>
                        <p class="mt-1 text-xs text-slate-500">Meerdere per tegel mogelijk. Nogmaals klikken verwijdert het object.</p>

                        <div class="mt-3 space-y-4">
                            @foreach ($propGroups as $groupLabel => $codes)
                                @php $groupTiles = $smallProps->whereIn('code', $codes); @endphp
                                @if ($groupTiles->isNotEmpty())
                                    <div>
                                        <p class="mb-1.5 text-[11px] font-medium uppercase tracking-wide text-slate-500">{{ $groupLabel }}</p>
                                        <div class="grid grid-cols-5 gap-2">
                                            @foreach ($groupTiles as $tile)
                                                <button
                                                    type="button"
                                                    class="tile-palette-btn aspect-square rounded-md border border-slate-700 bg-slate-800 bg-contain bg-center bg-no-repeat transition hover:ring-2 hover:ring-emerald-500/60"
                                                    data-tile-code="{{ $tile->code }}"
                                                    data-tile-category="decoration"
                                                    data-tile-sprite="{{ $tileSprites[$tile->code] }}"
                                                    title="{{ $tile->label }}"
                                                    style="background-image: url('{{ $tileSprites[$tile->code] }}')"
                                                >
                                                    <span class="sr-only">{{ $tile->label }}</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            @if ($ungrouped->isNotEmpty())
                                <div>
                                    <p class="mb-1.5 text-[11px] font-medium uppercase tracking-wide text-slate-500">Overig</p>
                                    <div class="grid grid-cols-5 gap-2">
                                        @foreach ($ungrouped as $tile)
                                            <button
                                                type="button"
                                                class="tile-palette-btn aspect-square rounded-md border border-slate-700 bg-slate-800 bg-contain bg-center bg-no-repeat transition hover:ring-2 hover:ring-emerald-500/60"
                                                data-tile-code="{{ $tile->code }}"
                                                data-tile-category="decoration"
                                                data-tile-sprite="{{ $tileSprites[$tile->code] }}"
                                                title="{{ $tile->label }}"
                                                style="background-image: url('{{ $tileSprites[$tile->code] }}')"
                                            >
                                                <span class="sr-only">{{ $tile->label }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Grote objecten</h3>
                        <p class="mt-1 text-xs text-slate-500">Beslaat meerdere tegels. Klik op het object om het te verwijderen.</p>
                        <div class="mt-2 grid grid-cols-5 gap-2">
                            @foreach ($largeObjects as $tile)
                                <button
                                    type="button"
                                    class="tile-palette-btn aspect-square rounded-md border border-slate-700 bg-slate-800 bg-contain bg-center bg-no-repeat transition hover:ring-2 hover:ring-emerald-500/60"
                                    data-tile-code="{{ $tile->code }}"
                                    data-tile-category="largeobject"
                                    data-tile-sprite="{{ $tileSprites[$tile->code] }}"
                                    data-footprint-width="{{ $tile->footprint_width }}"
                                    data-footprint-height="{{ $tile->footprint_height }}"
                                    title="{{ $tile->label }}"
                                    style="background-image: url('{{ $tileSprites[$tile->code] }}')"
                                >
                                    <span class="sr-only">{{ $tile->label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div x-show="tab === 'markers'" x-cloak class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Route</h3>
                            <button type="button" id="route-clear-btn" class="text-xs font-medium text-red-400 hover:text-red-300">
                                Wissen
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Klik eerst een ingang, dan het pad stap voor stap, dan de uitgang. Nogmaals klikken op het laatste padpunt maakt het ongedaan.</p>
                        <div class="mt-2 grid grid-cols-3 gap-2">
                            <button
                                type="button"
                                class="tile-palette-btn flex aspect-square items-center justify-center rounded-md border border-slate-700 text-xs font-semibold text-white transition hover:ring-2 hover:ring-emerald-500/60"
                                data-tile-code="entrance"
                                data-tile-category="route"
                                title="Ingang"
                                style="background-color: #16a34a"
                            >
                                IN
                            </button>
                            <button
                                type="button"
                                class="tile-palette-btn flex aspect-square items-center justify-center rounded-md border border-slate-700 text-xs font-semibold text-white transition hover:ring-2 hover:ring-emerald-500/60"
                                data-tile-code="path"
                                data-tile-category="route"
                                title="Pad"
                                style="background-color: #2563eb"
                            >
                                <span class="sr-only">Pad</span>
                            </button>
                            <button
                                type="button"
                                class="tile-palette-btn flex aspect-square items-center justify-center rounded-md border border-slate-700 text-xs font-semibold text-white transition hover:ring-2 hover:ring-emerald-500/60"
                                data-tile-code="exit"
                                data-tile-category="route"
                                title="Uitgang"
                                style="background-color: #7c3aed"
                            >
                                UIT
                            </button>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xs font-semibold uppercase tracking-wide text-slate-400">Tower pads</h3>
                        <p class="mt-1 text-xs text-slate-500">Vaste plek waar de speler een toren kan bouwen. Alleen op geschikte grond, niet op de route. Nogmaals klikken verwijdert de bouwplaats.</p>
                        <div class="mt-2">
                            <button
                                type="button"
                                class="tile-palette-btn flex aspect-square w-14 items-center justify-center rounded-md border-2 border-dashed border-sky-500 bg-slate-800/60 text-sky-400 transition hover:ring-2 hover:ring-sky-400/60"
                                data-tile-code="buildspot"
                                data-tile-category="buildspot"
                                title="Bouwplaats"
                            >
                                +
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div id="map-viewport" class="flex flex-1 items-start justify-center overflow-hidden bg-slate-950 p-6 [perspective:1600px]" style="background-image: radial-gradient(circle at 1px 1px, rgba(148,163,184,0.08) 1px, transparent 0); background-size: 24px 24px;">
            <div
                id="map-grid"
                wire:ignore
                class="relative inline-grid select-none rounded-md border border-slate-700 bg-slate-900 shadow-2xl shadow-black/60 [transform-origin:top_center] will-change-transform"
                data-map-id="{{ $map->id }}"
                data-width="{{ $map->width }}"
                data-height="{{ $map->height }}"
                data-tile-size="{{ $map->tile_size }}"
                data-waypoints="{{ $waypoints->map(fn ($w) => ['x' => $w->x, 'y' => $w->y, 'type' => $w->type])->toJson() }}"
                data-build-spots="{{ $buildSpots->map(fn ($spot) => ['x' => $spot->x, 'y' => $spot->y])->toJson() }}"
                data-map-objects="{{ $mapObjects->map(fn ($o) => ['tile_code' => $o->tile_code, 'x' => $o->x, 'y' => $o->y])->toJson() }}"
                data-large-object-types="{{ $largeObjects->mapWithKeys(fn ($t) => [$t->code => ['width' => $t->footprint_width, 'height' => $t->footprint_height, 'sprite' => $tileSprites[$t->code], 'scale' => (float) $t->render_scale]])->toJson() }}"
                data-enemy-sprite="{{ $enemyTypes->first() ? 'data:image/svg+xml;base64,'.base64_encode($enemyTypes->first()->sprite) : '' }}"
                data-road-assets="{{ json_encode($roadAssets) }}"
                data-fence-assets="{{ json_encode($fenceAssets) }}"
                data-tile-scales="{{ json_encode($tileScales) }}"
                style="grid-template-columns: repeat({{ $map->width }}, {{ $map->tile_size }}px);"
            >
                @foreach ($map->ground_grid ?? [] as $y => $row)
                    @foreach ($row as $x => $code)
                        @php
                            $pathCode = $map->path_grid[$y][$x] ?? null;
                            $fenceCode = $map->fence_grid[$y][$x] ?? null;
                        @endphp
                        <div
                            class="map-cell relative border border-slate-800/70 bg-cover"
                            data-x="{{ $x }}"
                            data-y="{{ $y }}"
                            data-code="{{ $code }}"
                            data-path="{{ $pathCode ?? '' }}"
                            data-fence="{{ $fenceCode ?? '' }}"
                            data-objects="{{ json_encode($map->object_grid[$y][$x] ?? []) }}"
                            style="width: {{ $map->tile_size }}px; height: {{ $map->tile_size }}px; background-image: url('{{ $tileSprites[$code] ?? '' }}');"
                        >
                            <div class="cell-road pointer-events-none absolute inset-0"></div>
                            <div class="cell-fence pointer-events-none absolute inset-0"></div>

                            <div class="cell-objects pointer-events-none absolute inset-0.5">
                                @foreach (($map->object_grid[$y][$x] ?? []) as $entry)
                                    @php
                                        if (is_string($entry)) {
                                            $objectCode = $entry;
                                            $subX = 0;
                                            $subY = 0;
                                        } else {
                                            $objectCode = $entry['code'] ?? null;
                                            $subX = $entry['sx'] ?? 0;
                                            $subY = $entry['sy'] ?? 0;
                                        }
                                        $objectScale = (float) ($tileScales[$objectCode] ?? 1);
                                        $footprint = $objectScale <= 1.5 ? 1 : ($objectScale <= 2.1 ? 2 : 3);
                                        $subPercent = 100 / 3;
                                    @endphp
                                    @continue(! $objectCode)
                                    <span
                                        class="absolute bg-contain bg-center bg-no-repeat"
                                        style="left: {{ $subX * $subPercent }}%; top: {{ $subY * $subPercent }}%; width: {{ $footprint * $subPercent }}%; height: {{ $footprint * $subPercent }}%; background-image: url('{{ $tileSprites[$objectCode] ?? '' }}')"
                                        title="{{ $tileLabels[$objectCode] ?? $objectCode }}"
                                    ></span>
                                @endforeach
                            </div>

                            <div class="cell-waypoint pointer-events-none absolute inset-0 flex items-center justify-center"></div>
                            <div class="cell-buildspot pointer-events-none absolute inset-[10%] hidden rounded-md border-2 border-dashed border-sky-400"></div>
                        </div>
                    @endforeach
                @endforeach

                <div id="map-large-objects" class="pointer-events-none absolute inset-0">
                    @foreach ($mapObjects as $object)
                        @php
                            $objectType = $tileTypes->firstWhere('code', $object->tile_code);
                            $ow = $objectType->footprint_width ?? 1;
                            $oh = $objectType->footprint_height ?? 1;
                            $oScale = (float) ($objectType->render_scale ?? 1);
                            $baseW = $ow * $map->tile_size;
                            $baseH = $oh * $map->tile_size;
                            $scaledW = $baseW * $oScale;
                            $scaledH = $baseH * $oScale;
                        @endphp
                        <div
                            class="map-large-object absolute bg-contain bg-center bg-no-repeat"
                            data-tile-code="{{ $object->tile_code }}"
                            data-origin-x="{{ $object->x }}"
                            data-origin-y="{{ $object->y }}"
                            style="left: {{ $object->x * $map->tile_size - ($scaledW - $baseW) / 2 }}px; top: {{ $object->y * $map->tile_size - ($scaledH - $baseH) / 2 }}px; width: {{ $scaledW }}px; height: {{ $scaledH }}px; background-image: url('{{ $tileSprites[$object->tile_code] ?? '' }}');"
                        ></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-slate-800 bg-slate-900 px-4 py-1.5 text-center text-xs text-slate-500">
        Left Click: Place &nbsp;·&nbsp; Right Click: Erase &nbsp;·&nbsp; Del: Delete &nbsp;·&nbsp; Ctrl+Z: Undo &nbsp;·&nbsp; Ctrl+Y: Redo
    </div>

    @vite(['resources/js/map-builder/index.js'])
</div>
