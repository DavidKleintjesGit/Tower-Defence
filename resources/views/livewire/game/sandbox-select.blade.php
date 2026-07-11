<div class="relative flex min-h-screen flex-col items-center px-4 py-10">
    <div
        class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('{{ asset('images/menu-background.png') }}')"
    ></div>
    <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-950/70 to-slate-950/95"></div>

    <div class="relative z-10 flex w-full max-w-3xl flex-col items-center gap-8">
        <div class="w-full">
            <a href="{{ route('home') }}" wire:navigate class="text-sm text-slate-400 hover:text-slate-200">
                &larr; Menu
            </a>
        </div>

        <div class="text-center">
            <p class="text-sm uppercase tracking-[0.3em] text-emerald-400">Vrij experimenteren</p>
            <h1 class="mt-2 text-3xl font-black uppercase tracking-widest text-emerald-300 drop-shadow-[0_0_18px_rgba(16,185,129,0.6)]">
                Sandbox
            </h1>
            <p class="mt-2 text-sm text-slate-400">Kies een map. Geen geld, geen levens, geen waves &mdash; zet vrij monsters en wapens in.</p>
        </div>

        @if ($maps->isEmpty())
            <p class="text-sm text-slate-400">Er zijn nog geen maps beschikbaar.</p>
        @else
            <div class="grid w-full grid-cols-1 gap-4 sm:grid-cols-2">
                @foreach ($maps as $map)
                    <a
                        href="{{ route('game.sandbox', ['mapId' => $map->id]) }}"
                        wire:navigate
                        class="group flex flex-col gap-2 rounded-md border border-emerald-500/30 bg-slate-950/60 p-4 text-left backdrop-blur-sm transition hover:border-emerald-400 hover:bg-emerald-500/10"
                    >
                        <span class="text-sm font-bold uppercase tracking-wide text-emerald-300">{{ $map->name }}</span>
                        <span class="text-xs text-slate-400">{{ $map->width }} &times; {{ $map->height }} tegels &middot; {{ $map->status }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
