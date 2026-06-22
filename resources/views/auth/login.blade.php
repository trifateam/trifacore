<!DOCTYPE html>
<html lang="id" x-data="loginPage()" :class="{ 'dark': isDark }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — TriFaCore</title>
    <link rel="icon" type="image/png" href="{{ asset('images/trifaFarmRedesign.png') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        }

        /* Float animations */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(2deg);
            }
        }

        @keyframes float-slow {

            0%,
            100% {
                transform: translateY(0px) scale(1);
            }

            50% {
                transform: translateY(-10px) scale(1.03);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-float-slow {
            animation: float-slow 8s ease-in-out infinite;
        }

        /* Shimmer effect */
        .lp-shimmer {
            background: linear-gradient(90deg, #FFC72C 0%, #FFB800 25%, #FF9500 50%, #FFB800 75%, #FFC72C 100%);
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: shine 4s linear infinite;
        }

        @keyframes shine {
            to {
                background-position: 200% center;
            }
        }
    </style>
</head>

<body
    class="bg-gray-50 dark:bg-[#0f0f0f] text-gray-900 dark:text-gray-100 overflow-x-hidden min-h-screen flex items-stretch">

    {{-- Split Screen Layout --}}
    <div class="flex w-full">

        {{-- ═══════════════════════════════════════
        LEFT COLUMN: BRAND SHOWCASE (Desktop)
        ═══════════════════════════════════════ --}}
        <div
            class="relative hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#FFC72C] via-[#FFB800] to-[#FF9500] flex-col justify-between p-12 md:p-16 overflow-hidden">
            {{-- Decorative Blobs --}}
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] rounded-full bg-white/10 blur-3xl">
                </div>
                <div class="absolute bottom-[-15%] right-[-15%] w-[600px] h-[600px] rounded-full bg-white/10 blur-3xl">
                </div>
                <div class="absolute top-1/3 right-[-10%] w-[300px] h-[300px] rounded-full bg-white/5 blur-2xl"></div>
                {{-- Grid overlay --}}
                <div class="absolute inset-0 opacity-10"
                    style="background-image: radial-gradient(circle, white 1.5px, transparent 1.5px); background-size: 35px 35px;">
                </div>
            </div>

            {{-- Brand Header (Logo + Name) --}}
            <div class="relative z-10 flex items-center gap-2.5">
                <div
                    class="w-10 h-10 rounded-2xl bg-white/15 backdrop-blur-md flex items-center justify-center p-1.5 shadow-lg border border-white/20">
                    <img src="{{ asset('images/trifaFarmRedesign.png') }}"
                        class="w-full h-full object-contain filter brightness-0 invert" alt="Trifafarm Logo">
                </div>
                <span class="text-xl font-black tracking-wider text-white">TRIFACORE SYSTEM</span>
            </div>

            {{-- Central Illustration / Feature Card --}}
            <div class="relative z-10 my-auto flex flex-col items-center text-center">
                {{-- Floating main card --}}
                <div
                    class="bg-white/10 backdrop-blur-md border border-white/20 p-8 rounded-[2.5rem] shadow-2xl max-w-sm w-full mb-10 animate-float">
                    <div
                        class="w-20 h-20 rounded-3xl bg-white flex items-center justify-center mx-auto mb-6 shadow-xl p-3">
                        <img src="{{ asset('images/trifaFarmRedesign.png') }}" class="w-full h-full object-contain"
                            alt="Trifafarm Logo">
                    </div>
                    <h3 class="text-white text-2xl font-black leading-snug">Kelola Peternakan Secara Modern</h3>
                    <p class="text-white/80 text-sm mt-3 leading-relaxed">
                        Sistem manajemen operasional harian terintegrasi untuk pemantauan produksi telur, stok gudang,
                        keuangan, dan performa kandang secara real-time.
                    </p>
                </div>

                {{-- Mini stats badges --}}
                <div class="flex items-center gap-4 justify-center">
                    <div
                        class="bg-white/15 backdrop-blur-md border border-white/10 px-4 py-2.5 rounded-2xl text-white shadow-lg flex items-center gap-2">
                        <span class="text-xl">🥚</span>
                        <div class="text-left">
                            <div class="text-[10px] text-white/60 font-semibold uppercase leading-none">Produksi</div>
                            <div class="text-xs font-bold mt-0.5">5K+ / Hari</div>
                            <div class="text-xs font-bold mt-0.5">10K+ / Hari</div>
                        </div>
                    </div>
                    <div
                        class="bg-white/15 backdrop-blur-md border border-white/10 px-4 py-2.5 rounded-2xl text-white shadow-lg flex items-center gap-2">
                        <span class="text-xl">📊</span>
                        <div class="text-left">
                            <div class="text-[10px] text-white/60 font-semibold uppercase leading-none">Analitik</div>
                            <div class="text-xs font-bold mt-0.5">Real-time</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer info --}}
            <div class="relative z-10 text-white/70 text-xs font-medium">
                &copy; {{ date('Y') }} Trifafarm. Ketahanan Pangan Modern Sumatera Barat.
            </div>
        </div>

        {{-- ═══════════════════════════════════════
        RIGHT COLUMN: LOGIN FORM
        ═══════════════════════════════════════ --}}
        <div
            class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 md:p-20 relative bg-white dark:bg-[#0f0f0f] transition-colors duration-300">

            {{-- Navigation / Mode Actions --}}
            <div class="absolute top-6 left-6 z-20">
                <a href="/"
                    class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-[#FFC72C] dark:text-gray-400 dark:hover:text-[#FFC72C] transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <div class="absolute top-6 right-6 z-20 flex items-center gap-3">
                {{-- Dark Mode Toggle --}}
                <button @click="toggleDark()"
                    class="w-10 h-10 rounded-xl bg-gray-50 hover:bg-gray-100 dark:bg-white/5 dark:hover:bg-white/10 flex items-center justify-center transition-all duration-300"
                    aria-label="Toggle dark mode">
                    <svg x-show="!isDark" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg x-show="isDark" x-cloak class="w-5 h-5 text-[#FFC72C]" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>
            </div>

            {{-- Form Wrapper --}}
            <div class="max-w-md w-full relative z-10">
                {{-- Brand Header (Mobile) --}}
                <div class="flex lg:hidden items-center gap-2 mb-8 justify-center">
                    <div
                        class="w-8 h-8 rounded-xl bg-[#FFC72C]/10 flex items-center justify-center p-1 border border-[#FFC72C]/20">
                        <img src="{{ asset('images/trifaFarmRedesign.png') }}" class="w-full h-full object-contain"
                            alt="Trifafarm Logo">
                    </div>
                    <span class="text-base font-extrabold tracking-tight lp-shimmer">TRIFACOR SYSTEM</span>
                </div>

                {{-- Greeting --}}
                <div class="mb-8 text-center lg:text-left">
                    <span class="text-xs font-bold text-[#FFC72C] uppercase tracking-wider">Selamat Datang</span>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mt-1">Masuk ke Sistem</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Gunakan akun pegawai Anda untuk mengakses
                        dashboard.</p>
                </div>

                {{-- Alert Errors --}}
                @if ($errors->any())
                    <div
                        class="mb-6 p-4 rounded-2xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900/50 text-red-700 dark:text-red-400">
                        <div class="flex items-center gap-2 mb-1.5">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-sm font-bold">Kredensial Salah</span>
                        </div>
                        @foreach ($errors->all() as $error)
                            <p class="text-xs font-medium pl-7">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Username Field --}}
                    <div>
                        <label for="username"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Username</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-[#1a1a1a]/50 focus:bg-white dark:focus:bg-[#222] focus:ring-2 focus:ring-[#FFC72C] focus:border-transparent outline-none transition-all w-full text-sm text-gray-800 dark:text-gray-100"
                                placeholder="Masukkan username Anda" required autofocus>
                        </div>
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password"
                                class="text-sm font-semibold text-gray-700 dark:text-gray-300">Password</label>
                            <a href="#"
                                class="text-xs font-bold text-gray-400 hover:text-[#FFC72C] dark:hover:text-[#FFC72C] transition-colors">Lupa
                                Password?</a>
                        </div>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password"
                                class="w-full pl-11 pr-4 py-3 rounded-2xl border border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-[#1a1a1a]/50 focus:bg-white dark:focus:bg-[#222] focus:ring-2 focus:ring-[#FFC72C] focus:border-transparent outline-none transition-all w-full text-sm text-gray-800 dark:text-gray-100"
                                placeholder="••••••••" required>
                        </div>
                    </div>

                    {{-- Remember Me & Options --}}
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="h-4.5 w-4.5 rounded-lg text-[#FFC72C] focus:ring-[#FFC72C] border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-[#1a1a1a]">
                        <label for="remember" class="ml-2.5 text-sm text-gray-500 dark:text-gray-400 font-medium">Ingat
                            saya untuk 30 hari ke depan</label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="w-full py-3.5 bg-[#FFC72C] hover:bg-[#FFB800] text-white font-bold rounded-2xl shadow-lg shadow-[#FFC72C]/20 hover:shadow-xl hover:shadow-[#FFC72C]/35 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Masuk ke Dashboard
                    </button>
                </form>

                {{-- Back Helper (Register) --}}
                <div class="mt-8 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                    Belum memiliki kredensial login?
                    <a href="{{ route('register') }}" class="text-[#FFC72C] hover:underline font-bold">Daftar Akun
                        Baru</a>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════
    ALPINE.JS SCRIPTS
    ═══════════════════════════════════════ --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loginPage', () => ({
                isDark: false,

                init() {
                    // Synchronize dark mode from local storage
                    const stored = localStorage.getItem('darkMode');
                    if (stored !== null) {
                        this.isDark = stored === 'true';
                    } else {
                        this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }
                },

                toggleDark() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('darkMode', this.isDark);
                }
            }));
        });
    </script>
</body>

</html>