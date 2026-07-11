<div
    x-data="{ tab: 'monsters', hasBosses: @js($hasBosses) }"
    class="flex h-screen flex-col overflow-hidden bg-slate-950"
>
    <header class="shrink-0 border-b border-emerald-500/20 bg-slate-900/60 px-6 py-4">
        <div class="mx-auto flex w-full max-w-6xl items-center">
            <a href="{{ route('home') }}" wire:navigate class="text-sm text-slate-400 hover:text-slate-200">&larr; Menu</a>
        </div>
        <div class="mx-auto mt-1 w-full max-w-6xl text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-emerald-400">Sector 7 // Dreigingsdossier</p>
            <h1 class="mt-1 text-3xl font-black uppercase tracking-widest text-emerald-300 drop-shadow-[0_0_18px_rgba(16,185,129,0.5)]">
                Bestiary
            </h1>
            <p class="mt-1 text-sm text-slate-400">Gedocumenteerde dreigingen binnen de perimeter van Area 51.</p>
        </div>

        <div class="mt-4 flex justify-center gap-2">
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
    </header>

    <div class="flex min-h-0 flex-1 items-center justify-center overflow-x-auto overflow-y-hidden px-4 py-6">
        <p x-show="tab === 'bosses' && !hasBosses" class="text-sm text-slate-500">
            Nog geen bosses gedocumenteerd &mdash; kom later terug.
        </p>

        <div class="flex h-full items-stretch gap-6 py-2">
            @foreach ($enemyTypes as $enemy)
                @php
                    $threatRatio = $maxThreat > 0 ? $enemy->threat_score / $maxThreat : 0;
                    $threatLabel = $threatRatio >= 0.75 ? 'Extreem' : ($threatRatio >= 0.45 ? 'Hoog' : 'Gemiddeld');
                    $threatColor = $threatRatio >= 0.75 ? 'border-red-500/40 bg-red-500/10 text-red-400' : ($threatRatio >= 0.45 ? 'border-yellow-500/40 bg-yellow-500/10 text-yellow-400' : 'border-emerald-500/40 bg-emerald-500/10 text-emerald-400');
                @endphp

                <div
                    x-data="{ frames: @js($enemy->frame_urls), frame: 0, timer: null }"
                    x-show="tab === '{{ $enemy->is_boss ? 'bosses' : 'monsters' }}'"
                    @mouseenter="if (frames.length > 1) { timer = setInterval(() => frame = (frame + 1) % frames.length, 140) }"
                    @mouseleave="clearInterval(timer); frame = 0"
                    class="flex h-full w-80 shrink-0 flex-col items-center overflow-y-auto rounded-xl border border-emerald-500/30 bg-slate-900/70 p-5 sm:w-96"
                >
                    <div class="flex w-full justify-end">
                        <span class="rounded-full border px-3 py-1 text-[11px] font-bold uppercase tracking-wide {{ $threatColor }}">
                            {{ $threatLabel }}
                        </span>
                    </div>

                    <div class="mt-1 flex aspect-square w-[78%] shrink-0 items-center justify-center rounded-lg bg-slate-950/60 p-3">
                        <img
                            :src="frames.length ? frames[frame] : '{{ $enemy->sprite_url }}'"
                            alt="{{ $enemy->name }}"
                            class="h-full w-full object-contain"
                            style="image-rendering: pixelated"
                        >
                    </div>

                    <h2 class="mt-3 shrink-0 text-2xl font-black uppercase tracking-wide text-emerald-300">{{ $enemy->name }}</h2>
                    @if ($enemy->tagline)
                        <p class="mt-1 text-center text-sm font-semibold italic text-sky-300">&ldquo;{{ $enemy->tagline }}&rdquo;</p>
                    @endif
                    <p class="mt-2 line-clamp-2 text-center text-sm leading-relaxed text-slate-400">{{ $enemy->description }}</p>

                    <dl class="mt-4 grid w-full grid-cols-3 gap-3 border-t border-slate-800 pt-4 text-center">
                        <div>
                            <dt class="text-[10px] uppercase tracking-wide text-slate-500">HP</dt>
                            <dd class="mt-0.5 text-lg font-bold text-slate-100">{{ $enemy->hp }}</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] uppercase tracking-wide text-slate-500">Snelheid</dt>
                            <dd class="mt-0.5 text-lg font-bold text-slate-100">{{ number_format($enemy->speed_multiplier, 2) }}x</dd>
                        </div>
                        <div>
                            <dt class="text-[10px] uppercase tracking-wide text-slate-500">Beloning</dt>
                            <dd class="mt-0.5 text-lg font-bold text-yellow-400">$ {{ $enemy->bounty }}</dd>
                        </div>
                    </dl>
                </div>
            @endforeach
        </div>
    </div>
</div>
