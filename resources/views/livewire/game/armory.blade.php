<div class="flex h-screen flex-col overflow-hidden bg-slate-950">
    <header class="relative shrink-0 overflow-hidden border-b border-emerald-500/20 bg-slate-900/60 px-6 py-4">
        <div class="pointer-events-none absolute inset-2 sm:inset-3">
            <div class="absolute left-0 top-0 h-5 w-5 border-l-2 border-t-2 border-emerald-500/40"></div>
            <div class="absolute right-0 top-0 h-5 w-5 border-r-2 border-t-2 border-emerald-500/40"></div>
        </div>

        <div class="mx-auto w-full max-w-6xl text-center">
            <p class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.3em] text-emerald-400">
                <span>[</span> Lab 2 // Armory <span>]</span>
            </p>
            <h1 class="mt-1 text-3xl font-black uppercase tracking-widest text-emerald-300 drop-shadow-[0_0_18px_rgba(16,185,129,0.5)]">
                Armory
            </h1>
            <p class="mt-1 text-sm text-slate-400">Available defense systems for base personnel. Click a card to flip it.</p>
        </div>
    </header>

    <div class="shrink-0 px-6 py-2">
        <a
            href="{{ route('home') }}"
            wire:navigate
            class="group flex w-fit items-center gap-1.5 rounded-md border border-emerald-500/30 bg-slate-950/60 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-emerald-300 backdrop-blur-sm transition hover:border-emerald-400 hover:bg-emerald-500/10"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 transition group-hover:-translate-x-0.5">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Menu
        </a>
    </div>

    <div class="flex min-h-0 flex-1 items-center justify-center overflow-x-auto overflow-y-hidden px-4 py-6">
        <div class="flex items-stretch gap-6 py-2">
            @foreach ($towerTypes as $tower)
                <div
                    x-data="{
                        flipped: false,
                        hovering: false,
                        flash: false,
                        timer: null,
                        levelIndex: 0,
                        tiers: @js($tower->upgrade_tiers),
                        get tier() { return this.tiers[this.levelIndex] ?? this.tiers[0]; },
                        get prevTier() { return this.levelIndex > 0 ? this.tiers[this.levelIndex - 1] : this.tiers[0]; },
                        get isBase() { return this.levelIndex === 0; },
                        // Same base/head/muzzle art at every level — the upgrade tiers only
                        // change numbers, not the sprite itself — so a level-tinted glow on
                        // the whole sprite stack is what actually reads as 'stronger' per
                        // level, without needing separate art per tier.
                        get levelGlow() {
                            const level = this.tier?.level ?? 1;
                            if (level <= 1) return '';
                            const glow = level >= 3
                                ? { color: 'rgba(249,115,22,0.85)', blur: '10px', sat: '1.35' }
                                : { color: 'rgba(250,204,21,0.75)', blur: '6px', sat: '1.15' };
                            return `filter: drop-shadow(0 0 ${glow.blur} ${glow.color}) saturate(${glow.sat});`;
                        },
                    }"
                    @mouseenter="hovering = true; timer = setInterval(() => flash = !flash, 220)"
                    @mouseleave="hovering = false; clearInterval(timer); flash = false"
                    class="relative h-[440px] w-64 shrink-0 cursor-pointer [perspective:1200px] sm:w-72"
                    @click="flipped = !flipped"
                >
                    <div
                        class="relative h-full w-full transition-transform duration-500 [transform-style:preserve-3d]"
                        :style="flipped ? 'transform: rotateY(180deg)' : ''"
                    >
                        {{-- FRONT --}}
                        <div class="absolute inset-0 flex h-full flex-col rounded-xl border border-emerald-500/30 bg-slate-900/70 p-4 [backface-visibility:hidden]">
                            <div class="relative aspect-square w-full shrink-0 rounded-lg bg-slate-950/60 transition-[filter] duration-300" :style="levelGlow">
                                @if ($tower->base_url)
                                    <img src="{{ $tower->base_url }}" alt="" class="absolute inset-2 object-contain" style="image-rendering: pixelated">

                                    @if ($tower->head_url)
                                        <img
                                            src="{{ $tower->head_url }}"
                                            alt=""
                                            class="absolute inset-2 object-contain transition-transform duration-300"
                                            :class="hovering ? '-rotate-6' : ''"
                                            style="image-rendering: pixelated"
                                        >
                                    @endif

                                    @if ($tower->muzzle_url)
                                        <img
                                            src="{{ $tower->muzzle_url }}"
                                            alt=""
                                            class="absolute inset-2 object-contain transition-opacity duration-100"
                                            :class="flash ? 'opacity-100' : 'opacity-0'"
                                            style="image-rendering: pixelated"
                                        >
                                    @endif
                                @else
                                    <img
                                        src="{{ $tower->sprite_url }}"
                                        alt="{{ $tower->name }}"
                                        class="absolute inset-2 object-contain transition-transform duration-300"
                                        :class="hovering ? 'scale-110' : ''"
                                        style="image-rendering: pixelated"
                                    >
                                @endif

                                <span class="absolute left-1 top-1 rounded-full border border-yellow-500/40 bg-slate-950/80 px-2 py-0.5 text-[10px] font-bold text-yellow-400 backdrop-blur-sm">
                                    &#36;{{ $tower->cost }}
                                </span>

                                {{-- Level picker where the old "Upgrades" badge used to sit. --}}
                                <div class="absolute right-1 top-1 flex gap-0.5" @click.stop>
                                    <template x-for="(t, i) in tiers" :key="t.level">
                                        <button
                                            type="button"
                                            @click="levelIndex = i"
                                            class="flex h-5 w-5 items-center justify-center rounded-full border text-[10px] font-bold backdrop-blur-sm transition"
                                            :class="levelIndex === i ? 'border-emerald-400 bg-emerald-500/30 text-emerald-200' : 'border-emerald-500/40 bg-slate-950/80 text-emerald-300 hover:bg-emerald-500/20'"
                                        >
                                            <span x-text="t.level"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <h2 class="mt-3 shrink-0 text-center text-lg font-black uppercase tracking-wide text-emerald-300">{{ $tower->name }}</h2>
                            @if ($tower->tagline)
                                <p class="text-center text-xs font-semibold italic leading-tight text-sky-300">&ldquo;{{ $tower->tagline }}&rdquo;</p>
                            @endif

                            {{-- Stats always occupy exactly the same height regardless of which
                            level is picked: the "+bonus" line is always rendered (reserved via
                            min-h), just left blank at level 1 instead of being removed from
                            the layout with x-show. --}}
                            <div class="mt-auto grid w-full grid-cols-4 gap-1 rounded-md bg-slate-950/40 px-1 py-2 text-center">
                                <div>
                                    <p class="text-[8px] font-semibold uppercase tracking-wide text-slate-500">Dmg</p>
                                    <p class="text-sm font-bold text-slate-100" x-text="prevTier.damage"></p>
                                    <p class="min-h-[14px] text-[10px] font-bold text-emerald-400" x-text="isBase ? '' : '+' + (tier.damage - prevTier.damage).toFixed(1)"></p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-semibold uppercase tracking-wide text-slate-500">Range</p>
                                    <p class="text-sm font-bold text-slate-100" x-text="prevTier.range_tiles"></p>
                                    <p class="min-h-[14px] text-[10px] font-bold text-emerald-400" x-text="isBase ? '' : '+' + (tier.range_tiles - prevTier.range_tiles).toFixed(2)"></p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-semibold uppercase tracking-wide text-slate-500">Rate</p>
                                    <p class="text-sm font-bold text-slate-100" x-text="prevTier.rate_per_sec + '/s'"></p>
                                    <p class="min-h-[14px] text-[10px] font-bold text-emerald-400" x-text="isBase ? '' : '+' + (tier.rate_per_sec - prevTier.rate_per_sec).toFixed(2)"></p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-semibold uppercase tracking-wide text-slate-500">DPS</p>
                                    <p class="text-sm font-bold text-yellow-400" x-text="prevTier.dps"></p>
                                    <p class="min-h-[14px] text-[10px] font-bold text-emerald-300" x-text="isBase ? '' : '+' + (tier.dps - prevTier.dps).toFixed(1)"></p>
                                </div>
                            </div>

                            <p class="mt-1.5 min-h-[14px] shrink-0 text-center text-[10px] text-slate-500" x-text="isBase ? '' : 'Upgrade cost: $ ' + tier.upgrade_cost"></p>
                        </div>

                        {{-- BACK: lore only. --}}
                        <div
                            class="absolute inset-0 flex h-full flex-col rounded-xl border border-emerald-500/30 bg-slate-900/90 p-5 [backface-visibility:hidden] [transform:rotateY(180deg)]"
                        >
                            <h2 class="text-center text-lg font-black uppercase tracking-wide text-emerald-300">{{ $tower->name }}</h2>
                            @if ($tower->tagline)
                                <p class="mt-1 text-center text-xs font-semibold italic text-sky-300">&ldquo;{{ $tower->tagline }}&rdquo;</p>
                            @endif
                            <p class="mt-4 flex-1 overflow-y-auto text-center text-sm leading-relaxed text-slate-300">
                                {{ $tower->description }}
                            </p>
                            <p class="mt-2 shrink-0 text-center text-[10px] uppercase tracking-widest text-slate-600">Click to flip back</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
