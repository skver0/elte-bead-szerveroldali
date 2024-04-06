<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Match
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-bold">Match</h1>
                    <p class="text-gray-600 dark:text-gray-200">Match details</p>
                    <div class="mt-5">
                        <h2 class="text-2xl font-bold">Character VS Enemy</h2>
                        <div class="flex items-center justify-between">
                            <div>
                                @if ($match->character)
                                    <h2 class="text-lg font-semibold">Character</h2>
                                    <p class="text-sm">Name: {{ $match->character->name }}</p>
                                    <p class="text-sm">Defence: {{ $match->character->defence }}</p>
                                    <p class="text-sm">Strength: {{ $match->character->strength }}</p>
                                    <p class="text-sm">Accuracy: {{ $match->character->accuracy }}</p>
                                    <p class="text-sm">Magic: {{ $match->character->magic }}</p>
                                @else
                                    <p class="text-sm">Character has been removed</p>
                                @endif
                            </div>
                            <div>
                                @if ($match->enemy)
                                    <h2 class="text-lg font-semibold">Enemy</h2>
                                    <p class="text-sm">Name: {{ $match->enemy->name }}</p>
                                    <p class="text-sm">Defence: {{ $match->enemy->defence }}</p>
                                    <p class="text-sm">Strength: {{ $match->enemy->strength }}</p>
                                    <p class="text-sm">Accuracy: {{ $match->enemy->accuracy }}</p>
                                    <p class="text-sm">Magic: {{ $match->enemy->magic }}</p>
                                @else
                                    <p class="text-sm">Enemy has been removed</p>
                                @endif
                            </div>
                        </div>
                    </div>
</x-app-layout>
