<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="flex w-60 shrink-0 flex-col border-r border-slate-800 bg-slate-900">
    <div class="flex items-center gap-2 border-b border-slate-800 px-5 py-5">
        <a href="{{ route('dashboard') }}" wire:navigate>
            <x-application-logo class="h-8 w-8 fill-current text-emerald-400" />
        </a>
        <span class="text-sm font-semibold uppercase tracking-wide text-slate-400">Area 51 Admin</span>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4">
        <a
            href="{{ route('dashboard') }}"
            wire:navigate
            class="flex items-center rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
        >
            Dashboard
        </a>

        <a
            href="{{ route('admin.maps.index') }}"
            wire:navigate
            class="flex items-center rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.maps.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
        >
            Maps
        </a>

        <a
            href="{{ route('admin.tile-types.index') }}"
            wire:navigate
            class="flex items-center rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.tile-types.*') ? 'bg-emerald-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}"
        >
            Objecten
        </a>
    </nav>

    <div class="border-t border-slate-800 p-3">
        <x-dropdown align="left" width="48" open-up>
            <x-slot name="trigger">
                <button class="flex w-full items-center justify-between rounded-md px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                    <span class="truncate" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
                    <svg class="h-4 w-4 shrink-0 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-dropdown-link>

                <button wire:click="logout" class="w-full text-start">
                    <x-dropdown-link>
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </button>
            </x-slot>
        </x-dropdown>
    </div>
</aside>
