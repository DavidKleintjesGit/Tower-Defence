<div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-4 py-16 sm:py-10">
    <div
        class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('{{ asset('images/menu-background.png') }}')"
    ></div>
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-950/70 to-slate-950/95"></div>

    <div class="pointer-events-none absolute inset-4 sm:inset-6">
        <div class="absolute left-0 top-0 h-8 w-8 border-l-2 border-t-2 border-emerald-500/50"></div>
        <div class="absolute right-0 top-0 h-8 w-8 border-r-2 border-t-2 border-emerald-500/50"></div>
        <div class="absolute bottom-0 left-0 h-8 w-8 border-b-2 border-l-2 border-emerald-500/50"></div>
        <div class="absolute bottom-0 right-0 h-8 w-8 border-b-2 border-r-2 border-emerald-500/50"></div>
    </div>

    {{-- Bestiary / Armory: pulled out of the main nav into two bold corner
    tiles per explicit request, instead of two more rows in the vertical list. --}}
    <div class="absolute left-3 top-3 z-20 flex gap-2 sm:left-6 sm:top-6 sm:gap-3">
        <a
            href="{{ route('game.bestiary') }}"
            wire:navigate
            class="group flex w-20 flex-col items-center gap-1.5 rounded-lg border-2 border-emerald-500/40 bg-slate-950/70 py-3 text-center shadow-lg shadow-black/40 backdrop-blur-sm transition hover:scale-105 hover:border-emerald-400 hover:bg-emerald-500/10 sm:w-28 sm:gap-2 sm:py-4"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-7 w-7 text-emerald-400 transition group-hover:text-emerald-300 sm:h-9 sm:w-9">
                <path d="M12 2C7 2 4 6 4 10c0 3 1.5 5 3 6.5 0 1.5 1 3.5 2 3.5s1-1.5 1.5-1.5S11 20 12 20s.5-1.5 1.5-1.5 1.5 1.5 2 1.5 2-2 2-3.5c1.5-1.5 3-3.5 3-6.5 0-4-3-8-8-8z"/>
                <ellipse cx="9" cy="10" rx="1.4" ry="2" fill="currentColor" stroke="none"/>
                <ellipse cx="15" cy="10" rx="1.4" ry="2" fill="currentColor" stroke="none"/>
            </svg>
            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-300 sm:text-xs">Bestiary</span>
            <span class="hidden text-[9px] uppercase tracking-wide text-slate-500 sm:block">Monsters</span>
        </a>

        <a
            href="{{ route('game.armory') }}"
            wire:navigate
            class="group flex w-20 flex-col items-center gap-1.5 rounded-lg border-2 border-emerald-500/40 bg-slate-950/70 py-3 text-center shadow-lg shadow-black/40 backdrop-blur-sm transition hover:scale-105 hover:border-emerald-400 hover:bg-emerald-500/10 sm:w-28 sm:gap-2 sm:py-4"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-7 w-7 text-emerald-400 transition group-hover:text-emerald-300 sm:h-9 sm:w-9">
                <circle cx="12" cy="12" r="8"/>
                <circle cx="12" cy="12" r="3.2"/>
                <path d="M12 2v3.5M12 18.5V22M2 12h3.5M18.5 12H22"/>
            </svg>
            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-300 sm:text-xs">Armory</span>
            <span class="hidden text-[9px] uppercase tracking-wide text-slate-500 sm:block">Wapens</span>
        </a>
    </div>

    <div class="relative z-10 flex w-full flex-col items-center gap-8 sm:gap-10">
        <div class="text-center">
            <p class="inline-flex items-center gap-3 text-xs uppercase tracking-[0.3em] text-emerald-400 sm:text-sm">
                <span>[</span> Top secret // clearance required <span>]</span>
            </p>

            <div class="mx-auto mt-4 flex flex-col items-center">
                <svg viewBox="0 0 60 40" class="h-10 w-16 text-emerald-400">
                    <path d="M2 30 L22 18 L30 22 L38 18 L58 30" fill="none" stroke="currentColor" stroke-width="1.5" opacity="0.7"/>
                    <path d="M22 22c0-5 3.5-9 8-9s8 4 8 9-3.5 8-8 8-8-3-8-8z" fill="currentColor"/>
                    <ellipse cx="27" cy="21" rx="1.6" ry="2.4" fill="#022c1e"/>
                    <ellipse cx="33" cy="21" rx="1.6" ry="2.4" fill="#022c1e"/>
                </svg>
            </div>

            <h1 class="mt-2 text-4xl font-black uppercase tracking-widest text-emerald-300 drop-shadow-[0_0_18px_rgba(16,185,129,0.6)] sm:text-6xl">
                Area 51
            </h1>
            <p class="mt-1 text-base uppercase tracking-[0.4em] text-sky-300 sm:text-lg">Tower Defense</p>
        </div>

        <nav class="w-full max-w-md space-y-3">
            <a
                href="{{ route('game.play') }}"
                wire:navigate
                class="group flex w-full items-center gap-4 rounded-md border border-emerald-400 bg-emerald-500/10 px-5 py-3.5 text-left backdrop-blur-sm transition hover:bg-emerald-500/20"
            >
                <x-menu-panel-content icon="play" label="Spelen" />
            </a>

            <a
                href="{{ route('game.free-play') }}"
                wire:navigate
                class="group flex w-full items-center gap-4 rounded-md border border-emerald-500/30 bg-slate-950/60 px-5 py-3.5 text-left backdrop-blur-sm transition hover:border-emerald-400 hover:bg-emerald-500/10"
            >
                <x-menu-panel-content icon="grid" label="Free Play" />
            </a>

            <a
                href="{{ route('game.sandbox-select') }}"
                wire:navigate
                class="group flex w-full items-center gap-4 rounded-md border border-emerald-500/30 bg-slate-950/60 px-5 py-3.5 text-left backdrop-blur-sm transition hover:border-emerald-400 hover:bg-emerald-500/10"
            >
                <x-menu-panel-content icon="flask" label="Sandbox" />
            </a>

            <livewire:game.coming-soon-notice label="Instellingen" icon="gear" wire:key="menu-settings" />
        </nav>

        <div class="flex w-full max-w-md flex-wrap items-center justify-center gap-x-4 gap-y-1.5 text-[10px] uppercase tracking-widest text-emerald-500/70 sm:flex-nowrap sm:justify-between">
            <div class="flex items-center gap-2">
                <span>Clearance level: 7</span>
                <span class="flex items-center gap-0.5">
                    @for ($i = 0; $i < 8; $i++)
                        <span class="h-2 w-1.5 {{ $i < 6 ? 'bg-emerald-500/70' : 'bg-emerald-500/20' }}"></span>
                    @endfor
                </span>
            </div>
            <span class="hidden sm:inline">Druk op enter om te starten</span>
            <span class="flex items-center gap-1.5">
                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-400"></span>
                Secure feed
            </span>
        </div>
    </div>
</div>
