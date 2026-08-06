<nav class="bg-white border-gray-200 dark:bg-gray-900">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">

        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Fast-Satta-Result</span>
    </a>
    <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
        <button type="button" class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
          <span class="sr-only">Open user menu</span>
          <img class="w-8 h-8 rounded-full" src="/docs/images/people/profile-picture-3.jpg" alt="user photo">
        </button>
        <!-- Dropdown menu -->
        <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
          <div class="px-4 py-3">
            <span class="block text-sm text-gray-900 dark:text-white">Fast-Satta-Result</span>
          </div>
          <ul class="py-2" aria-labelledby="user-menu-button">
{{--            <li>--}}
{{--              <a href="{{ route('admin.change.password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Change Password</a>--}}
{{--            </li>--}}
            <li>
              <a href="{{ route('admin.logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a>
            </li>
          </ul>
        </div>
        <button data-collapse-toggle="navbar-user" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-user" aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
          </svg>
      </button>
    </div>
    <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-user">

    </div>
    </div>
  </nav>

  <!-- Sidebar -->
<div id="mobile-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full bg-white dark:bg-gray-900 transform -translate-x-full transition-transform">
  <div class="p-4">
      <h2 class="text-2xl font-semibold dark:text-white">Fast-Satta-Result</h2>
      <ul class="space-y-2 font-medium">
        <li>
            <a href="{{ route('other.game.result') }}" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="ms-3">Game Result</span>
            </a>
        </li>
    </ul>
  </div>
</div>
<script>
  const sidebar = document.getElementById('mobile-sidebar');
  const sidebarToggleButton = document.querySelector('[data-collapse-toggle="navbar-user"]');

  sidebarToggleButton.addEventListener('click', () => {
      sidebar.classList.toggle('-translate-x-full');
  });
</script>

