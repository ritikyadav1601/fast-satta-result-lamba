@extends('admin.layout.master')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex justify-between">
        <div class="flex items-center justify-between p-4">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Extra Game Results</h5>
        </div>

        <div class="flex items-center justify-between p-4 space-x-2">
            @if(isset($lastSyncTime) && $lastSyncTime)
                <span class="text-sm text-gray-500 dark:text-gray-400 mr-2">Last Sync: {{ \Carbon\Carbon::parse($lastSyncTime)->format('M d, Y h:i A') }}</span>
            @endif
            <form id="syncForm" action="{{ route('admin.extra-game-result.sync-today') }}" method="POST" style="display: inline;">
                @csrf
                <button type="button" id="syncBtn" onclick="submitSyncForm()" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-green-500 dark:hover:bg-green-600 dark:focus:ring-green-800 flex items-center">
                    <svg id="syncSpinner" class="hidden w-4 h-4 mr-2 text-white animate-spin" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="syncText">Sync Today's Results</span>
                </button>
            </form>
            <button id="openPopup2" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
               Add Single Result
            </button>
            <button id="openPopup" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Upload CSV
            </button>
        </div>
    </div>
    <div class="mb-4 flex items-center space-x-2 p-4">
        <label for="filter_date" class="text-gray-700 font-semibold">Filter by Date:</label>
        <input type="date" id="filter_date" class="border border-gray-300 p-2 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
    </div>
    
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Extra Game Name</th>
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
                    <a href="{{ route('admin.extra-game-result.edit', $result->id) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit</a>
                    <div>
                    <form action="{{ route('admin.extra-game-result.destroy', $result->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
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
        <h2 class="text-2xl font-bold mb-4">Upload CSV Result</h2>
        <form method="POST" action="{{ route('admin.extra-game-result.csv') }}" enctype="multipart/form-data">
            @csrf
             <select name="extra_game_id" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach ($games as $game )
                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                @endforeach
             </select>
             <input class="mt-2" type="file" name="csv_file" required>

            <div class="flex justify-end mt-4">
                <button type="button" id="closePopup" class="mr-2 text-gray-700 dark:text-gray-300 hover:text-gray-500">Cancel</button>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div id="popup2" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-4">Add Single Result</h2>
        <form method="POST" action="{{ route('admin.extra-game-result.store') }}">
            @csrf
             <select name="extra_game_id" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach ($games as $game )
                    <option value="{{ $game->id }}">{{ $game->name }}</option>
                @endforeach
             </select>
             <input class="mt-2 w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" type="date" name="result_date" required>
             <input class="mt-2 w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" type="text" name="result" placeholder="Enter Result (e.g. 100 for --)" required>

            <div class="flex justify-end mt-4">
                <button type="button" id="closePopup2" class="mr-2 text-gray-700 dark:text-gray-300 hover:text-gray-500">Cancel</button>
                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Add</button>
            </div>
        </form>
    </div>
</div>

<script>
    function submitSyncForm() {
        if (confirm('Are you sure you want to run the automatic scraper? This will take ~30 seconds.')) {
            document.getElementById('syncBtn').disabled = true;
            document.getElementById('syncText').innerText = 'Processing...';
            document.getElementById('syncSpinner').classList.remove('hidden');
            document.getElementById('syncForm').submit();
        }
    }

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

    openPopupButton.addEventListener('click', function() {
        popup.classList.remove('hidden');
    });

    closePopupButton.addEventListener('click', function() {
        popup.classList.add('hidden');
    });

    const popup2 = document.getElementById('popup2');
    const openPopupButton2 = document.getElementById('openPopup2');
    const closePopupButton2 = document.getElementById('closePopup2');

    openPopupButton2.addEventListener('click', function() {
        popup2.classList.remove('hidden');
    });

    closePopupButton2.addEventListener('click', function() {
        popup2.classList.add('hidden');
    });
</script>

@endsection
