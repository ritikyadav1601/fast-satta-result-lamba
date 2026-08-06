@extends('admin.layout.master')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex justify-between">
        <div class="flex items-center justify-between p-4">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Games List With Time</h5>
        </div>

        <div class="flex items-center justify-between p-4">
            <!-- Trigger the modal with this button -->
            <button id="openPopup" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Create
            </button>
        </div>
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Game Name</th>
                <th scope="col" class="px-6 py-3">Time</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($games as $game )
                
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $game->name }}
                </th>
                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($game->time)->format('g:i A') }}</td>

                <td class="px-6 py-4">
                    <a href="{{ route('games.edit', $game->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="popup" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Create Game</h2>
        <form method="POST" action="{{ route('games.store') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Game Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">English Game Name</label>
                <input type="text" id="name" name="english_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                <input type="time" id="time" name="time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Result Time</label>
                <input type="time" id="result_time" name="result_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
            </div>
            @if($otherChart->count() > 0)
            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Enter In Other Chart</label>
                <select name="other_chart_id" id="other_chart" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                    <option value="">Select Other Chart</option>
                    @foreach ($otherChart as $other)
                        <option value="{{ $other->id }}">{{ $other->khaiwal_name }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="flex justify-end">
                <button type="button" id="closePopup" class="mr-2 text-gray-700 dark:text-gray-300 hover:text-gray-500">Cancel</button>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript to handle opening and closing the modal -->
<script>
    // Get the modal, open button, and close button
    const popup = document.getElementById('popup');
    const openPopupButton = document.getElementById('openPopup');
    const closePopupButton = document.getElementById('closePopup');

    // When the "Create" button is clicked, open the popup
    openPopupButton.addEventListener('click', function() {
        popup.classList.remove('hidden');
    });

    // When the "Cancel" button is clicked, close the popup
    closePopupButton.addEventListener('click', function() {
        popup.classList.add('hidden');
    });
</script>

@endsection
