<div>
    <header class="bg-white">
        <div class="flex items-center justify-between px-6 py-6">
            <div>
                <a href="{{ route('admin.equipment.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-800">&larr; Weapons & Monsters</a>
                <h2 class="mt-1 text-xl font-semibold text-gray-900">{{ $towerType->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">Code: <code class="rounded bg-gray-100 px-1.5 py-0.5">{{ $towerType->code }}</code></p>
            </div>

            @if ($justSaved)
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                    Saved &#10003;
                </span>
            @endif
        </div>
    </header>

    <div class="px-6 py-6">
        <form wire:submit="save" class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-1">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <div class="flex aspect-square w-full items-center justify-center rounded-lg bg-gradient-to-br from-gray-50 to-gray-100 p-6">
                        <img
                            src="data:image/svg+xml;base64,{{ base64_encode($towerType->sprite) }}"
                            alt="{{ $towerType->name }}"
                            class="h-full w-full object-contain"
                            style="image-rendering: pixelated"
                        >
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">Identity</h3>

                    <div class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tagline</label>
                            <input type="text" wire:model="tagline" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('tagline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm"></textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">Combat stats</h3>

                    <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Damage</label>
                            <input type="number" step="0.1" wire:model="damage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('damage') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Range (tiles)</label>
                            <input type="number" step="0.1" wire:model="range_tiles" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('range_tiles') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fire interval (sec)</label>
                            <input type="number" step="0.01" wire:model="fire_interval" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('fire_interval') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cost</label>
                            <input type="number" wire:model="cost" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('cost') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Render scale</label>
                            <input type="number" step="0.05" wire:model="render_scale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('render_scale') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">Attack type</h3>
                    <p class="mt-1 text-xs text-gray-500">How this weapon fires and what it's allowed to hit.</p>

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition has-[:checked]:border-orange-400 has-[:checked]:bg-orange-50">
                            <input type="checkbox" wire:model="splash_damage" class="mt-0.5 h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                            <span>
                                <span class="block text-sm font-medium text-gray-900">Splash damage</span>
                                <span class="block text-xs text-gray-500">Damages every enemy in a blast radius around the impact, not just the direct hit.</span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition has-[:checked]:border-violet-400 has-[:checked]:bg-violet-50">
                            <input type="checkbox" wire:model="multi_target" class="mt-0.5 h-4 w-4 rounded border-gray-300 text-violet-600 focus:ring-violet-500">
                            <span>
                                <span class="block text-sm font-medium text-gray-900">Multi-target</span>
                                <span class="block text-xs text-gray-500">Strikes up to 3 of the nearest enemies in range at once instead of a single target.</span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition has-[:checked]:border-gray-400 has-[:checked]:bg-gray-100">
                            <input type="checkbox" wire:model="targets_ground" class="mt-0.5 h-4 w-4 rounded border-gray-300 text-gray-700 focus:ring-gray-500">
                            <span>
                                <span class="block text-sm font-medium text-gray-900">Targets ground</span>
                                <span class="block text-xs text-gray-500">Can fire at ground-based enemies.</span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition has-[:checked]:border-sky-400 has-[:checked]:bg-sky-50">
                            <input type="checkbox" wire:model="targets_air" class="mt-0.5 h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                            <span>
                                <span class="block text-sm font-medium text-gray-900">Targets air</span>
                                <span class="block text-xs text-gray-500">Can fire at flying enemies.</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700">
                        Save
                    </button>
                    <span wire:loading wire:target="save" class="text-sm text-gray-400">Saving...</span>
                </div>
            </div>
        </form>
    </div>
</div>
