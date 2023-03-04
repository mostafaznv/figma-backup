<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="overflow-x-auto relative sm:rounded-lg">
            @if($attributes->get('label'))
                <header class="mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ $attributes->get('label') }}</h2>
                </header>
            @endif

            <code class="text-red-600">{{ $attributes->get('text') }}</code>
            <small class="text-gray-500">{{ $attributes->get('sub') }}</small>
        </div>
    </div>
</div>
