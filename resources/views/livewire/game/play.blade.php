<div class="flex h-screen flex-col overflow-hidden bg-slate-950">
    @if (! $map)
        <div class="flex h-full flex-col items-center justify-center gap-6 px-4 text-center">
            <p class="text-sm uppercase tracking-[0.3em] text-emerald-400">Top secret // clearance required</p>
            <h1 class="mt-2 text-2xl font-bold uppercase tracking-widest text-emerald-300">Geen speelbare map</h1>
            <p class="mt-3 max-w-sm text-sm text-slate-400">
                Er is nog geen gepubliceerde map. Maak en publiceer er een in de adminomgeving.
            </p>
            <a
                href="{{ route('home') }}"
                wire:navigate
                class="mt-6 inline-block rounded-md border border-emerald-500/40 px-4 py-2 text-sm text-emerald-300 transition hover:border-emerald-400 hover:bg-slate-800"
            >
                &larr; Terug naar menu
            </a>
        </div>
    @else
        <div id="game-topbar" class="relative z-20 flex h-14 shrink-0 items-center justify-between gap-4 border-b border-emerald-500/20 bg-slate-900/95 px-4">
            <a href="{{ route('home') }}" wire:navigate class="text-sm text-slate-400 hover:text-slate-200">
                &larr; Menu
            </a>

            <h1 class="hidden text-sm font-bold uppercase tracking-widest text-emerald-300 sm:block">{{ $map->name }}</h1>

            <div class="flex items-center gap-4 text-xs sm:text-sm">
                <span class="flex items-center gap-1.5 text-slate-300">
                    <span class="text-slate-500">Levens</span>
                    <span id="game-lives" class="font-bold text-red-400">10</span>
                </span>
                <span class="flex items-center gap-1.5 text-slate-300">
                    <span class="text-slate-500">Geld</span>
                    <span id="game-gold" class="font-bold text-yellow-400">0</span>
                </span>
                <span class="hidden items-center gap-1.5 text-slate-300 md:flex">
                    <span class="text-slate-500">Wave</span>
                    <span id="game-wave-info" class="font-bold text-emerald-300">-</span>
                </span>
                <button
                    id="game-wave-btn"
                    type="button"
                    class="rounded-md border border-emerald-500/40 px-3 py-1.5 text-xs font-medium text-emerald-300 transition hover:border-emerald-400 hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Start wave 1
                </button>
            </div>
        </div>

        <div class="flex flex-1 overflow-hidden">
            <div id="game-viewport" class="relative flex flex-1 items-center justify-center overflow-hidden [perspective:1600px]">
                <canvas
                    id="game-canvas"
                    class="rounded-md border border-emerald-500/30 shadow-lg shadow-emerald-500/10 will-change-transform"
                    style="transform-origin: center center; image-rendering: pixelated;"
                ></canvas>

                <div id="info-popup" class="absolute right-3 top-3 z-10 hidden w-52 rounded-md border border-emerald-500/40 bg-slate-950/90 p-3 text-xs text-slate-200 shadow-lg backdrop-blur-sm">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <img id="info-popup-image" class="h-10 w-10 shrink-0 rounded bg-slate-900/60" style="image-rendering: pixelated" alt="">
                            <span id="info-popup-name" class="font-bold uppercase tracking-wide text-emerald-300"></span>
                        </div>
                        <button id="info-popup-close" type="button" class="text-slate-500 hover:text-slate-300">&times;</button>
                    </div>
                    <div class="mt-2 space-y-1">
                        <div class="flex items-center justify-between">
                            <span id="info-stat-label-1" class="text-slate-400"></span>
                            <span id="info-stat-value-1" class="font-semibold text-slate-100"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span id="info-stat-label-2" class="text-slate-400"></span>
                            <span id="info-stat-value-2" class="font-semibold text-slate-100"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span id="info-stat-label-3" class="text-slate-400"></span>
                            <span id="info-stat-value-3" class="font-semibold text-slate-100"></span>
                        </div>
                    </div>
                </div>

                <div id="game-over-overlay" class="absolute inset-0 hidden flex-col items-center justify-center gap-4 bg-black/80">
                    <p class="text-2xl font-bold uppercase tracking-widest text-red-400">Game Over</p>
                    <button id="game-restart-btn" type="button" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-500">
                        Opnieuw
                    </button>
                </div>

                <div id="game-victory-overlay" class="absolute inset-0 hidden flex-col items-center justify-center gap-4 bg-black/80">
                    <p class="text-2xl font-bold uppercase tracking-widest text-emerald-300">Overwinning!</p>
                    <button id="game-victory-restart-btn" type="button" class="rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-500">
                        Opnieuw
                    </button>
                </div>

                {{-- Overlays the map instead of taking up flex layout space, so
                opening it doesn't shrink/shift the canvas. --}}
                <div id="tower-detail-sidebar" class="absolute inset-y-0 right-0 z-20 hidden w-64 flex-col overflow-y-auto border-l border-emerald-500/20 bg-slate-900/95 p-4 shadow-2xl shadow-black/60 sm:w-72">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase tracking-widest text-emerald-400">Toren</span>
                        <button id="tower-detail-close" type="button" class="text-slate-500 hover:text-slate-300">&times;</button>
                    </div>

                    <div class="mt-3 flex aspect-square w-full items-center justify-center rounded-lg bg-slate-950/60 p-4">
                        <img id="tower-detail-image" src="" alt="" class="h-full w-full object-contain" style="image-rendering: pixelated">
                    </div>

                    <h2 id="tower-detail-name" class="mt-3 text-lg font-black uppercase tracking-wide text-emerald-300"></h2>

                    <dl class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-800 pt-3 text-center text-xs">
                        <div>
                            <dt class="uppercase tracking-wide text-slate-500">Schade</dt>
                            <dd id="tower-detail-damage" class="mt-0.5 text-base font-bold text-slate-100"></dd>
                        </div>
                        <div>
                            <dt class="uppercase tracking-wide text-slate-500">Bereik</dt>
                            <dd id="tower-detail-range" class="mt-0.5 text-base font-bold text-slate-100"></dd>
                        </div>
                        <div>
                            <dt class="uppercase tracking-wide text-slate-500">Vuursnelheid</dt>
                            <dd id="tower-detail-rate" class="mt-0.5 text-base font-bold text-slate-100"></dd>
                        </div>
                        <div>
                            <dt class="uppercase tracking-wide text-slate-500">Kosten</dt>
                            <dd id="tower-detail-cost" class="mt-0.5 text-base font-bold text-yellow-400"></dd>
                        </div>
                    </dl>

                    <div class="mt-4 border-t border-slate-800 pt-3 text-center">
                        <p id="tower-upgrade-label" class="text-[10px] uppercase tracking-widest text-slate-500"></p>
                        <button
                            id="tower-upgrade-btn"
                            type="button"
                            class="mt-2 w-full rounded-md border border-emerald-500/40 bg-emerald-500/10 px-3 py-2 text-xs font-bold uppercase tracking-wide text-emerald-300 transition hover:bg-emerald-500/20 disabled:cursor-not-allowed disabled:opacity-40"
                        >
                            Upgrade &mdash; <span id="tower-upgrade-cost"></span>
                        </button>
                    </div>
                </div>
            </div>

            <div id="game-sidebar" class="relative z-20 flex w-28 shrink-0 flex-col gap-2 overflow-y-auto border-l border-emerald-500/20 bg-slate-900/95 p-2 sm:w-32">
                <p class="text-center text-[9px] uppercase tracking-widest text-slate-500">Wapens</p>

                <div id="weapon-palette" class="flex flex-col gap-2">
                    @foreach ($towerTypes as $tower)
                        <div
                            class="weapon-palette-item flex h-24 w-full shrink-0 cursor-pointer flex-col items-center justify-center gap-0.5 rounded-md border border-emerald-500/30 bg-slate-950/70 p-1.5 text-center transition hover:border-emerald-400"
                            data-tower-code="{{ $tower['code'] }}"
                            data-tower-cost="{{ $tower['cost'] }}"
                            title="{{ $tower['name'] }}: {{ $tower['damage'] }} schade, {{ number_format($tower['range_tiles'], 1) }} bereik"
                        >
                            <img src="{{ $tower['sprite'] }}" alt="{{ $tower['name'] }}" class="h-8 w-8 shrink-0" style="image-rendering: pixelated">
                            <span class="w-full truncate text-[9px] font-semibold uppercase tracking-wide text-emerald-300">{{ $tower['name'] }}</span>
                            <span class="text-[10px] font-bold text-yellow-400">{{ $tower['cost'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-auto text-center text-[9px] uppercase leading-tight tracking-widest text-slate-600">
                    Special moves<br>binnenkort
                </div>
            </div>
        </div>

        <script id="map-data" type="application/json">{!! json_encode($mapData) !!}</script>
    @endif

    @vite(['resources/js/game/index.js'])
</div>
