<div>
    <button
        type="button"
        wire:click="show"
        class="group flex w-full items-center gap-4 rounded-md border border-emerald-500/30 bg-slate-950/60 px-5 py-3.5 text-left backdrop-blur-sm transition hover:border-emerald-400 hover:bg-emerald-500/10"
    >
        <x-menu-panel-content :icon="$icon" :label="$label" />
    </button>

    @if ($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 px-4" wire:click.self="hide">
            <div class="max-w-sm rounded-lg border border-emerald-500/40 bg-slate-900 p-6 text-center shadow-xl">
                <h3 class="text-xl font-bold uppercase tracking-wide text-emerald-300">{{ $feature }}</h3>
                <p class="mt-3 text-sm text-slate-300">Deze functie is nog in ontwikkeling en komt in een volgende fase beschikbaar.</p>
                <button
                    type="button"
                    wire:click="hide"
                    class="mt-5 rounded-md bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-500"
                >
                    Sluiten
                </button>
            </div>
        </div>
    @endif
</div>
