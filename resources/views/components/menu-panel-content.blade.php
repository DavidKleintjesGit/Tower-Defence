@props(['icon', 'label'])

<span class="flex h-9 w-9 items-center justify-center rounded border border-emerald-500/30 text-emerald-400 transition group-hover:border-emerald-400 group-hover:text-emerald-300">
    <x-menu-icon :name="$icon" />
</span>
<span class="flex-1 text-lg font-bold uppercase tracking-wide text-emerald-300 transition group-hover:text-emerald-200">
    {{ $label }}
</span>
<span class="hidden items-center gap-0.5 sm:flex">
    @for ($i = 0; $i < 5; $i++)
        <span class="h-3 w-0.5 bg-emerald-500/30"></span>
    @endfor
</span>
<span class="text-emerald-400 opacity-0 transition group-hover:opacity-100">&gt;</span>
