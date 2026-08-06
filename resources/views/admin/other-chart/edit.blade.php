@extends('admin.layout.master')

@section('content')

<div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4">Add Other Chart</h2>
    <form method="POST" action="{{ route('other.chart.update', $otherChart->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Khailwal Name</label>
            <input type="text" id="name" name="khaiwal_name" value="{{ $otherChart->khaiwal_name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Whatsapp Number </label>
            <input type="text" id="name" name="whatsapp_number" value="{{ $otherChart->whatsapp_numbers }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chart</label>
            <textarea id="chart" name="chart_content" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">  {!! $otherChart->chart_content !!}</textarea>

        </div>
        <div class="flex justify-end">
            <button type="button" id="closePopup" class="mr-2 text-gray-700 dark:text-gray-300 hover:text-gray-500">Cancel</button>
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Save</button>
        </div>
    </form>
</div>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<!-- Initialize CKEditor -->
<script>
    CKEDITOR.replace('chart', {
        // Optional configuration
        height: 200,
        // Add any other configuration options you need
    });
</script>
@endsection
