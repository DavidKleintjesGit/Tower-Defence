<div class="flex h-screen flex-col overflow-hidden bg-slate-950">
    <header class="shrink-0 border-b border-emerald-500/20 bg-slate-900/60 px-6 py-4">
        <div class="mx-auto flex w-full max-w-6xl items-center">
            <a href="{{ route('home') }}" wire:navigate class="text-sm text-slate-400 hover:text-slate-200">&larr; Menu</a>
        </div>
        <div class="mx-auto mt-1 w-full max-w-6xl text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-emerald-400">Lab 2 // Uitrusting</p>
            <h1 class="mt-1 text-3xl font-black uppercase tracking-widest text-emerald-300 drop-shadow-[0_0_18px_rgba(16,185,129,0.5)]">
                Armory
            </h1>
            <p class="mt-1 text-sm text-slate-400">Beschikbare verdedigingsmiddelen voor basispersoneel.</p>
        </div>
    </header>

    <div class="flex min-h-0 flex-1 items-center justify-center overflow-x-auto overflow-y-hidden px-4 py-6">
        <div class="flex h-full items-stretch gap-6 py-2">
            @foreach ($towerTypes as $tower)
                <div
                    x-data="{
                        hovering: false,
                        flash: false,
                        timer: null,
                        showUpgrades: false,
                        levelIndex: 0,
                        tiers: @js($tower->upgrade_tiers),
                        get tier() { return this.tiers[this.levelIndex] ?? this.tiers[0]; },
                        get prevTier() { return this.levelIndex > 0 ? this.tiers[this.levelIndex - 1] : this.tiers[0]; },
                        get isBase() { return this.levelIndex === 0; },
                    }"
                    @mouseenter="hovering = true; timer = setInterval(() => flash = !flash, 220)"
                    @mouseleave="hovering = false; clearInterval(timer); flash = false"
                    class="flex h-full w-80 shrink-0 flex-col items-center overflow-y-auto rounded-xl border border-emerald-500/30 bg-slate-900/70 p-5 sm:w-96"
                >
                    <div class="flex w-full items-center justify-between">
                        <span class="flex items-center gap-1 rounded-full border border-yellow-500/40 bg-yellow-500/10 px-3 py-1 text-xs font-bold text-yellow-400">
                            &#36; {{ $tower->cost }}
                        </span>

                        <button
                            type="button"
                            @click="showUpgrades = !showUpgrades; levelIndex = 0"
                            class="flex items-center gap-1 rounded-full border px-3 py-1 text-[11px] font-bold uppercase tracking-wide transition"
                            :class="showUpgrades ? 'border-emerald-400 bg-emerald-500/20 text-emerald-300' : 'border-emerald-500/40 bg-emerald-500/10 text-emerald-300 hover:bg-emerald-500/20'"
                        >
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.3 6.9.9-5 4.8 1.3 6.8L12 17.3 5.9 20.8l1.3-6.8-5-4.8 6.9-.9L12 2z"/></svg>
                            Upgrades
                        </button>
                    </div>

                    <div class="relative mt-1 aspect-square w-[78%] shrink-0 rounded-lg bg-slate-950/60">
                        @if ($tower->base_url)
                            <img src="{{ $tower->base_url }}" alt="" class="absolute inset-3 object-contain" style="image-rendering: pixelated">

                            @if ($tower->head_url)
                                <img
                                    src="{{ $tower->head_url }}"
                                    alt=""
                                    class="absolute inset-3 object-contain transition-transform duration-300"
                                    :class="hovering ? '-rotate-6' : ''"
                                    style="image-rendering: pixelated"
                                >
                            @endif

                            @if ($tower->muzzle_url)
                                <img
                                    src="{{ $tower->muzzle_url }}"
                                    alt=""
                                    class="absolute inset-3 object-contain transition-opacity duration-100"
                                    :class="flash ? 'opacity-100' : 'opacity-0'"
                                    style="image-rendering: pixelated"
                                >
                            @endif
                        @else
                            <img
                                src="{{ $tower->sprite_url }}"
                                alt="{{ $tower->name }}"
                                class="absolute inset-3 object-contain transition-transform duration-300"
                                :class="hovering ? 'scale-110' : ''"
                                style="image-rendering: pixelated"
                            >
                        @endif
                    </div>

                    <h2 class="mt-3 shrink-0 text-2xl font-black uppercase tracking-wide text-emerald-300">{{ $tower->name }}</h2>

                    {{-- Default view: lore + base (level 1) stats. --}}
                    <div x-show="!showUpgrades" class="contents">
                        @if ($tower->tagline)
                            <p class="mt-1 text-center text-sm font-semibold italic text-sky-300">&ldquo;{{ $tower->tagline }}&rdquo;</p>
                        @endif
                        <p class="mt-2 line-clamp-2 text-center text-sm leading-relaxed text-slate-400">{{ $tower->description }}</p>

                        <dl class="mt-4 grid w-full grid-cols-2 gap-3 border-t border-slate-800 pt-4 text-center">
                            <div>
                                <dt class="text-[10px] uppercase tracking-wide text-slate-500">Schade</dt>
                                <dd class="mt-0.5 text-lg font-bold text-slate-100">{{ $tower->damage }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] uppercase tracking-wide text-slate-500">Bereik</dt>
                                <dd class="mt-0.5 text-lg font-bold text-slate-100">{{ number_format($tower->range_tiles, 1) }}</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] uppercase tracking-wide text-slate-500">Vuursnelheid</dt>
                                <dd class="mt-0.5 text-lg font-bold text-slate-100">{{ number_format($tower->rate_per_sec, 1) }}/s</dd>
                            </div>
                            <div>
                                <dt class="text-[10px] uppercase tracking-wide text-slate-500">DPS</dt>
                                <dd class="mt-0.5 text-lg font-bold text-yellow-400">{{ number_format($tower->dps, 1) }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Upgrade view: pick a level, see it as "previous level's stats
                    + the bonus this level adds" per stat, per the requested diff style. --}}
                    <div x-show="showUpgrades" class="mt-2 flex w-full flex-col items-center">
                        <div class="flex flex-wrap justify-center gap-1.5">
                            <template x-for="(t, i) in tiers" :key="t.level">
                                <button
                                    type="button"
                                    @click="levelIndex = i"
                                    class="rounded-md border px-3 py-1.5 text-xs font-bold uppercase tracking-wide transition"
                                    :class="levelIndex === i ? 'border-emerald-400 bg-emerald-500/20 text-emerald-300' : 'border-slate-700 text-slate-400 hover:border-slate-500'"
                                >
                                    <span x-text="'Niv. ' + t.level"></span>
                                </button>
                            </template>
                        </div>

                        <dl class="mt-4 grid w-full grid-cols-2 gap-3 border-t border-slate-800 pt-4 text-center">
                            <div>
                                <dt class="text-[10px] uppercase tracking-wide text-slate-500">Schade</dt>
                                <dd class="mt-0.5 text-lg font-bold text-slate-100">
                                    <span x-text="prevTier.damage"></span>
                                    <span x-show="!isBase" class="text-sm font-bold text-emerald-400" x-text="'+' + (tier.damage - prevTier.damage).toFixed(1)"></span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-[10px] uppercase tracking-wide text-slate-500">Bereik</dt>
                                <dd class="mt-0.5 text-lg font-bold text-slate-100">
                                    <span x-text="prevTier.range_tiles"></span>
                                    <span x-show="!isBase" class="text-sm font-bold text-emerald-400" x-text="'+' + (tier.range_tiles - prevTier.range_tiles).toFixed(2)"></span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-[10px] uppercase tracking-wide text-slate-500">Vuursnelheid</dt>
                                <dd class="mt-0.5 text-lg font-bold text-slate-100">
                                    <span x-text="prevTier.rate_per_sec + '/s'"></span>
                                    <span x-show="!isBase" class="text-sm font-bold text-emerald-400" x-text="'+' + (tier.rate_per_sec - prevTier.rate_per_sec).toFixed(2) + '/s'"></span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-[10px] uppercase tracking-wide text-slate-500">DPS</dt>
                                <dd class="mt-0.5 text-lg font-bold text-yellow-400">
                                    <span x-text="prevTier.dps"></span>
                                    <span x-show="!isBase" class="text-sm font-bold text-emerald-300" x-text="'+' + (tier.dps - prevTier.dps).toFixed(1)"></span>
                                </dd>
                            </div>
                        </dl>

                        <p class="mt-3 text-center text-[11px] text-slate-500" x-show="!isBase">
                            Upgradekosten: <span class="font-bold text-yellow-400" x-text="'$ ' + tier.upgrade_cost"></span>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
