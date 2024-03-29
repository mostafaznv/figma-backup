<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-card :label="__('Total Users')" :text="$users" />
                <x-card :label="__('Total Projects')" :text="$projects" />
                <x-card :label="__('Total Backups')" :text="$totalBackups" />
                <x-card :label="__('Total Available Backups')" :text="$totalAvailableBackups" />
                <x-card :label="__('Total Downloads')" :text="$totalDownloads" />
            </div>
        </div>
    </div>
</x-app-layout>
