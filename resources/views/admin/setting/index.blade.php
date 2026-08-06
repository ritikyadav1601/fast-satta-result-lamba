@extends('admin.layout.master')

@section('content')

<form action="{{ route('setting.update') }}" method="POST" class="w-full mx-auto">
    @csrf
    <h2 class="text-2xl font-bold mb-5">Settings</h2>
    <div class="flex flex-col sm:flex-row mb-5">
        <label for="khaiwal_name" class="block mb-2 sm:mb-0 text-sm font-medium text-gray-900 dark:text-white sm:w-1/4">Owner Name</label>
        <input type="text" name="owner_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-3/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name" value="{{ $settings['owner_name'] ?? '' }}" required />
    </div>
    <div class="flex flex-col sm:flex-row mb-5">
        <label for="khaiwal_name" class="block mb-2 sm:mb-0 text-sm font-medium text-gray-900 dark:text-white sm:w-1/4">Owner Mobile Number</label>
        <input type="text" name="owner_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-3/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="number" value="{{ $settings['owner_number'] ?? '' }}" required />
    </div>
    <div class="flex flex-col sm:flex-row mb-5">
      <label for="khaiwal_name" class="block mb-2 sm:mb-0 text-sm font-medium text-gray-900 dark:text-white sm:w-1/4">Khaiwal Name</label>
      <input type="text" name="khaiwal_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-3/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name" value="{{ $settings['khaiwal_name'] ?? '' }}" required />
    </div>

    <div class="flex flex-col sm:flex-row mb-5">
        <label for="website_name" class="block mb-2 sm:mb-0 text-sm font-medium text-gray-900 dark:text-white sm:w-1/4">Website Name</label>
        <input type="text" name="website_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-3/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="fast-satta-result" value="{{ $settings['website_name'] ?? '' }}" required />
    </div>

    <div class="flex flex-col sm:flex-row mb-5">
        <label for="home_page_float_text" class="block mb-2 sm:mb-0 text-sm font-medium text-gray-900 dark:text-white sm:w-1/4">Home Page Float Text</label>
        <textarea type="text" name="home_page_float_text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-3/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="welcome to khaiwal" required rows="4"> {{ $settings['home_page_float_text'] ?? '' }}</textarea>
    </div>

    <div class="flex flex-col sm:flex-row mb-5">
        <label for="home_page_float_text" class="block mb-2 sm:mb-0 text-sm font-medium text-gray-900 dark:text-white sm:w-1/4">Secondary Page Float Text</label>
        <textarea type="text" name="secondary_page_float_text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-3/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="welcome to khaiwal" required rows="4"> {{ $settings['secondary_page_float_text'] ?? '' }}</textarea>
    </div>

    <div class="flex flex-col sm:flex-row mb-5">
        <label for="whatsapp_number" class="block mb-2 sm:mb-0 text-sm font-medium text-gray-900 dark:text-white sm:w-1/4">Whatsapp Number</label>
        <input type="text" name="whatsapp_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-3/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="512-345-678" value="{{ $settings['whatsapp_number'] ?? '' }}" required />
    </div>

    <div class="flex flex-col sm:flex-row mb-5">
        <label for="whatsapp_number" class="block mb-2 sm:mb-0 text-sm font-medium text-gray-900 dark:text-white sm:w-1/4">DISCLAIMER</label>
        <input type="text" name="disclaimer" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full sm:w-3/4 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="disclaimer" value="{{ $settings['disclaimer'] ?? '' }}" required />
    </div>

    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Update
    </button>

</form>

@endsection
