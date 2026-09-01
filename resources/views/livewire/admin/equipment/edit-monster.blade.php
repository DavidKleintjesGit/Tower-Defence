<div>
    <header class="bg-white">
        <div class="flex items-center justify-between px-6 py-6">
            <div>
                <a href="{{ route('admin.equipment.index') }}" wire:navigate class="text-sm text-gray-500 hover:text-gray-800">&larr; Weapons & Monsters</a>
                <h2 class="mt-1 text-xl font-semibold text-gray-900">{{ $enemyType->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">Code: <code class="rounded bg-gray-100 px-1.5 py-0.5">{{ $enemyType->code }}</code></p>
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
                            src="data:image/svg+xml;base64,{{ base64_encode($enemyType->sprite) }}"
                            alt="{{ $enemyType->name }}"
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
                            <label class="block text-sm font-medium text-gray-700">Hit points</label>
                            <input type="number" step="1" wire:model="hp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('hp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Speed multiplier</label>
                            <input type="number" step="0.05" wire:model="speed_multiplier" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('speed_multiplier') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Bounty ($)</label>
                            <input type="number" wire:model="bounty" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('bounty') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Render scale</label>
                            <input type="number" step="0.05" wire:model="render_scale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 sm:text-sm">
                            @error('render_scale') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-900">Type</h3>
                    <p class="mt-1 text-xs text-gray-500">Determines which towers can hit this monster (see each weapon's Targets ground/air setting).</p>

                    <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition has-[:checked]:border-gray-400 has-[:checked]:bg-gray-100">
                            <input type="radio" value="ground" wire:model="domain" class="mt-0.5 h-4 w-4 border-gray-300 text-gray-700 focus:ring-gray-500">
                            <span>
                                <span class="block text-sm font-medium text-gray-900">Ground</span>
                                <span class="block text-xs text-gray-500">Walks the map's path.</span>
                            </span>
                        </label>

                        <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-gray-200 p-3 transition has-[:checked]:border-sky-400 has-[:checked]:bg-sky-50">
                            <input type="radio" value="air" wire:model="domain" class="mt-0.5 h-4 w-4 border-gray-300 text-sky-600 focus:ring-sky-500">
                            <span>
                                <span class="block text-sm font-medium text-gray-900">Air</span>
                                <span class="block text-xs text-gray-500">Only ground+air towers can hit it.</span>
                            </span>
                        </label>
                    </div>
                    @error('domain') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror

                    <label class="mt-4 flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 p-3 transition has-[:checked]:border-red-400 has-[:checked]:bg-red-50">
                        <input type="checkbox" wire:model="is_boss" class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                        <span class="text-sm font-medium text-gray-900">Is a boss</span>
                    </label>
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
