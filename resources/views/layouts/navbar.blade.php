<header class="sticky top-0 z-20 bg-white dark:bg-gray-800 shadow-sm dark:shadow-gray-900/30 h-14 flex items-center justify-between px-4 sm:px-5 border-b border-gray-200 dark:border-gray-700/60 dark:border-gray-700/60">
    <div class="flex items-center gap-3">
        <a href="/dashboard" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:linear-gradient(135deg,#f59e0b,#d97706);box-shadow:0 2px 8px rgba(245,158,11,0.3);">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <span class="text-[15px] font-bold tracking-tight text-gray-800 dark:text-gray-100 hidden sm:inline">Tri<span class="text-amber-500">Fa</span>Core</span>
        </a>
    </div>
    <div class="flex items-center gap-2 sm:gap-3">
        <button @click="toggle()" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-700 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-200 transition-colors cursor-pointer" :title="isDark ? 'Light Mode' : 'Dark Mode'">
            <svg x-show="isDark" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
            <svg x-show="!isDark" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
        </button>
        <span class="hidden md:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase" style="background-color:rgba(255,200,0,0.2);color:#ff9900;">{{ str_replace('_', ' ', auth()->user()->role ?? 'Guest') }}</span>
        <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 text-sm focus:outline-none cursor-pointer">
                <span class="font-medium text-gray-700 dark:text-gray-200 hidden sm:inline">{{ auth()->user()->name ?? 'User' }}</span>
                <div class="h-8 w-8 rounded-full flex items-center justify-center text-white font-bold uppercase" style="background-color:#ff9900;">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</div>
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 ring-1 ring-black/5 dark:ring-white/10 z-50" style="display:none;">
                <div class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700 md:hidden">Role: <span class="uppercase font-medium" style="color:#ff9900;">{{ str_replace('_', ' ', auth()->user()->role ?? 'Guest') }}</span></div>
                <a href="/pengaturan/profil-sistem" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-700">Profil & Sistem</a>
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-700">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>
