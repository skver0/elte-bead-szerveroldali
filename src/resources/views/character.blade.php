<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Character
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold">{{ $character->name }}</h2>
                            <p class="text-sm">Defence: {{ $character->defence }}</p>
                            <p class="text-sm">Strength: {{ $character->strength }}</p>
                            <p class="text-sm">Accuracy: {{ $character->accuracy }}</p>
                            <p class="text-sm">Magic: {{ $character->magic }}</p>
                        </div>
                        <div class="flex flex-col">
                            <a href="{{ route('dashboard') }}" class="text-blue-500">Back</a>
                            <!-- edit, delete -->
                            <a href="{{ route('character.edit', $character->id) }}" class="text-blue-500">Edit</a>
                            <form action="{{ route('character.destroy', $character->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500">Delete</button>
                            </form>
                        </div>
                    </div>
                    <div class="mt-5">
                        <h3 class="text-lg font-semibold">Matches</h3>
                        <ul class="mt-2">
                            @foreach ($matches as $match)
                                <li class="flex items-center justify-between mb-2">
                                    <div>
                                        <p class="text-sm">Place: {{ $match->place->name }}</p>
                                        <p class="text-sm">Enemy: {{ $match->enemy->name ?? 'REMOVED' }}</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('match', $match->id) }}" class="text-blue-500">View</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
</x-app-layout>
