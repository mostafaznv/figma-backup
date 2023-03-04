<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="post" action="{{ route('projects.create') }}" class="mt-6 space-y-6">
                @csrf

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Project Information') }}
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    {{ __('Figma project information.') }}
                                </p>
                            </header>

                            <div class="mt-4">
                                <x-input-label for="figma-id" :value="__('Figma ID')" />
                                <x-text-input
                                    id="figma-id"
                                    name="figma_id"
                                    type="text"
                                    class="mt-1 block w-full"
                                    autocomplete="figma_id"
                                    :value="old('figma_id')"
                                />

                                <x-input-error class="mt-2" :messages="$errors->get('figma_id')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input
                                    id="name"
                                    name="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :value="old('name')"
                                    autocomplete="name"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="slug" :value="__('Slug')" />
                                <x-text-input
                                    id="slug"
                                    name="slug"
                                    type="text"
                                    class="mt-1 block w-full"
                                    :value="old('slug')"
                                    autocomplete="slug"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('slug')" />
                            </div>

                            <div class="mt-4">
                                <label for="is-active" class="inline-flex items-center">
                                    <input type="hidden" name="is_active" value="0" />
                                    <input
                                        id="is-active"
                                        type="checkbox"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                        name="is_active"
                                        value="1"
                                    >

                                    <span class="ml-2 text-sm text-gray-600">{{ __('Is Active') }}</span>
                                </label>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="flex items-center gap-4 ml-4 lg:ml-0">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
