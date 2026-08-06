@extends('admin.layout.master')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex justify-between">
        <div class="flex items-center justify-between p-4">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Frequently Asked Questions</h5>
        </div>

        <div class="flex items-center justify-between p-4">
            <!-- Trigger the modal with this button -->
            <button id="openPopup" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Create
            </button>
        </div>
    </div>

    @if(Session::has('success'))
        <div class="flex p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
            <span class="font-medium">Success!</span> {{ Session::get('success') }}
        </div>
    @endif

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Question</th>
                <th scope="col" class="px-6 py-3">Answer</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ( $question as $q)

            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $q->question }}
                </th>
                <td class="px-6 py-4">{{ $q->answer }}</td>

                <!-- Add an input field and a button for updating the result -->
                <td class="px-6 py-4">
                   <a href="{{ route('admin.faq.edit', $q->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>
<div id="popup" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Create Game</h2>
        <!-- Form for creating a game -->
        <form method="POST" action="{{ route('faq.store') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Question</label>
                <input type="text" id="name" name="question" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Answer</label>
             <textarea id="time" rows="5" name="answer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white"></textarea>
            </div>

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
