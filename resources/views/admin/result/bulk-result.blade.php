@extends('admin.layout.master')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex justify-between">
        <div class="flex items-center justify-between p-4">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Add Bulk Result</h5>
        </div>

        <div class="flex items-center justify-between p-4">
        <button id="openPopup3" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
               Download Result
            </button>
        <button id="openPopup2" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
               Add Single Result
            </button>
            <button id="openPopup" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Single Game Result <span class="text-xs">Bulk</span>
            </button>
        </div>
    </div>
    <div class="mb-4 flex items-center space-x-2">
        <label for="filter_date" class="text-gray-700 font-semibold">Filter by Date:</label>
        <input type="date" id="filter_date" class="border border-gray-300 p-2 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
    </div>
    
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Game Name</th>
                <th scope="col" class="px-6 py-3">Date</th>
                <th scope="col" class="px-6 py-3">Result</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
                @foreach ($oldResult as $result)
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $result->game_name }}
                </th>
                <td class="px-6 py-4">{{ $result->result_date}}</td>
                <td class="px-6 py-4">{{ $result->result }}</td>
                <td class="px-6 py-4">
                    <div class="flex">
                    <a href="{{ route('bulk.result.edit', $result->id) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit</a>
                    <div>
                    <form action="{{ route('bulk.result.destroy', $result->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Delete</button>
                    </form> 
                    </div>
                </td>
            </tr>
            @empty($oldResult)
            <tr>
                <td colspan="4" class="px-6 py-4 text-center">No data found</td>
            </tr>
            @endempty
            @endforeach

        </tbody>
    </table>
    {{ $oldResult->links() }}
</div>

<!-- Modal -->
<div id="popup" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Add Game Result</h2>
        <form method="POST" action="{{ route('games.single.result') }}" enctype="multipart/form-data">
            @csrf
             <select name="game_id" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach ($games as $game )
                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                @endforeach
             </select>
             <input class="mt-2" type="file" name="csv_file" >

            <div class="flex justify-end">
                <button type="button" id="closePopup" class="mr-2 text-gray-700 dark:text-gray-300 hover:text-gray-500">Cancel</button>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div id="popup2" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Add Game Result</h2>
        <form method="POST" action="{{ route('games.add.result.old') }}" enctype="multipart/form-data">
            @csrf
             <select name="game_id" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach ($games as $game )
                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                @endforeach
             </select>
             <input class="mt-2" type="date" name="result_date">
             <input class="mt-2" type="text" name="result">

            <div class="flex justify-end">
                <button type="button" id="closePopup2" class="mr-2 text-gray-700 dark:text-gray-300 hover:text-gray-500">Cancel</button>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Add</button>
            </div>
        </form>
    </div>
</div>
<div id="popup3" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Add Game Result</h2>
        <form method="POST" action="{{ route('games.download.result.old') }}" enctype="multipart/form-data">
            @csrf
             <select name="game_id" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach ($games as $game )
                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                @endforeach
             </select>
             <input class="mt-2" type="date" name="start_date">
             <input class="mt-2" type="date" name="end_date">

            <div class="flex justify-end">
                <button type="button" id="closePopup3" class="mr-2 text-gray-700 dark:text-gray-300 hover:text-gray-500">Cancel</button>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Add</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.getElementById('filter_date').addEventListener('change', function() {
        let selectedDate = this.value;
        if (selectedDate) {
            let url = new URL(window.location.href);
            url.searchParams.set('date', selectedDate);
            window.location.href = url.toString();
        }
    });

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

    const popup2 = document.getElementById('popup2');
    const openPopupButton2 = document.getElementById('openPopup2');
    const closePopupButton2 = document.getElementById('closePopup2');

    // When the "Create" button is clicked, open the popup
    openPopupButton2.addEventListener('click', function() {
        popup2.classList.remove('hidden');
    });

    // When the "Cancel" button is clicked, close the popup
    closePopupButton2.addEventListener('click', function() {
        popup2.classList.add('hidden');
    });

    const popup3 = document.getElementById('popup3');
    const openPopupButton3 = document.getElementById('openPopup3');
    const closePopupButton3 = document.getElementById('closePopup3');

    // When the "Create" button is clicked, open the popup
    openPopupButton3.addEventListener('click', function() {
        popup3.classList.remove('hidden');
    });

    // When the "Cancel" button is clicked, close the popup
    closePopupButton3.addEventListener('click', function() {
        popup3.classList.add('hidden');
    });

</script>

@endsection
