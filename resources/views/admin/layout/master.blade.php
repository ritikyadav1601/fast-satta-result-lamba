<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

    <link rel="stylesheet" href={{asset('assets/scss/app.scss')}}>
    <link rel="stylesheet" href={{asset('assets/css/admin.css')}}>
    @vite('resources/css/app.css')
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>
<body class="bg-gray-100 dark:bg-gray-900">

    @include('admin.layout.header')

    <div class="flex flex-col md:flex-row">

        <!-- Sidebar - hidden on mobile -->
        <aside class=" md:w-1/4 lg:w-1/5 bg-white dark:bg-gray-800 hidden md:block">
            @include('admin.layout.sidebar')
        </aside>

        <!-- Content Area -->
        <div class="w-full md:w-3/4 lg:w-4/5 p-4">
            @yield('content')
        </div>

    </div>

    @include('admin.layout.footer')

    <script src="{{ asset('node_modules/flowbite/dist/flowbite.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>

</body>
</html>
