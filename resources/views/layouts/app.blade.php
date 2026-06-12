<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TriFaCore') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Prevent dark mode flash (runs before CSS/JS load) -->
    <script>
        if (localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-900 dark:text-gray-100" x-data="darkMode()">
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-20 bg-black/50 md:hidden" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <!-- Content Area -->
        <div class="flex-1 flex flex-col min-h-screen w-full md:ml-64 transition-all duration-300">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Main Content -->
            <main class="flex-1 p-6">
                @include('components.flash-message')

                @yield('content')
            </main>
        </div>
    </div>
    <!-- UI Polish Scripts -->
    <script src="{{ asset('js/form-handler.js') }}"></script>
    <script src="{{ asset('js/rupiah-formatter.js') }}"></script>
    @yield('scripts')
</body>
</html>
