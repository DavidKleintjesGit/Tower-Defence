<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-screen overflow-hidden font-sans antialiased">
        <div class="flex h-screen bg-gray-50">
            <livewire:layout.navigation />

            <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
                @if (isset($header))
                    <header class="shrink-0 border-b border-gray-200 bg-white">
                        <div class="px-6 py-6">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="min-h-0 flex-1 overflow-hidden">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
