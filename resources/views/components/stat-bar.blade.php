@props(['label', 'value', 'max', 'display' => null, 'color' => 'emerald'])

@php
    $percent = $max > 0 ? min(100, round(($value / $max) * 100)) : 0;

    $barColor = match ($color) {
        'yellow' => 'bg-yellow-400',
        'red' => 'bg-red-400',
        'sky' => 'bg-sky-400',
        default => 'bg-emerald-400',
    };
@endphp

<div>
    <div class="flex items-center justify-between text-[11px] text-slate-400">
        <span>{{ $label }}</span>
        <span class="font-semibold text-slate-200">{{ $display ?? $value }}</span>
    </div>
    <div class="mt-0.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-800">
        <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $percent }}%"></div>
    </div>
</div>
