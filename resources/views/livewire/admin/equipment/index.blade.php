<div>
    <header class="bg-white">
        <div class="px-6 py-6">
            <h2 class="text-xl font-semibold text-gray-900">Weapons & Monsters</h2>
            <p class="mt-1 text-sm text-gray-500">Manage stats and combat behavior for every weapon and monster. Click a card to edit it.</p>

            <div class="mt-5 flex gap-1 border-b border-gray-200">
                <button
                    type="button"
                    wire:click="setTab('weapons')"
                    class="border-b-2 px-4 py-2 text-sm font-medium transition {{ $tab === 'weapons' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
                >
                    Weapons ({{ $towerTypes->count() }})
                </button>
                <button
                    type="button"
                    wire:click="setTab('monsters')"
                    class="border-b-2 px-4 py-2 text-sm font-medium transition {{ $tab === 'monsters' ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
                >
                    Monsters ({{ $enemyTypes->count() }})
                </button>
            </div>
        </div>
    </header>

    <div class="px-6 py-6">
        @if ($tab === 'weapons')
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($towerTypes as $tower)
                    <a
                        href="{{ route('admin.equipment.weapon.edit', $tower) }}"
                        wire:navigate
                        wire:key="tower-{{ $tower->id }}"
                        class="group block overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <div class="relative flex h-32 items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 p-4">
                            <img
                                src="data:image/svg+xml;base64,{{ base64_encode($tower->sprite) }}"
                                alt="{{ $tower->name }}"
                                class="h-full w-full object-contain transition group-hover:scale-110"
                                style="image-rendering: pixelated"
                            >

                            <div class="absolute right-2 top-2 flex flex-col items-end gap-1">
                                @if ($tower->splash_damage)
                                    <span class="rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-semibold text-orange-700">Splash</span>
                                @endif
                                @if ($tower->multi_target)
                                    <span class="rounded-full bg-violet-100 px-2 py-0.5 text-[10px] font-semibold text-violet-700">Multi</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4">
                            <h3 class="truncate text-sm font-semibold text-gray-900">{{ $tower->name }}</h3>
                            <p class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                                <span>&#9876;&#65039; {{ $tower->damage }}</span>
                                <span>&#128204; {{ $tower->range_tiles }}</span>
                                <span class="font-medium text-yellow-600">&#36;{{ $tower->cost }}</span>
                            </p>
                            <p class="mt-2 flex items-center gap-1.5 text-[11px] font-medium text-gray-400">
                                @if ($tower->targets_ground)
                                    <span class="rounded bg-gray-100 px-1.5 py-0.5 text-gray-600">Ground</span>
                                @endif
                                @if ($tower->targets_air)
                                    <span class="rounded bg-sky-100 px-1.5 py-0.5 text-sky-700">Air</span>
                                @endif
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($enemyTypes as $enemy)
                    <a
                        href="{{ route('admin.equipment.monster.edit', $enemy) }}"
                        wire:navigate
                        wire:key="enemy-{{ $enemy->id }}"
                        class="group relative block overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md"
                    >
                        <div class="absolute left-2 top-2 z-10 flex flex-col gap-1">
                            @if ($enemy->is_boss)
                                <span class="rounded-full bg-red-600 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Boss</span>
                            @endif
                            <span class="rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $enemy->domain === 'air' ? 'bg-sky-100 text-sky-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($enemy->domain) }}
                            </span>
                        </div>

                        <div class="flex h-32 items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 p-4">
                            <img
                                src="data:image/svg+xml;base64,{{ base64_encode($enemy->sprite) }}"
                                alt="{{ $enemy->name }}"
                                class="h-full w-full object-contain transition group-hover:scale-110"
                                style="image-rendering: pixelated"
                            >
                        </div>

                        <div class="p-4">
                            <h3 class="truncate text-sm font-semibold text-gray-900">{{ $enemy->name }}</h3>
                            <p class="mt-2 flex items-center gap-3 text-xs text-gray-500">
                                <span>&#10084;&#65039; {{ $enemy->hp }}</span>
                                <span>&#127939; {{ $enemy->speed_multiplier }}x</span>
                                <span class="font-medium text-yellow-600">&#36;{{ $enemy->bounty }}</span>
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
