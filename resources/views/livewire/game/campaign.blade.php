<div class="flex h-screen flex-col overflow-hidden bg-slate-950">
    <header class="relative shrink-0 overflow-hidden border-b border-emerald-500/20 bg-slate-900/60 px-6 py-4">
        <div class="pointer-events-none absolute inset-2 sm:inset-3">
            <div class="absolute left-0 top-0 h-5 w-5 border-l-2 border-t-2 border-emerald-500/40"></div>
            <div class="absolute right-0 top-0 h-5 w-5 border-r-2 border-t-2 border-emerald-500/40"></div>
        </div>

        <div class="mx-auto w-full max-w-6xl text-center">
            <p class="inline-flex items-center gap-2 text-xs uppercase tracking-[0.3em] text-emerald-400">
                <span>[</span> Operatie Roswell // Voortgang <span>]</span>
            </p>
            <h1 class="mt-1 text-3xl font-black uppercase tracking-widest text-emerald-300 drop-shadow-[0_0_18px_rgba(16,185,129,0.5)]">
                Campaign
            </h1>
        </div>
    </header>

    <div class="shrink-0 px-6 py-2">
        <div class="mx-auto flex w-full max-w-6xl items-center gap-4">
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

            <div class="flex flex-1 items-center gap-3">
                <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-800">
                    <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $completedCount * 10 }}%"></div>
                </div>
                <span class="shrink-0 text-xs font-bold uppercase tracking-wide text-emerald-300">{{ $completedCount }} / 10 voltooid</span>
            </div>
        </div>
    </div>

    <div class="min-h-0 flex-1 overflow-y-auto px-6 py-8">
        <div class="relative mx-auto max-w-lg">
            <div class="absolute bottom-0 left-1/2 top-0 w-1 -translate-x-1/2 rounded-full bg-gradient-to-b from-emerald-500/50 via-emerald-500/20 to-red-500/40"></div>

            <div class="relative flex flex-col gap-10">
                @foreach ($levels as $level)
                    @php $onLeft = $level['order'] % 2 === 1; @endphp
                    <div class="relative flex {{ $onLeft ? 'justify-start' : 'justify-end' }}">
                        @if ($level['unlocked'])
                            <a
                                href="{{ route('game.play', ['mapId' => $level['map_id'], 'campaign' => 1]) }}"
                                wire:navigate
                                class="group relative z-10 flex w-72 items-center gap-3 rounded-xl border bg-gradient-to-br p-3 shadow-lg shadow-black/40 backdrop-blur-sm transition hover:scale-[1.03] {{ $level['theme'] }}"
                            >
                                <div class="relative flex h-16 w-16 shrink-0 items-center justify-center rounded-lg bg-slate-950/60">
                                    @if ($iconSprites[$level['icon_code']] ?? null)
                                        <img src="{{ $iconSprites[$level['icon_code']] }}" alt="" class="h-12 w-12 object-contain" style="image-rendering: pixelated">
                                    @endif
                                    <span class="absolute -left-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-950 bg-emerald-500 text-xs font-black text-slate-950">
                                        {{ $level['order'] }}
                                    </span>
                                    @if ($level['completed'])
                                        <span class="absolute -bottom-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-950 bg-emerald-400 text-slate-950">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="h-3.5 w-3.5">
                                                <path d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1 text-left">
                                    <p class="truncate text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ $level['area'] }}</p>
                                    <p class="truncate text-base font-black uppercase tracking-wide text-emerald-300 group-hover:text-emerald-200">{{ $level['title'] }}</p>
                                    <p class="mt-0.5 line-clamp-2 text-[11px] italic leading-snug text-slate-400">{{ $level['tagline'] }}</p>
                                </div>
                            </a>
                        @else
                            <div class="relative z-10 flex w-72 items-center gap-3 rounded-xl border border-slate-800 bg-slate-900/60 p-3 opacity-60">
                                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg bg-slate-950/60">
                                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7 text-slate-600">
                                        <path d="M6 10V8a6 6 0 1112 0v2h1a1 1 0 011 1v10a1 1 0 01-1 1H5a1 1 0 01-1-1V11a1 1 0 011-1h1zm2 0h8V8a4 4 0 10-8 0v2z"/>
                                    </svg>
                                    <span class="absolute -left-2 -top-2 flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-950 bg-slate-700 text-xs font-black text-slate-400">
                                        {{ $level['order'] }}
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 text-left">
                                    <p class="truncate text-[10px] font-bold uppercase tracking-wide text-slate-600">Vergrendeld</p>
                                    <p class="truncate text-base font-black uppercase tracking-wide text-slate-500">{{ $level['title'] }}</p>
                                    <p class="mt-0.5 text-[11px] italic text-slate-600">Voltooi level {{ $level['order'] - 1 }} om te ontgrendelen.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
