<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Character Edit
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('character', ['id' => $character->id]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-5">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                            <input type="text" name="name" id="name" value="{{ $character->name }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:focus:ring-indigo-200 dark:focus:ring-opacity-50 dark:focus:border-indigo-300 sm:text-sm"
                                required>
                        </div>
                        <div class="mb-5">
                            <label for="defence"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Defence</label>
                            <input type="number" name="defence" id="defence" value="{{ $character->defence }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:focus:ring-indigo-200 dark:focus:ring-opacity-50 dark:focus:border-indigo-300 sm:text-sm"
                                required>
                        </div>
                        <div class="mb-5">
                            <label for="strength"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Strength</label>
                            <input type="number" name="strength" id="strength" value="{{ $character->strength }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:focus:ring-indigo-200 dark:focus:ring-opacity-50 dark:focus:border-indigo-300 sm:text-sm"
                                required>
                        </div>
                        <div class="mb-5">
                            <label for="accuracy"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Accuracy</label>
                            <input type="number" name="accuracy" id="accuracy" value="{{ $character->accuracy }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:focus:ring-indigo-200 dark:focus:ring-opacity-50 dark:focus:border-indigo-300 sm:text-sm"
                                required>
                        </div>
                        <div class="mb-5">
                            <label for="magic"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">Magic</label>
                            <input type="number" name="magic" id="magic" value="{{ $character->magic }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 dark:focus:ring-indigo-200 dark:focus:ring-opacity-50 dark:focus:border-indigo-300 sm:text-sm"
                                required>
                        </div>
                        @if (Auth::user()->is_admin)
                            <div class="mb-5 flex">
                                <input {{ $character->enemy ? 'checked' : '' }} type="checkbox" name="enemy"
                                    id="enemy" class="mr-2">
                                <label for="enemy"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-200">Enemy</label>
                            </div>
                        @endif
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
                    </form>
                </div>
            </div>
</x-app-layout>
