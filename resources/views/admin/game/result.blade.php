@extends('admin.layout.master')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="flex justify-between">
        <div class="flex items-center justify-between p-4">
            <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white">Update Game Result</h5>
        </div>
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">Game Name</th>
                <th scope="col" class="px-6 py-3">Result Time</th>
                <th scope="col" class="px-6 py-3">Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($games as $game)

            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $game->name }}
                </th>
                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($game->result_time)->format('g:i A') }}</td>

                <!-- Add an input field and a button for updating the result -->
                <td class="px-6 py-4">
                    <form action="{{ route('admin.game.result.update', $game->id)}}" method="POST" class="flex">
                        @csrf
                        @method('PUT')
                        <input type="text" name="result" value="{{ $game->gameResult?->first()?->result }}"  class="border rounded px-2 py-1 mr-2" placeholder="Enter result">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded hover:bg-blue-700">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
