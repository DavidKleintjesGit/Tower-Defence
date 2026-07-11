<div>
    <header class="bg-white">
        <div class="flex items-center justify-between px-6 py-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Maps</h2>
                <p class="mt-1 text-sm text-gray-500">Beheer de speelbare maps voor Area 51 Tower Defense.</p>
            </div>

            <button
                type="button"
                wire:click="createBlank"
                class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700"
            >
                <span class="text-lg leading-none">+</span> Nieuwe map
            </button>
        </div>
    </header>

    <div class="px-6 py-6">
        @if ($maps->isEmpty())
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white py-16 text-center">
                <p class="text-sm font-medium text-gray-900">Nog geen maps</p>
                <p class="mt-1 text-sm text-gray-500">Klik op "Nieuwe map" om direct te beginnen in de mapbuilder.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($maps as $map)
                    @php
                        $statusStyles = match ($map->status) {
                            'published' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                            'valid' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                            'invalid' => 'bg-red-50 text-red-700 ring-red-600/20',
                            default => 'bg-gray-100 text-gray-600 ring-gray-500/10',
                        };
                    @endphp

                    <div wire:key="map-{{ $map->id }}" class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <button
                            type="button"
                            wire:click="delete({{ $map->id }})"
                            wire:confirm="Deze map verwijderen?"
                            class="absolute right-2 top-2 z-10 hidden h-7 w-7 items-center justify-center rounded-full bg-white text-gray-400 shadow-sm hover:text-red-600 group-hover:flex"
                            aria-label="Map verwijderen"
                        >
                            &times;
                        </button>

                        <a href="{{ route('admin.maps.edit', $map) }}" wire:navigate class="block">
                            <div class="flex h-28 items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 text-xs font-medium text-gray-400">
                                {{ $map->width }} &times; {{ $map->height }} tegels
                            </div>

                            <div class="p-4">
                                <h3 class="truncate text-sm font-semibold text-gray-900">{{ $map->name }}</h3>
                                <span class="mt-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $statusStyles }}">
                                    {{ $map->status }}
                                </span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
