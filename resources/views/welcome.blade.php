<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="landingPage()" :class="{ 'dark': isDark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Trifafarm — Peternakan ayam petelur modern di Padang dengan produk telur segar berkualitas tinggi, ayam afkir, dan pupuk kandang berkualitas.">
    <title>Trifafarm — Telur & Produk Peternakan Berkualitas Tinggi dari Padang</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-white dark:bg-[#0f0f0f] text-gray-900 dark:text-gray-100 overflow-x-hidden">

    {{-- ═══════════════════════════════════════
         LOADING SCREEN
         ═══════════════════════════════════════ --}}
    <div x-show="loading" x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-white dark:bg-[#0f0f0f]" x-cloak>
        <div class="relative mb-6">
            <div class="w-16 h-16 rounded-full border-4 border-[#FFC72C]/20 border-t-[#FFC72C]" style="animation: loading-spin 1s linear infinite;"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                {{-- Egg icon --}}
                <svg class="w-7 h-7 text-[#FFC72C]" viewBox="0 0 24 24" fill="currentColor">
                    <ellipse cx="12" cy="13" rx="7" ry="9"/>
                </svg>
            </div>
        </div>
        <span class="text-2xl font-extrabold tracking-tight lp-shimmer">TRIFA FARM</span>
        <span class="mt-2 text-sm text-gray-400 dark:text-gray-500">Memuat halaman...</span>
    </div>

    {{-- ═══════════════════════════════════════
         STICKY NAVBAR
         ═══════════════════════════════════════ --}}
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
         :class="scrolled ? 'lp-glass shadow-lg shadow-black/5 dark:shadow-black/20' : 'bg-transparent'"
         id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 md:h-20">
                {{-- Logo --}}
                <a href="#beranda" class="flex items-center gap-2 group" @click.prevent="scrollToSection('beranda')">
                    <img src="{{ asset('images/trifaFarmRedesign.png') }}" class="h-11 md:h-12 w-auto object-contain drop-shadow-lg" alt="Trifafarm Logo">
                    <span class="text-xl font-extrabold tracking-tight">
                        <span class="text-[#FFC72C]">TRIFA</span><span class="dark:text-white"> FARM</span>
                    </span>
                </a>

                {{-- Desktop Menu --}}
                <div class="hidden md:flex items-center gap-1">
                    <template x-for="item in menuItems" :key="item.id">
                        <a :href="'#' + item.id"
                           @click.prevent="scrollToSection(item.id)"
                           class="px-4 py-2 text-sm font-medium transition-all duration-300 hover:text-[#FFC72C]"
                           :class="activeSection === item.id ? 'text-[#FFC72C]' : 'text-gray-600 dark:text-gray-300'"
                           x-text="item.label">
                        </a>
                    </template>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    {{-- Dark Mode Toggle --}}
                    <button @click="toggleDark()" class="relative w-10 h-10 rounded-xl flex items-center justify-center transition-all duration-300 hover:bg-gray-100 dark:hover:bg-white/10" aria-label="Toggle dark mode">
                        <svg x-show="!isDark" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="isDark" x-cloak class="w-5 h-5 text-[#FFC72C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    {{-- Login Button (Desktop) --}}
                    <a href="{{ route('login') }}" class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl bg-[#FFC72C] text-white hover:bg-[#FFB800] transition-all duration-300 hover:shadow-lg hover:shadow-[#FFC72C]/25">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Login Pegawai
                    </a>

                    {{-- Mobile Hamburger --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden w-10 h-10 rounded-xl flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/10 transition-colors" aria-label="Open menu">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu Panel --}}
        <div x-show="mobileMenuOpen" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             @click.away="mobileMenuOpen = false"
             class="md:hidden lp-glass mx-4 mb-4 rounded-2xl shadow-xl overflow-hidden">
            <div class="p-4 space-y-1">
                <template x-for="item in menuItems" :key="item.id">
                    <a :href="'#' + item.id"
                       @click.prevent="scrollToSection(item.id); mobileMenuOpen = false"
                       class="block px-4 py-3 text-sm font-medium transition-all duration-200 hover:text-[#FFC72C]"
                       :class="activeSection === item.id ? 'text-[#FFC72C]' : 'text-gray-600 dark:text-gray-300'"
                       x-text="item.label">
                    </a>
                </template>
                <hr class="my-2 border-gray-200 dark:border-gray-700">
                <a href="{{ route('login') }}" class="flex items-center gap-2 px-4 py-3 text-sm font-semibold text-[#FFC72C] rounded-xl hover:bg-[#FFC72C]/10 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Login Pegawai
                </a>
            </div>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════
         HERO SECTION
         ═══════════════════════════════════════ --}}
    <section id="beranda" class="relative min-h-screen flex items-center overflow-hidden pt-20">
        {{-- Background decorative blobs --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-[500px] h-[500px] rounded-full bg-gradient-to-br from-[#FFC72C]/20 to-[#FF9500]/10 lp-blob-1 blur-3xl"></div>
            <div class="absolute -bottom-60 -left-40 w-[600px] h-[600px] rounded-full bg-gradient-to-tr from-[#FFC72C]/15 to-[#FFE082]/10 lp-blob-2 blur-3xl"></div>
            <div class="absolute top-1/3 left-1/2 w-[300px] h-[300px] rounded-full bg-[#FFC72C]/5 lp-blob-1 blur-2xl"></div>
            {{-- Subtle grid pattern --}}
            <div class="absolute inset-0 opacity-[0.02] dark:opacity-[0.04]" style="background-image: radial-gradient(circle, #FFC72C 1px, transparent 1px); background-size: 40px 40px;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                {{-- Left Content --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#FFC72C]/10 border border-[#FFC72C]/20 mb-6" style="animation: fadeInUp 0.8s ease-out forwards;">
                        <span class="w-2 h-2 rounded-full bg-[#FFC72C] animate-pulse"></span>
                        <span class="text-sm font-medium text-[#FFC72C]">🥚 Produk Segar Setiap Hari</span>
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight mb-6" style="animation: fadeInUp 0.8s ease-out 0.1s both;">
                        Produk Segar <span class="lp-gradient-text">Trifafarm</span> Langsung dari Peternakan
                    </h1>

                    <p class="text-lg text-gray-500 dark:text-gray-400 mb-8 max-w-lg mx-auto lg:mx-0 leading-relaxed" style="animation: fadeInUp 0.8s ease-out 0.2s both;">
                        Trifafarm menghadirkan telur ayam segar berkualitas premium, ayam afkir siap olah, dan pupuk kandang organik hasil dari peternakan modern kami di Padang.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start" style="animation: fadeInUp 0.8s ease-out 0.3s both;">
                        {{-- Pesan Sekarang --}}
                        <a href="https://wa.me/6281372237463?text=Halo%20Trifafarm,%20saya%20tertarik%20untuk%20memesan%20produk%20Anda"
                           target="_blank"
                           class="group inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-gradient-to-r from-[#FFC72C] to-[#FFB800] text-white font-bold text-base rounded-2xl shadow-lg shadow-[#FFC72C]/30 hover:shadow-xl hover:shadow-[#FFC72C]/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300"
                           id="hero-order-btn">
                            {{-- WhatsApp icon --}}
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Pesan Sekarang
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>

                        {{-- Login Pegawai --}}
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center gap-2 px-8 py-4 font-bold text-base rounded-2xl border-2 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:border-[#FFC72C] hover:text-[#FFC72C] hover:bg-[#FFC72C]/5 transition-all duration-300"
                           id="hero-login-btn">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Login Pegawai
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center gap-8 mt-10 justify-center lg:justify-start" style="animation: fadeInUp 0.8s ease-out 0.4s both;">
                        <div>
                            <div class="text-2xl font-extrabold text-[#FFC72C]">10K+</div>
                            <div class="text-xs text-gray-400 font-medium mt-1">Telur/Hari</div>
                        </div>
                        <div class="w-px h-10 bg-gray-200 dark:bg-gray-700"></div>
                        <div>
                            <div class="text-2xl font-extrabold text-[#FFC72C]">500+</div>
                            <div class="text-xs text-gray-400 font-medium mt-1">Pelanggan</div>
                        </div>
                        <div class="w-px h-10 bg-gray-200 dark:bg-gray-700"></div>
                        <div>
                            <div class="text-2xl font-extrabold text-[#FFC72C]">5★</div>
                            <div class="text-xs text-gray-400 font-medium mt-1">Rating</div>
                        </div>
                    </div>
                </div>

                {{-- Right Illustration --}}
                <div class="relative flex items-center justify-center" style="animation: fadeInRight 1s ease-out 0.3s both;">
                    {{-- Main floating card --}}
                    <div class="relative w-72 h-72 sm:w-80 sm:h-80 lg:w-96 lg:h-96">
                        {{-- Central egg illustration --}}
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-48 h-48 sm:w-56 sm:h-56 lg:w-64 lg:h-64 rounded-[3rem] bg-gradient-to-br from-[#FFC72C]/20 to-[#FFE082]/30 dark:from-[#FFC72C]/10 dark:to-[#FFE082]/15 flex items-center justify-center lp-float-slow shadow-2xl shadow-[#FFC72C]/10">
                                <svg class="w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 text-[#FFC72C] drop-shadow-lg" viewBox="0 0 100 120" fill="currentColor">
                                    <ellipse cx="50" cy="65" rx="35" ry="45" opacity="0.2"/>
                                    <ellipse cx="50" cy="63" rx="32" ry="42"/>
                                    <ellipse cx="42" cy="50" rx="8" ry="12" fill="white" opacity="0.3"/>
                                </svg>
                            </div>
                        </div>

                        {{-- Floating element 1 - Quality badge --}}
                        <div class="absolute top-4 right-4 lp-float" style="animation-delay: 0.5s;">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl p-3 shadow-xl shadow-black/5 dark:shadow-black/30 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold">Premium</div>
                                    <div class="text-[10px] text-gray-400">Quality</div>
                                </div>
                            </div>
                        </div>

                        {{-- Floating element 2 - Fresh badge --}}
                        <div class="absolute bottom-8 left-0 lp-float-reverse" style="animation-delay: 1s;">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl p-3 shadow-xl shadow-black/5 dark:shadow-black/30 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-[#FFC72C]/20 flex items-center justify-center">
                                    <span class="text-base">🥚</span>
                                </div>
                                <div>
                                    <div class="text-xs font-bold">100% Segar</div>
                                    <div class="text-[10px] text-gray-400">Setiap Hari</div>
                                </div>
                            </div>
                        </div>

                        {{-- Floating element 3 - Rating --}}
                        <div class="absolute top-1/2 -left-4 lp-float" style="animation-delay: 1.5s;">
                            <div class="bg-white dark:bg-gray-800 rounded-2xl px-3 py-2 shadow-xl shadow-black/5 dark:shadow-black/30">
                                <div class="flex gap-0.5 text-[#FFC72C] text-sm">★★★★★</div>
                                <div class="text-[10px] text-gray-400 mt-0.5 text-center">4.9/5</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Curved divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 100" fill="none" class="w-full" preserveAspectRatio="none">
                <path d="M0 50C360 0 720 100 1440 50V100H0V50Z" class="fill-[#F5F5F5] dark:fill-[#1a1a1a]"/>
            </svg>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         PRODUK SECTION
         ═══════════════════════════════════════ --}}
    <section id="produk" class="py-20 md:py-28 bg-[#F5F5F5] dark:bg-[#1a1a1a] relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center mb-16 lp-animate" x-intersect:enter.once="$el.classList.add('visible')">
                <span class="inline-block px-4 py-1.5 rounded-full bg-[#FFC72C]/10 text-[#FFC72C] text-sm font-semibold mb-4">Produk Kami</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-4">
                    Produk <span class="lp-gradient-text">Unggulan</span> Trifafarm
                </h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto text-lg">
                    Diproses dengan standar kebersihan tertinggi dan disajikan segar untuk memenuhi berbagai kebutuhan pangan dan pertanian Anda.
                </p>
            </div>

            {{-- Product Cards --}}
            <div class="grid md:grid-cols-3 lg:grid-cols-5 gap-6">
                @php
                    $cards = [
                        ['id' => 1, 'name' => 'Telur RB', 'desc' => 'Telur ukuran RB, segar setiap hari.', 'icon' => 'telur'],
                        ['id' => 2, 'name' => 'Telur MB', 'desc' => 'Telur ukuran MB, standar kualitas tinggi.', 'icon' => 'telur'],
                        ['id' => 3, 'name' => 'Telur MK', 'desc' => 'Telur ukuran MK, besar dan bernutrisi.', 'icon' => 'telur'],
                        ['id' => 10, 'name' => 'Ayam Afkir', 'desc' => 'Ayam petelur afkir siap olah, kaldu gurih.', 'icon' => 'ayam'],
                        ['id' => 9, 'name' => 'Pupuk Kandang', 'desc' => 'Pupuk organik terfermentasi sempurna.', 'icon' => 'pupuk'],
                    ];
                @endphp

                @foreach($cards as $index => $c)
                    @php
                        $item = $produk[$c['id']] ?? null;
                        $stok = $item ? number_format($item->stok_barang, 0, ',', '.') : '0';
                    @endphp
                    <div class="lp-card lp-animate bg-white dark:bg-[#222] p-5 shadow-sm border border-gray-100 dark:border-gray-800 group flex flex-col"
                         x-intersect:enter.once="$el.classList.add('visible')" style="transition-delay: {{ $index * 0.1 }}s">
                        <div class="w-full h-36 rounded-2xl overflow-hidden mb-4 bg-white dark:bg-white flex items-center justify-center border border-gray-100 dark:border-gray-800">
                            <img src="{{ asset('images/' . str_replace(' ', '-', $c['name']) . '.png') }}" 
                                 class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform duration-500" 
                                 alt="{{ $c['name'] }}">
                        </div>
                        
                        <h3 class="text-lg font-bold mb-1">{{ $item ? $item->nama_barang : $c['name'] }}</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-xs leading-relaxed mb-3 flex-grow">
                            {{ $c['desc'] }}
                        </p>
                        
                        <div class="mb-4">
                            <span class="text-xs font-medium text-gray-500">Stok Tersedia:</span>
                            <div class="text-xl font-extrabold text-[#FFC72C]">{{ $stok }} <span class="text-sm font-medium text-gray-500">{{ $item ? $item->satuan : '' }}</span></div>
                        </div>
                        
                        <a href="https://wa.me/6281372237463?text=Halo%20Trifafarm,%20saya%20ingin%20memesan%20{{ rawurlencode($c['name']) }}" target="_blank"
                           class="inline-flex items-center justify-center gap-2 w-full py-2 bg-[#FFC72C] text-white font-semibold rounded-xl hover:bg-[#FFB800] hover:shadow-lg hover:shadow-[#FFC72C]/25 transition-all duration-300 text-sm mt-auto">
                            Pesan via WA
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         TENTANG KAMI SECTION
         ═══════════════════════════════════════ --}}
    <section id="tentang" class="py-20 md:py-28 bg-white dark:bg-[#0f0f0f] relative overflow-hidden">
        {{-- Background decorations --}}
        <div class="absolute top-20 right-0 w-[500px] h-[500px] rounded-full bg-[#FFC72C]/5 lp-blob-2 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] rounded-full bg-[#FFC72C]/3 lp-blob-1 blur-3xl pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center mb-16 lp-animate" x-intersect:enter.once="$el.classList.add('visible')">
                <span class="inline-block px-4 py-1.5 rounded-full bg-[#FFC72C]/10 text-[#FFC72C] text-sm font-semibold mb-4">Tentang Kami</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-4">
                    Perjalanan <span class="lp-gradient-text">Trifafarm</span>
                </h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto text-lg">
                    Dari kandang sederhana hingga peternakan modern berbasis digital — inilah cerita kami.
                </p>
            </div>

            {{-- Two-column: Narrative + Timeline --}}
            <div class="grid lg:grid-cols-2 gap-16 items-center">

                {{-- Left: Narrative --}}
                <div class="lp-animate" x-intersect:enter.once="$el.classList.add('visible')">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[#FFC72C]/10 border border-[#FFC72C]/20 mb-6">
                        <svg class="w-4 h-4 text-[#FFC72C]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-xs font-semibold text-[#FFC72C]">Kisah Kami</span>
                    </div>

                    <h3 class="text-2xl sm:text-3xl font-extrabold mb-6 leading-snug">
                        Berawal dari Mimpi Sederhana di <span class="lp-gradient-text">Tanah Minang</span>
                    </h3>

                    <div class="space-y-4 text-gray-600 dark:text-gray-400 leading-relaxed">
                        <p>
                            <strong class="text-gray-900 dark:text-gray-100">Trifafarm</strong> berdiri pada tahun 2020 di kawasan Limau Manis, Kecamatan Pauh, Kota Padang, Sumatera Barat. Bermula dari tekad tiga bersaudara yang ingin memanfaatkan lahan keluarga secara produktif, peternakan ini lahir dengan modal terbatas namun semangat yang besar.
                        </p>
                        <p>
                            Pada awal berdirinya, Trifafarm hanya memiliki satu kandang dengan kapasitas 500 ekor ayam petelur. Telur-telur segar kami dipasarkan langsung ke warung dan pasar tradisional di sekitar Pauh dan Limau Manis, membangun kepercayaan pelanggan satu per satu.
                        </p>
                        <p>
                            Seiring waktu, permintaan terus meningkat. Pada tahun 2022, kami memperluas kapasitas kandang dan mulai mengolah kotoran ayam menjadi <strong class="text-gray-900 dark:text-gray-100">pupuk kandang organik</strong> yang kini menjadi produk unggulan kami bagi para petani sayur di sekitar Padang.
                        </p>
                        <p>
                            Pada 2023, Trifafarm bertransisi ke sistem kandang semi-modern dan mengintegrasikan sistem manajemen digital <strong class="text-gray-900 dark:text-gray-100">Trifacore</strong> — memungkinkan pemantauan produksi telur, kesehatan ternak, dan stok pakan secara real-time untuk menjaga kualitas di setiap butir telur.
                        </p>
                    </div>

                    <div class="mt-8 flex flex-wrap gap-4">
                        <div class="flex items-center gap-2 px-4 py-3 rounded-2xl bg-[#FFC72C]/10 border border-[#FFC72C]/20">
                            <span class="text-2xl font-extrabold text-[#FFC72C]">5+</span>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Tahun<br>Pengalaman</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-3 rounded-2xl bg-[#FFC72C]/10 border border-[#FFC72C]/20">
                            <span class="text-2xl font-extrabold text-[#FFC72C]">5K+</span>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Ekor Ayam<br>Petelur</span>
                        </div>
                        <div class="flex items-center gap-2 px-4 py-3 rounded-2xl bg-[#FFC72C]/10 border border-[#FFC72C]/20">
                            <span class="text-2xl font-extrabold text-[#FFC72C]">500+</span>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Pelanggan<br>Setia</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Timeline --}}
                <div class="lp-animate lp-animate-delay-1" x-intersect:enter.once="$el.classList.add('visible')">
                    <div class="relative pl-8">
                        {{-- Vertical line --}}
                        <div class="absolute left-3.5 top-2 bottom-2 w-0.5 bg-gradient-to-b from-[#FFC72C] via-[#FFC72C]/50 to-transparent"></div>

                        {{-- Milestone 1 --}}
                        <div class="relative mb-10">
                            <div class="absolute -left-8 top-1 w-7 h-7 rounded-full bg-[#FFC72C] flex items-center justify-center shadow-lg shadow-[#FFC72C]/30">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="lp-card bg-white dark:bg-[#1a1a1a] p-5 border border-gray-100 dark:border-gray-800 shadow-sm">
                                <span class="text-xs font-bold text-[#FFC72C] uppercase tracking-wider">2020 — Awal Berdiri</span>
                                <h4 class="font-bold text-base mt-1 mb-2">Kandang Pertama Trifafarm</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Memulai dengan 500 ekor ayam petelur di lahan keluarga, Limau Manis, Padang. Produk dipasarkan ke warung lokal.</p>
                            </div>
                        </div>

                        {{-- Milestone 2 --}}
                        <div class="relative mb-10">
                            <div class="absolute -left-8 top-1 w-7 h-7 rounded-full bg-[#FFC72C] flex items-center justify-center shadow-lg shadow-[#FFC72C]/30">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="lp-card bg-white dark:bg-[#1a1a1a] p-5 border border-gray-100 dark:border-gray-800 shadow-sm">
                                <span class="text-xs font-bold text-[#FFC72C] uppercase tracking-wider">2022 — Ekspansi Produk</span>
                                <h4 class="font-bold text-base mt-1 mb-2">Pupuk Kandang & Ayam Afkir</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kapasitas kandang diperluas. Kotoran ayam diolah menjadi pupuk kandang organik. Ayam afkir mulai dipasarkan ke pelanggan kuliner.</p>
                            </div>
                        </div>

                        {{-- Milestone 3 --}}
                        <div class="relative mb-10">
                            <div class="absolute -left-8 top-1 w-7 h-7 rounded-full bg-[#FFC72C] flex items-center justify-center shadow-lg shadow-[#FFC72C]/30">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="lp-card bg-white dark:bg-[#1a1a1a] p-5 border border-gray-100 dark:border-gray-800 shadow-sm">
                                <span class="text-xs font-bold text-[#FFC72C] uppercase tracking-wider">2023 — Modernisasi</span>
                                <h4 class="font-bold text-base mt-1 mb-2">Sistem Manajemen Digital Trifacore</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Transisi ke kandang semi-modern dan integrasi sistem digital Trifacore untuk pencatatan produksi, pakan, dan kesehatan ternak secara real-time.</p>
                            </div>
                        </div>

                        {{-- Milestone 4 (current) --}}
                        <div class="relative">
                            <div class="absolute -left-8 top-1 w-7 h-7 rounded-full bg-gradient-to-br from-[#FFC72C] to-[#FF9500] flex items-center justify-center shadow-lg shadow-[#FFC72C]/40 animate-pulse">
                                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                                </svg>
                            </div>
                            <div class="lp-card bg-gradient-to-br from-[#FFC72C]/10 to-[#FFE082]/10 dark:from-[#FFC72C]/5 dark:to-[#FFE082]/5 p-5 border border-[#FFC72C]/30 shadow-sm">
                                <span class="text-xs font-bold text-[#FFC72C] uppercase tracking-wider">2025 — Sekarang</span>
                                <h4 class="font-bold text-base mt-1 mb-2">Trifafarm Terus Berkembang 🚀</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Melayani 500+ pelanggan aktif dari rumah tangga, pedagang, hingga pelaku usaha kuliner dan pertanian di Padang dan sekitarnya.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         TESTIMONI SECTION
         ═══════════════════════════════════════ --}}
    <section id="testimoni" class="py-20 md:py-28 bg-[#F5F5F5] dark:bg-[#1a1a1a] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section Header --}}
            <div class="text-center mb-16 lp-animate" x-intersect:enter.once="$el.classList.add('visible')">
                <span class="inline-block px-4 py-1.5 rounded-full bg-[#FFC72C]/10 text-[#FFC72C] text-sm font-semibold mb-4">Testimoni</span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold mb-4">
                    Apa Kata <span class="lp-gradient-text">Pelanggan</span> Kami
                </h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto text-lg">
                    Kepuasan pelanggan adalah prioritas utama kami.
                </p>
            </div>

            {{-- Carousel --}}
            <div class="lp-animate" x-intersect:enter.once="$el.classList.add('visible')"
                 x-data="testimonialCarousel()"
                 @mouseenter="pause()" @mouseleave="resume()">
                <div class="relative max-w-4xl mx-auto">
                    {{-- Testimonial Card --}}
                    <div class="grid grid-cols-1 grid-rows-1">
                        <template x-for="(testimonial, index) in testimonials" :key="index">
                            <div x-show="currentSlide === index"
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 translate-x-8"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-x-0"
                                 x-transition:leave-end="opacity-0 -translate-x-8"
                                 class="col-start-1 row-start-1 bg-white dark:bg-[#222] rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100 dark:border-gray-800">
                                {{-- Stars --}}
                                <div class="flex gap-1 text-[#FFC72C] text-xl mb-6">★★★★★</div>

                                {{-- Quote --}}
                                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-300 leading-relaxed mb-8 italic" x-text="'&quot;' + testimonial.text + '&quot;'"></p>

                                {{-- Author --}}
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#FFC72C] to-[#FF9500] flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-[#FFC72C]/20"
                                         x-text="testimonial.name.charAt(0)">
                                    </div>
                                    <div>
                                        <div class="font-bold" x-text="testimonial.name"></div>
                                        <div class="text-sm text-gray-400" x-text="testimonial.role"></div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Navigation --}}
                    <div class="flex items-center justify-center gap-4 mt-8">
                        <button @click="prev()" class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-[#FFC72C] hover:border-[#FFC72C] hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>

                        {{-- Dots --}}
                        <div class="flex gap-2">
                            <template x-for="(testimonial, index) in testimonials" :key="'dot-'+index">
                                <button @click="goTo(index)"
                                        class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                        :class="currentSlide === index ? 'bg-[#FFC72C] w-8' : 'bg-gray-300 dark:bg-gray-600 hover:bg-[#FFC72C]/50'">
                                </button>
                            </template>
                        </div>

                        <button @click="next()" class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-700 flex items-center justify-center hover:bg-[#FFC72C] hover:border-[#FFC72C] hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Form Ulasan --}}
            <div class="max-w-3xl mx-auto mt-20 lp-animate" x-intersect:enter.once="$el.classList.add('visible')">
                <div class="bg-white dark:bg-[#222] rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-800 relative overflow-hidden">
                    {{-- Decorative bg --}}
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-gradient-to-br from-[#FFC72C]/20 to-[#FF9500]/20 rounded-full blur-3xl"></div>
                    
                    <h3 class="text-2xl font-bold mb-2">Berikan Ulasan Anda</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Ceritakan pengalaman Anda menggunakan produk Trifafarm.</p>
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('testimoni.store') }}" method="POST" class="relative z-10 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a1a] focus:bg-white dark:focus:bg-[#222] focus:ring-2 focus:ring-[#FFC72C] focus:border-transparent transition-all outline-none" placeholder="Cth: Budi Santoso">
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pekerjaan / Asal</label>
                                <input type="text" name="role" id="role" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a1a] focus:bg-white dark:focus:bg-[#222] focus:ring-2 focus:ring-[#FFC72C] focus:border-transparent transition-all outline-none" placeholder="Cth: Pemilik RM Padang">
                            </div>
                        </div>
                        <div>
                            <label for="teks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ulasan Anda</label>
                            <textarea name="teks" id="teks" rows="4" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#1a1a1a] focus:bg-white dark:focus:bg-[#222] focus:ring-2 focus:ring-[#FFC72C] focus:border-transparent transition-all outline-none resize-none" placeholder="Tuliskan pendapat Anda tentang produk kami..."></textarea>
                        </div>
                        <button type="submit" class="w-full py-3.5 bg-[#FFC72C] hover:bg-[#FFB800] text-white font-bold rounded-xl shadow-lg shadow-[#FFC72C]/30 transition-all hover:scale-[1.02] active:scale-[0.98]">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         CALL TO ACTION
         ═══════════════════════════════════════ --}}
    <section class="py-20 md:py-28 relative overflow-hidden">
        {{-- Gradient Background --}}
        <div class="absolute inset-0 bg-gradient-to-br from-[#FFC72C] via-[#FFB800] to-[#FF9500]"></div>
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 50%, white 1px, transparent 1px); background-size: 30px 30px;"></div>

        {{-- Floating decorations --}}
        <div class="absolute top-10 left-10 w-20 h-20 rounded-full bg-white/10 lp-float blur-sm"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 rounded-full bg-white/10 lp-float-reverse blur-sm"></div>
        <div class="absolute top-1/2 left-1/3 w-16 h-16 rounded-full bg-white/5 lp-float-slow blur-sm"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center lp-animate" x-intersect:enter.once="$el.classList.add('visible')">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 text-white text-sm font-medium mb-6">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                Siap Memesan?
            </div>

            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white mb-6 leading-tight">
                Dapatkan Telur Segar<br>Berkualitas Tinggi Sekarang!
            </h2>

            <p class="text-lg text-white/80 mb-10 max-w-2xl mx-auto">
                Hubungi kami langsung melalui WhatsApp untuk pemesanan cepat dan mudah. Tim sales kami siap membantu Anda.
            </p>

            <a href="https://wa.me/628xxxxxxxxxx?text=Halo%20saya%20ingin%20memesan%20produk%20Trifacore"
               target="_blank"
               class="inline-flex items-center gap-3 px-10 py-5 bg-white text-[#FFC72C] font-extrabold text-lg rounded-2xl shadow-2xl shadow-black/10 hover:shadow-3xl hover:scale-[1.03] active:scale-[0.98] transition-all duration-300 lp-wa-pulse"
               id="cta-order-btn">
                {{-- WhatsApp icon --}}
                <svg class="w-6 h-6 text-green-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Pesan Sekarang via WhatsApp
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         KONTAK SECTION
         ═══════════════════════════════════════ --}}
    <section id="kontak" class="py-20 md:py-28 bg-[#F5F5F5] dark:bg-[#1a1a1a] relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 items-center">
                {{-- Contact Info Card --}}
                <div class="lg:col-span-5 space-y-8">
                    <div class="lp-animate" x-intersect:enter.once="$el.classList.add('visible')">
                        <span class="inline-block px-4 py-1.5 rounded-full bg-[#FFC72C]/10 text-[#FFC72C] text-sm font-semibold mb-4">Hubungi Kami</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold mb-4">
                            Kunjungi <span class="lp-gradient-text">Peternakan</span> Kami
                        </h2>
                        <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">
                            Punya pertanyaan tentang produk kami, kerja sama kemitraan, atau ingin berkunjung langsung? Silakan hubungi kami atau kunjungi peternakan kami di Padang.
                        </p>
                    </div>

                    <div class="space-y-4">
                        {{-- Address --}}
                        <div class="flex items-start gap-4 p-5 rounded-2xl bg-white dark:bg-[#222] border border-gray-100 dark:border-gray-800 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-[#FFC72C]/10 flex items-center justify-center text-[#FFC72C] shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Lokasi Peternakan</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                                    RT 02 / RW 06, Koto Baru, Kel. Limau Manis Selatan, Kec. Pauh, Kota Padang, Sumatera Barat 25158
                                </p>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="flex items-start gap-4 p-5 rounded-2xl bg-white dark:bg-[#222] border border-gray-100 dark:border-gray-800 shadow-sm">
                            <div class="w-10 h-10 rounded-xl bg-[#FFC72C]/10 flex items-center justify-center text-[#FFC72C] shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Email Resmi</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">info@trifafarm.com</p>
                            </div>
                        </div>

                        {{-- Phone / WhatsApp --}}
                        <a href="https://wa.me/6281372237463?text=Halo%20Trifafarm,%20saya%20ingin%20bertanya%20mengenai%20produk%20Anda" target="_blank"
                           class="flex items-start gap-4 p-5 rounded-2xl bg-white dark:bg-[#222] border border-gray-100 dark:border-gray-800 shadow-sm hover:border-[#FFC72C] transition-colors duration-300">
                            <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 shrink-0">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-sm">Hubungi via WhatsApp</h4>
                                <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-semibold">+62 813-7223-7463</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Interactive Map Embed --}}
                <div class="lg:col-span-7 lp-animate" x-intersect:enter.once="$el.classList.add('visible')">
                    <div class="relative rounded-3xl overflow-hidden shadow-xl shadow-black/5 dark:shadow-black/20 border border-gray-100 dark:border-gray-800 bg-white dark:bg-[#222] p-2">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1063.7588851441528!2d100.47769847296289!3d-0.9355222998593551!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4b7001a50579d%3A0x2ebba61a6625b9b9!2sTRIFA%20FARM!5e1!3m2!1sid!2sid!4v1782149257375!5m2!1sid!2sid" 
                                class="w-full h-96 rounded-2xl" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <div class="absolute bottom-6 right-6">
                            <a href="https://maps.app.goo.gl/DGu453VfQ9EmA4gV8" target="_blank"
                               class="inline-flex items-center gap-2 px-5 py-3 bg-[#FFC72C] hover:bg-[#FFB800] text-white font-bold rounded-xl shadow-lg transition-all duration-300 text-xs">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Buka di Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════
         FOOTER
         ═══════════════════════════════════════ --}}
    <footer class="bg-[#e9e9e9] dark:bg-[#111] py-12 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 border-b border-gray-200 dark:border-gray-800 pb-8 mb-8">
                {{-- Logo --}}
                <div class="flex items-center gap-2">
                    <img src="{{ asset('images/trifaFarmRedesign.png') }}" class="h-10 md:h-11 w-auto object-contain drop-shadow-md" alt="Trifafarm Logo">
                    <span class="text-lg font-extrabold tracking-tight">
                        <span class="text-[#FFC72C]">TRIFA</span><span class="dark:text-white"> FARM</span>
                    </span>
                </div>
                
                {{-- Quick Nav --}}
                <div class="flex flex-wrap justify-center gap-6 text-sm text-gray-500 dark:text-gray-400">
                    <a href="#beranda" @click.prevent="scrollToSection('beranda')" class="hover:text-[#FFC72C] transition-colors">Beranda</a>
                    <a href="#produk" @click.prevent="scrollToSection('produk')" class="hover:text-[#FFC72C] transition-colors">Produk</a>
                    <a href="#tentang" @click.prevent="scrollToSection('tentang')" class="hover:text-[#FFC72C] transition-colors">Tentang Kami</a>
                    <a href="#testimoni" @click.prevent="scrollToSection('testimoni')" class="hover:text-[#FFC72C] transition-colors">Testimoni</a>
                    <a href="#kontak" @click.prevent="scrollToSection('kontak')" class="hover:text-[#FFC72C] transition-colors">Kontak</a>
                    <a href="{{ route('login') }}" class="hover:text-[#FFC72C] transition-colors">Login Pegawai</a>
                </div>

                {{-- Social Icons --}}
                <div class="flex gap-3">
                    <a href="#" class="w-8 h-8 rounded-lg bg-gray-200 dark:bg-gray-800 flex items-center justify-center hover:bg-[#FFC72C] hover:text-white transition-all duration-300 text-gray-500">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-lg bg-gray-200 dark:bg-gray-800 flex items-center justify-center hover:bg-[#FFC72C] hover:text-white transition-all duration-300 text-gray-500">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Trifafarm. All rights reserved.</p>
                <p class="text-xs text-gray-400">Dibuat dengan <span class="text-[#FFC72C]">♥</span> untuk ketahanan pangan Indonesia</p>
            </div>
        </div>
    </footer>

    {{-- ═══════════════════════════════════════
         ALPINE.JS SCRIPTS
         ═══════════════════════════════════════ --}}
    <script>
        document.addEventListener('alpine:init', () => {
            // Main landing page component
            Alpine.data('landingPage', () => ({
                loading: true,
                scrolled: false,
                mobileMenuOpen: false,
                isDark: false,
                activeSection: 'beranda',
                menuItems: [
                    { id: 'beranda', label: 'Beranda' },
                    { id: 'produk', label: 'Produk' },
                    { id: 'tentang', label: 'Tentang Kami' },
                    { id: 'testimoni', label: 'Testimoni' },
                    { id: 'kontak', label: 'Kontak' },
                ],

                init() {
                    // Dark mode init
                    const stored = localStorage.getItem('darkMode');
                    if (stored !== null) {
                        this.isDark = stored === 'true';
                    } else {
                        this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    }

                    // Loading screen
                    setTimeout(() => { this.loading = false; }, 1200);

                    // Scroll listener
                    window.addEventListener('scroll', () => {
                        this.scrolled = window.scrollY > 50;
                        this.updateActiveSection();
                    });

                    // Initial check
                    this.scrolled = window.scrollY > 50;
                },

                toggleDark() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('darkMode', this.isDark);
                },

                scrollToSection(id) {
                    const el = document.getElementById(id);
                    if (el) {
                        const offset = 80;
                        const top = el.getBoundingClientRect().top + window.pageYOffset - offset;
                        window.scrollTo({ top, behavior: 'smooth' });
                    }
                },

                updateActiveSection() {
                    const sections = ['kontak', 'testimoni', 'tentang', 'produk', 'beranda'];
                    for (const id of sections) {
                        const el = document.getElementById(id);
                        if (el) {
                            const rect = el.getBoundingClientRect();
                            if (rect.top <= 150) {
                                this.activeSection = id;
                                break;
                            }
                        }
                    }
                }
            }));

            // Testimonial carousel component
            Alpine.data('testimonialCarousel', () => ({
                currentSlide: 0,
                interval: null,
                testimonials: {!! $testimonis->isEmpty() ? json_encode([
                    [
                        'name' => 'Budi Santoso',
                        'role' => 'Pemilik Toko Kelontong, Padang',
                        'text' => 'Kuning telur dari Trifafarm pekat dan kulitnya tebal, sehingga jarang sekali ada yang pecah saat dikirim ke toko kami. Pelanggan selalu menanyakan telur ini!'
                    ],
                    [
                        'name' => 'Siti Rahayu',
                        'role' => 'Pemilik RM Soto',
                        'text' => 'Daging ayam afkir dari Trifafarm sangat segar dan bersih. Kaldu soto kami jadi jauh lebih gurih, pekat, dan lezat. Pelanggan kami sangat menyukainya.'
                    ],
                    [
                        'name' => 'Ahmad Fauzi',
                        'role' => 'Petani Hortikultura',
                        'text' => 'Pupuk kandang dari Trifafarm sudah melalui proses fermentasi yang baik, tidak berbau menyengat, dan sangat efektif menyuburkan kebun sayur kol kami.'
                    ],
                    [
                        'name' => 'Linda Wijaya',
                        'role' => 'Ibu Rumah Tangga, Pauh',
                        'text' => 'Saya selalu membeli telur langsung di lokasi Trifafarm karena dekat dan dijamin dipanen hari itu juga. Sangat segar dan pelayanannya ramah!'
                    ]
                ]) : json_encode($testimonis->map(function($t) { return ['name' => $t->nama, 'role' => $t->role ?? 'Pelanggan', 'text' => $t->teks]; })) !!},

                init() {
                    this.startAutoplay();
                },

                startAutoplay() {
                    this.interval = setInterval(() => {
                        this.next();
                    }, 5000);
                },

                pause() {
                    clearInterval(this.interval);
                },

                resume() {
                    this.startAutoplay();
                },

                next() {
                    this.currentSlide = (this.currentSlide + 1) % this.testimonials.length;
                },

                prev() {
                    this.currentSlide = (this.currentSlide - 1 + this.testimonials.length) % this.testimonials.length;
                },

                goTo(index) {
                    this.currentSlide = index;
                    this.pause();
                    this.resume();
                }
            }));
        });

        // ─── Native IntersectionObserver (replaces x-intersect plugin) ───────────
        // Adds class 'visible' to .lp-animate elements when they scroll into view.
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

            document.querySelectorAll('.lp-animate').forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>
