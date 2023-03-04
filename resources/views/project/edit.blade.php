<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase pt-1">
                {{ $project->name }}
            </h2>

            <div>
                <a href="{{ route('projects.view', ['any_project' => $project->id]) }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">{{ __('View') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="post" action="{{ route('projects.update', ['any_project' => $project->id]) }}"
                  class="mt-6 space-y-6">
                @csrf
                @method('patch')

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
                                    :value="old('figma_id', $project->figma_id)"
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
                                    :value="old('name', $project->name)"
                                    autocomplete="name"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
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
                                        {{ old('is_active', $project->is_active) ? 'checked' : ''}}
                                    >

                                    <span class="ml-2 text-sm text-gray-600">{{ __('Is Active') }}</span>
                                </label>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="flex items-center gap-4 ml-4 lg:ml-0">
                    <x-primary-button>{{ __('Update') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
