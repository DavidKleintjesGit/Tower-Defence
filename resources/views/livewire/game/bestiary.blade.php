<div
    x-data="{ tab: 'monsters', hasBosses: @js($hasBosses) }"
    class="flex h-screen flex-col overflow-hidden bg-slate-950"
>
    <header class="relative shrink-0 overflow-hidden border-b border-emerald-500/20 bg-slate-900/60 px-6 py-4">
        <div class="pointer-events-none absolute inset-2 sm:inset-3">
            <div class="absolute left-0 top-0 h-5 w-5 border-l-2 border-t-2 border-emerald-500/40"></div>
            <div class="absolute right-0 top-0 h-5 w-5 border-r-2 border-t-2 border-emerald-500/40"></div>
        </div>

        <div class="mx-auto w-full max-w-6xl text-center">
            <p class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.3em] text-emerald-400">
                <span>[</span> Sector 7 // Threat Dossier <span>]</span>
            </p>
            <h1 class="mt-1 text-3xl font-black uppercase tracking-widest text-emerald-300 drop-shadow-[0_0_18px_rgba(16,185,129,0.5)]">
                Bestiary
            </h1>
        </div>
    </header>

    <div class="grid shrink-0 grid-cols-3 items-center px-6 py-2">
        <a
            href="{{ route('home') }}"
            wire:navigate
            class="group flex items-center gap-1.5 justify-self-start rounded-md border border-emerald-500/30 bg-slate-950/60 px-3 py-1.5 text-xs font-bold uppercase tracking-wide text-emerald-300 backdrop-blur-sm transition hover:border-emerald-400 hover:bg-emerald-500/10"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-3 w-3 transition group-hover:-translate-x-0.5">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Menu
        </a>

        <div class="flex justify-self-center gap-2">
            <button
                type="button"
                @click="tab = 'monsters'"
                class="rounded-full border px-4 py-1.5 text-xs font-bold uppercase tracking-wide transition"
                :class="tab === 'monsters' ? 'border-emerald-400 bg-emerald-500/20 text-emerald-300' : 'border-slate-700 text-slate-400 hover:border-slate-500'"
            >
                Monsters
            </button>
            <button
                type="button"
                @click="tab = 'bosses'"
                class="rounded-full border px-4 py-1.5 text-xs font-bold uppercase tracking-wide transition"
                :class="tab === 'bosses' ? 'border-red-400 bg-red-500/20 text-red-300' : 'border-slate-700 text-slate-400 hover:border-slate-500'"
            >
                Bosses
            </button>
        </div>
    </div>

    <div class="flex min-h-0 flex-1 items-center justify-center overflow-x-auto overflow-y-hidden px-4 py-6">
        <p x-show="tab === 'bosses' && !hasBosses" class="text-sm text-slate-500">
            No bosses documented yet &mdash; check back later.
        </p>

        <div class="flex items-stretch gap-5 py-2">
            @foreach ($enemyTypes as $enemy)
                @php
                    $threatRatio = $maxThreat > 0 ? $enemy->threat_score / $maxThreat : 0;
                    $threatLabel = $threatRatio >= 0.75 ? 'Extreme' : ($threatRatio >= 0.45 ? 'High' : 'Moderate');
                    $threatColor = $threatRatio >= 0.75 ? 'border-red-500/40 bg-red-500/10 text-red-400' : ($threatRatio >= 0.45 ? 'border-yellow-500/40 bg-yellow-500/10 text-yellow-400' : 'border-emerald-500/40 bg-emerald-500/10 text-emerald-400');
                @endphp

                <div
                    x-data="{ frames: @js($enemy->frame_urls), frame: 0, timer: null, flipped: false }"
                    x-show="tab === '{{ $enemy->is_boss ? 'bosses' : 'monsters' }}'"
                    @mouseenter="if (frames.length > 1) { timer = setInterval(() => frame = (frame + 1) % frames.length, 140) }"
                    @mouseleave="clearInterval(timer); frame = 0"
                    class="relative h-[440px] w-64 shrink-0 cursor-pointer [perspective:1200px] sm:w-72"
                    @click="flipped = !flipped"
                >
                    <div
                        class="relative h-full w-full transition-transform duration-500 [transform-style:preserve-3d]"
                        :style="flipped ? 'transform: rotateY(180deg)' : ''"
                    >
                        {{-- FRONT --}}
                        <div class="absolute inset-0 flex h-full flex-col rounded-xl border border-emerald-500/30 bg-slate-900/70 p-4 [backface-visibility:hidden]">
                            <div class="relative aspect-square w-full shrink-0 rounded-lg bg-slate-950/60">
                                <img
                                    :src="frames.length ? frames[frame] : '{{ $enemy->sprite_url }}'"
                                    alt="{{ $enemy->name }}"
                                    class="absolute inset-2 object-contain"
                                    style="image-rendering: pixelated"
                                >

                                <span class="absolute right-1 top-1 rounded-full border px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide {{ $threatColor }}">
                                    {{ $threatLabel }}
                                </span>
                            </div>

                            <h2 class="mt-3 shrink-0 text-center text-lg font-black uppercase tracking-wide text-emerald-300">{{ $enemy->name }}</h2>
                            @if ($enemy->tagline)
                                <p class="text-center text-xs font-semibold italic leading-tight text-sky-300">&ldquo;{{ $enemy->tagline }}&rdquo;</p>
                            @endif

                            <div class="mt-auto grid w-full grid-cols-3 gap-1 rounded-md bg-slate-950/40 px-1 py-2 text-center">
                                <div>
                                    <p class="text-[8px] font-semibold uppercase tracking-wide text-slate-500">HP</p>
                                    <p class="text-sm font-bold text-slate-100">{{ $enemy->hp }}</p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-semibold uppercase tracking-wide text-slate-500">Speed</p>
                                    <p class="text-sm font-bold text-slate-100">{{ number_format($enemy->speed_multiplier, 2) }}x</p>
                                </div>
                                <div>
                                    <p class="text-[8px] font-semibold uppercase tracking-wide text-slate-500">Bounty</p>
                                    <p class="text-sm font-bold text-yellow-400">$ {{ $enemy->bounty }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- BACK --}}
                        <div class="absolute inset-0 flex h-full flex-col rounded-xl border border-emerald-500/30 bg-slate-900/90 p-5 [backface-visibility:hidden] [transform:rotateY(180deg)]">
                            <h2 class="text-center text-lg font-black uppercase tracking-wide text-emerald-300">{{ $enemy->name }}</h2>
                            @if ($enemy->tagline)
                                <p class="mt-1 text-center text-xs font-semibold italic text-sky-300">&ldquo;{{ $enemy->tagline }}&rdquo;</p>
                            @endif
                            <p class="mt-4 flex-1 overflow-y-auto text-center text-sm leading-relaxed text-slate-300">
                                {{ $enemy->description }}
                            </p>
                            <p class="mt-2 shrink-0 text-center text-[10px] uppercase tracking-widest text-slate-600">Click to flip back</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
