<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight pt-1">
                {{ __('Users') }}
            </h2>

            <div>
                <a href="{{ route('profile.add') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">{{ __('Add User') }}</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto relative sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                            <tr class="border-b">
                                <th scope="col" class="pb-3 px-6 text-base">{{ __('Name') }}</th>
                                <th scope="col" class="pb-3 px-6 text-base">{{ __('Email') }}</th>
                                <th scope="col" class="pb-3 px-6 text-base">{{ __('CreatedAt') }}</th>
                                <th scope="col" class="pb-3 px-6 text-base"></th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($users as $user)
                                <tr @class(['dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 bg-white'])>
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-800 whitespace-nowrap dark:text-white">
                                        <code>{{ $user->name }}</code>
                                    </th>

                                    <td class="py-4 px-6">{{ $user->email }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap">{{ strtoupper($user->created_at->format('Y-m-d')) }}</td>

                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('profile.view', ['user' => $user->id]) }}" class="inline-flex items-center justify-center mt-1 mb-1 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" style="min-width: 72px">{{ __('View') }}</a>
                                        <a href="{{ route('profile.edit', ['user' => $user->id]) }}" class="inline-flex items-center justify-center mt-1 mb-1 px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150" style="min-width: 72px">{{ __('Edit') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
