<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @foreach ($characters as $character)
                <div class="mb-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items
                    -center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold">{{ $character->name }}</h2>
                                <p class="text-sm">Defence: {{ $character->defence }}</p>
                                <p class="text-sm">Strength: {{ $character->strength }}</p>
                                <p class="text-sm">Accuracy: {{ $character->accuracy }}</p>
                                <p class="text-sm">Magic: {{ $character->magic }}</p>
                            </div>
                            <div>
                                <a href="{{ route('character', $character->id) }}" class="text-blue-500">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
