<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TrifaFarm') }}</title>

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
    <div class="min-h-screen">
        
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Mobile Bottom Navbar -->
        @include('layouts.bottom-navbar')

        <!-- Content Area -->
        <div class="flex flex-col min-h-screen ml-0 md:ml-20 transition-all duration-300 pb-20 md:pb-0">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Main Content -->
            <main class="flex-1 p-4 md:p-6">
                @include('components.flash-message')

                @yield('content')
            </main>
        </div>
    </div>
    <!-- UI Polish Scripts -->
    <script src="{{ asset('js/form-handler.js') }}"></script>
    <script src="{{ asset('js/rupiah-formatter.js') }}"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
