@props(['name'])

<span {{ $attributes->merge(['class' => 'h-5 w-5 shrink-0']) }}>
    @switch($name)
        @case('play')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 4l14 8-14 8V4z" fill="currentColor"/></svg>
            @break
        @case('grid')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="8" height="8"/><rect x="13" y="3" width="8" height="8"/><rect x="3" y="13" width="8" height="8"/><rect x="13" y="13" width="8" height="8"/></svg>
            @break
        @case('gear')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg>
            @break
        @case('alien')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2C7 2 4 6 4 10c0 3 1.5 5 3 6.5 0 1.5 1 3.5 2 3.5s1-1.5 1.5-1.5S11 20 12 20s.5-1.5 1.5-1.5 1.5 1.5 2 1.5 2-2 2-3.5c1.5-1.5 3-3.5 3-6.5 0-4-3-8-8-8z"/><ellipse cx="9" cy="10" rx="1.4" ry="2"/><ellipse cx="15" cy="10" rx="1.4" ry="2"/></svg>
            @break
        @case('rank')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 14l8-5 8 5M4 19l8-5 8 5"/></svg>
            @break
        @case('star')
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.3 6.9.9-5 4.8 1.3 6.8L12 17.3 5.9 20.8l1.3-6.8-5-4.8 6.9-.9L12 2z"/></svg>
            @break
        @case('flask')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 2v6L3.5 18a2 2 0 001.7 3h13.6a2 2 0 001.7-3L15 8V2"/><path d="M8 2h8"/><path d="M6.5 15h11"/></svg>
            @break
        @case('route')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 20c2-4 2-8 8-8s6-4 8-8"/><circle cx="4" cy="20" r="1.6" fill="currentColor" stroke="none"/><circle cx="20" cy="4" r="1.6" fill="currentColor" stroke="none"/></svg>
            @break
        @default
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/></svg>
    @endswitch
</span>
