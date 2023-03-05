<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase pt-1 mr-2">
                    {{ $project->name }}
                </h2>

                <small class="text-gray-500">{{ strtoupper($project->created_at->format('Y-m-d H:i:s')) }}</small>
            </div>

            <div>
                <a href="{{ route('projects.edit', ['any_project' => $project->id]) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">{{ __('Edit') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-gray-900">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-card :label="__('Figma ID')" :text="$project->figma_id" />
                    <x-card :label="__('Name')" :text="$project->name" />
                    <x-card :label="__('Slug')" :text="$project->slug" />
                    <x-card :label="__('Status')" :text="$project->is_active ? __('Enabled') : __('Disabled')" />

                </div>
            </div>


            @if($project->backups->count())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto relative sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                                <tr class="border-b">
                                    <th scope="col" class="pb-3 px-6 text-base">{{ __('Name') }}</th>

                                    <th scope="col" class="pb-3 px-6 text-base">
                                        <span>{{ __('Size') }}</span>
                                        <small>MB</small>
                                    </th>

                                    </th>

                                    <th scope="col" class="pb-3 px-6 text-base"></th>
                                    <th scope="col" class="pb-3 px-6 text-base">{{ __('Date') }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach ($project->backups as $backup)
                                    <tr
                                        class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100"
                                        @class(['border-b' => !$loop->last])
                                    >
                                        <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $history->year }}</th>
                                        <td class="py-4 px-6">{{ $backup->name }}</td>
                                        <td class="py-4 px-6">{{ byteToMb($backup->size) }}</td>
                                        <td class="py-4 px-6">
                                            <a href="{{ $backup->link }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" download>{{ __('Download') }}</a>
                                        </td>
                                        <td class="py-4 px-6">{{ $backup->created_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
