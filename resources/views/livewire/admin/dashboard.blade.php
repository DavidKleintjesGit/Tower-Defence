<div>
    <header class="bg-white shadow">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>
        </div>
    </header>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 text-gray-900 shadow-sm sm:rounded-lg">
                You're logged into the admin environment.
            </div>

            <div class="overflow-hidden bg-white p-6 text-gray-900 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Maps</h3>
                <p class="mt-1 text-sm text-gray-600">Manage the playable maps for Area 51 Tower Defense.</p>
                <a
                    href="{{ route('admin.maps.index') }}"
                    wire:navigate
                    class="mt-4 inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-700"
                >
                    Go to Maps
                </a>
            </div>
        </div>
    </div>
</div>
