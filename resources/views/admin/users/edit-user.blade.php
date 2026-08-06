@extends('admin.layout.master')

@section('content')

<div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold mb-4">Edit Game</h2>
    <form method="POST" action="{{ route('admin.user.store') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $user?->id }}">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Name</label>
            <input type="text" id="name" name="name" value="{{ $user?->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
        </div>

        <div class="mb-4">
            <label for="result_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
            <input type="text" id="result_time" name="email" value="{{ $user?->email }}"  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white">
        </div>

        <div class="flex justify-end">
            <a href="{{ route('admin.home') }}" class="mr-2 p-2 text-gray-700 dark:text-gray-300 hover:text-gray-500">Back</a>
            <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">Save</button>
        </div>
    </form>
</div>

@endsection
