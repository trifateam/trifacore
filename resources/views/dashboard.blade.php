<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TriFaCore</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <h1 class="text-xl font-bold text-gray-800">TriFaCore</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">
                        {{ Auth::user()->nama_lengkap ?? Auth::user()->username }}
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded ml-1">
                            {{ Auth::user()->role }}
                        </span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:underline">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Selamat Datang, {{ Auth::user()->nama_lengkap ?? Auth::user()->username }}!</h2>
            <p class="text-gray-600">Anda login sebagai <strong>{{ Auth::user()->role }}</strong>.</p>
        </div>
    </main>
</body>
</html>
