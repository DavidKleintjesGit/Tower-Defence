<div class="min-h-screen bg-slate-950 px-6 py-8 text-slate-200">
    <div class="mx-auto max-w-5xl">
        <div class="mb-6">
            <h1 class="text-lg font-bold uppercase tracking-widest text-emerald-400">Objectenbeheer</h1>
            <p class="mt-1 text-sm text-slate-400">Stel per object de grootte in (klein tot groot) en upload eventueel je eigen afbeelding. Wijzigingen zijn direct zichtbaar in de mapbuilder.</p>
        </div>

        <div class="mb-8 rounded-md border border-slate-800 bg-slate-900 p-4">
            <h2 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">Nieuw object toevoegen</h2>

            <form wire:submit="createTileType" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-medium text-slate-400">Naam</label>
                    <input wire:model="newLabel" type="text" class="mt-1 block w-40 rounded-md border-slate-600 bg-slate-800 text-sm text-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('newLabel') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-400">Code (uniek)</label>
                    <input wire:model="newCode" type="text" placeholder="bijv-nieuwe-code" class="mt-1 block w-40 rounded-md border-slate-600 bg-slate-800 text-sm text-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                    @error('newCode') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="flex items-center gap-2 text-xs font-medium text-slate-400">
                        <input wire:model="newIsLarge" type="checkbox" class="rounded border-slate-600 bg-slate-800 text-emerald-500 focus:ring-emerald-500">
                        Groot object (2x2 tegels)
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-400">Afbeelding</label>
                    <input wire:model="newImage" type="file" accept="image/*" class="mt-1 block w-48 text-xs text-slate-400 file:mr-2 file:rounded file:border-0 file:bg-slate-700 file:px-2 file:py-1 file:text-xs file:text-slate-200">
                    @error('newImage') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled" class="rounded-md bg-emerald-600 px-4 py-2 text-xs font-medium text-white transition hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-50">
                    Toevoegen
                </button>
            </form>
        </div>

        @foreach ($grouped as $category => $tiles)
            <div class="mb-8">
                <h2 class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    {{ match ($category) {
                        'ground' => 'Terrain',
                        'road' => 'Wegen',
                        'fence' => 'Hekken',
                        'decoration' => 'Objecten & Props',
                        default => ucfirst($category),
                    } }}
                </h2>

                <div class="space-y-2">
                    @foreach ($tiles as $tile)
                        @php $currentStep = $this->stepForScale((float) $tile->render_scale); @endphp
                        <div class="flex items-center gap-4 rounded-md border border-slate-800 bg-slate-900 p-3">
                            <div
                                class="h-14 w-14 shrink-0 rounded-md border border-slate-700 bg-slate-800 bg-contain bg-center bg-no-repeat"
                                style="background-image: url('{{ $tile->spriteUrl() }}')"
                            ></div>

                            <div class="w-48 shrink-0">
                                <p class="text-sm font-medium text-slate-100">{{ $tile->label }}</p>
                                <p class="text-xs text-slate-500">{{ $tile->code }}</p>
                                @if ($tile->image_path)
                                    <button type="button" wire:click="clearImage('{{ $tile->code }}')" class="mt-1 text-[11px] text-red-400 hover:text-red-300">
                                        Eigen afbeelding verwijderen
                                    </button>
                                @endif
                            </div>

                            <div class="flex w-72 shrink-0 gap-1">
                                @if (in_array($category, ['ground', 'road']))
                                    <span class="flex items-center text-xs italic text-slate-500">Vaste grootte &mdash; altijd 1 tegel</span>
                                @else
                                    @foreach ($sizeSteps as $step => $data)
                                        <button
                                            type="button"
                                            wire:click="setScale('{{ $tile->code }}', {{ $step }})"
                                            class="flex h-9 w-9 items-center justify-center rounded-md border text-[10px] font-semibold transition {{ $step === $currentStep ? 'border-emerald-400 bg-emerald-500/20 text-emerald-300' : 'border-slate-700 bg-slate-800 text-slate-400 hover:border-slate-500' }}"
                                            title="{{ $data['label'] }} ({{ $data['scale'] }}x)"
                                        >
                                            {{ $data['label'] }}
                                        </button>
                                    @endforeach
                                @endif
                            </div>

                            <div class="flex flex-1 items-center justify-end gap-2">
                                <input
                                    type="file"
                                    wire:model="uploads.{{ $tile->code }}"
                                    accept="image/*"
                                    class="block w-40 text-xs text-slate-400 file:mr-2 file:rounded file:border-0 file:bg-slate-700 file:px-2 file:py-1 file:text-xs file:text-slate-200"
                                >
                                <button
                                    type="button"
                                    wire:click="saveUpload('{{ $tile->code }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="uploads.{{ $tile->code }}, saveUpload('{{ $tile->code }}')"
                                    class="rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-emerald-500 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    Upload
                                </button>
                                @error("uploads.{$tile->code}")
                                    <span class="text-[11px] text-red-400">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
