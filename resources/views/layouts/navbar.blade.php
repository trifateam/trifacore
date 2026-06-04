<header class="sticky top-0 z-20 bg-white shadow-sm h-16 flex items-center justify-between px-4 sm:px-6">
    <!-- Left side -->
    <div class="flex items-center">
        <!-- Mobile menu button -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 focus:outline-none md:hidden mr-4">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <!-- Spacer / Breadcrumbs -->
        <div class="text-gray-500 text-sm hidden sm:block">
            <!-- Breadcrumbs space -->
        </div>
    </div>

    <!-- Right side (User Info) -->
    <div class="flex items-center space-x-4">
        <!-- Role Badge -->
        <span class="hidden md:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 uppercase">
            {{ str_replace('_', ' ', auth()->user()->role ?? 'Guest') }}
        </span>

        <!-- Profile dropdown -->
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 text-sm focus:outline-none">
                <span class="font-medium text-gray-700">{{ auth()->user()->name ?? 'User' }}</span>
                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold uppercase">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown menu -->
            <div x-show="dropdownOpen" 
                 @click.away="dropdownOpen = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                 style="display: none;">
                 
                <div class="px-4 py-2 text-xs text-gray-500 border-b border-gray-100 md:hidden">
                    Role: <span class="uppercase font-medium text-indigo-600">{{ str_replace('_', ' ', auth()->user()->role ?? 'Guest') }}</span>
                </div>

                <a href="/pengaturan/profil-sistem" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil & Sistem</a>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
