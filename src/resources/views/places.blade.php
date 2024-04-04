<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Places
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach ($places as $place)
                <div class="mb-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold">{{ $place->name }}</h2>
                                <img src="{{ $place->image }}" alt="{{ $place->name }}"
                                    class="w-96 h-48 object-cover mt-2">
                            </div>
                            <div class="flex flex-col">
                                <a href="{{ route('places.edit', $place->id) }}" class="text-blue-500">Edit</a>
                                <form action="{{ route('places.destroy', $place->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
</x-app-layout>
