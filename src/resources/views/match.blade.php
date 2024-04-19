<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Match
        </h2>
    </x-slot>

    <div style="background-image: url('{{ $match->place->image }}'); background-size:cover;" class="py-12 h-[100vh]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-3xl font-bold">Match</h1>
                    <p class="text-gray-600 text-xl dark:text-gray-200">Place: {{ $match->place->name }}</p>
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
                                    <p class="text-sm">Health: {{ $match->character->hp }}</p>
                                    <!-- history -->
                                    <h2 class="text-lg font-semibold mt-5">History</h2>
                                    <ul class="list-disc list-inside">
                                        @if ($match->character->history)
                                            @foreach ($match->character->history as $history)
                                                <li>{{ $history }}</li>
                                            @endforeach
                                        @else
                                            <p class="text-sm">Character has no history</p>
                                        @endif
                                    </ul>
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
                                    <p class="text-sm">Health: {{ $match->enemy->hp }}</p>
                                    <h2 class="text-lg font-semibold mt-5">History</h2>
                                    <ul class="list-disc list-inside">
                                        @if ($match->enemy->history)
                                            @foreach ($match->enemy->history as $history)
                                                <li>{{ $history }}</li>
                                            @endforeach
                                        @else
                                            <p class="text-sm">Enemy has no history</p>
                                        @endif
                                    </ul>
                                @else
                                    <p class="text-sm">Enemy has been removed</p>
                                @endif
                            </div>
                        </div>
                        @if (!isset($match->win))
                            <!-- melee, ranged, special (magic) buttons -->
                            <div class="mt-5 mx-auto">
                                <form action="{{ route('match.attack', $match->id) }}" method="POST" novalidate>
                                    @csrf
                                    @method('POST')
                                    <button type="submit" name="attack" value="melee"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Melee</button>
                                    <button type="submit" name="attack" value="ranged"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ranged</button>
                                    <button type="submit" name="attack" value="special"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Special</button>
                                </form>
                            </div>
                        @else
                            <h2 class="text-2xl font-bold mt-5">Winner</h2>
                            @if ($match->win)
                                <p class="text-sm">Winner: {{ $match->character->name }}</p>
                            @else
                                <p class="text-sm">Winner: {{ $match->enemy->name }}</p>
                            @endif
                        @endif
                    </div>
</x-app-layout>
