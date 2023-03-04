<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase pt-1 mr-2">
                    {{ $user->username }}
                </h2>

                <small class="text-gray-500">{{ strtoupper($user->created_at->format('Y-m-d H:i:s')) }}</small>
            </div>

            <div>
                <a href="{{ route('profile.edit', ['user' => $user->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">{{ __('Edit') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-900">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-card :label="__('Name')" :text="$user->name" />
                    <x-card :label="__('Email')" :text="$user->email" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
