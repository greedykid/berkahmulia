<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Katalog') | Berkah Mulia - Pakaian Bayi, Anak-anak & Underwear</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="@yield('meta_description', 'Pusat grosir dan eceran pakaian bayi, anak-anak, dan underwear berkualitas premium dengan harga bersahabat di Berkah Mulia.')">
    <link rel="canonical" href="@yield('canonical_url', request()->url())">
    
    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Katalog') | Berkah Mulia">
    <meta property="og:description" content="@yield('meta_description', 'Pusat grosir dan eceran pakaian bayi, anak-anak, dan underwear berkualitas premium dengan harga bersahabat.')">
    <meta property="og:image" content="@yield('og_image', asset('logo.webp'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Katalog') | Berkah Mulia">
    <meta property="twitter:description" content="@yield('meta_description', 'Pusat grosir dan eceran pakaian bayi, anak-anak, dan underwear berkualitas premium dengan harga bersahabat.')">
    <meta property="twitter:image" content="@yield('og_image', asset('logo.webp'))">

    <!-- Google Fonts & DNS Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" as="style">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript><link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet"></noscript>
    
    <!-- Compiled Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="/logo.webp">

    <!-- FontAwesome for icons (asynchronous load to prevent render blocking) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    @php
        $whatsappTemplate = \App\Models\Setting::get('whatsapp_message_template', '');
        $whatsappLink = 'https://wa.me/' . config('app.whatsapp_number', '628123456789');
        if (!empty($whatsappTemplate)) {
            $whatsappLink .= '?text=' . urlencode($whatsappTemplate);
        }
        $instagramUrl = \App\Models\Setting::get('instagram_url', '');
        $tiktokUrl = \App\Models\Setting::get('tiktok_url', '');
        $tiktokName = \App\Models\Setting::get('tiktok_name', '');
        $shopeeUrl = \App\Models\Setting::get('shopee_url', '');
        $showInstagramNav = \App\Models\Setting::get('show_instagram_nav', true);
        $showTiktokNav = \App\Models\Setting::get('show_tiktok_nav', true);
        $storeAddress = \App\Models\Setting::get('store_address', 'Jl. Poin Mas 40, Sawangan , Kota Depok, Jawa Barat');
    @endphp

    <!-- Top Announcement Bar -->
    <div id="announcement-bar" class="bg-primary-600 text-white text-xs py-2 px-4 text-center font-medium tracking-wide relative hidden">
        <i class="fa-solid fa-gift mr-1 animate-pulse"></i>
        {{ \App\Models\Setting::get('store_announcement_text', 'Selamat datang di Berkah Mulia! Koleksi Pakaian Bayi, Anak-anak & Pakaian Dalam Terbaik.') }}
        <button onclick="closeAnnouncementBar()" class="absolute right-3 top-1/2 -translate-y-1/2 text-white hover:text-slate-200 transition-colors" aria-label="Tutup">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>
    <script>
        (function() {
            const bar = document.getElementById('announcement-bar');
            if (sessionStorage.getItem('announcement_closed') !== 'true') {
                bar.classList.remove('hidden');
            }
        })();
        function closeAnnouncementBar() {
            document.getElementById('announcement-bar').style.display = 'none';
            sessionStorage.setItem('announcement_closed', 'true');
        }
    </script>

    <!-- Header Section -->
    <header class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3 sm:gap-4">
                
                <!-- Logo -->
                <div class="shrink-0" id="header-logo">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3">
                        <img src="/logo.webp" alt="Berkah Mulia Logo" width="48" height="48" class="h-10 sm:h-12 w-auto rounded-xl shadow-sm border border-slate-100 object-cover">
                        <div class="hidden sm:flex flex-col">
                            <span class="flex items-center gap-0.5 text-base sm:text-2xl tracking-tight leading-none select-none">
                                <span class="logo-letter text-apricot-cream-300">B</span>
                                <span class="logo-letter text-pearl-aqua-300">e</span>
                                <span class="logo-letter text-sky-blue-300">r</span>
                                <span class="logo-letter text-vanilla-custard-300">k</span>
                                <span class="logo-letter text-thistle-300">a</span>
                                <span class="logo-letter text-frozen-water-300">h</span>
                                <span class="w-1 sm:w-1.5"></span>
                                <span class="logo-letter text-peach-fuzz-300">M</span>
                                <span class="logo-letter text-frozen-water-u-300">u</span>
                                <span class="logo-letter text-vanilla-custard-l-300">l</span>
                                <span class="logo-letter text-thistle-300">i</span>
                                <span class="logo-letter text-thistle-300">a</span>
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Search Bar (Desktop) -->
                <div class="flex-1 max-w-lg hidden sm:block">
                    <form action="{{ route('catalog.index') }}" method="GET" class="relative">
                        <input type="text" name="search" aria-label="Cari pakaian anak dan kode SKU" placeholder="Cari pakaian anak, kode SKU..." value="{{ request('search') }}"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-700 pl-10 pr-12 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-primary-400 focus:bg-white transition-all text-sm">
                        <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 flex items-center">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </div>
                        <button type="submit" class="absolute right-1.5 top-1.5 bg-primary-500 hover:bg-primary-600 text-white px-3 py-1.5 rounded-full text-xs font-semibold transition-all" aria-label="Kirim pencarian">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Mobile Search Toggle + WhatsApp -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- Mobile Search Button -->
                    <button type="button" id="mobile-search-toggle" aria-label="Buka kolom pencarian mobile" class="sm:hidden w-11 h-11 flex items-center justify-center rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:bg-slate-100 transition-all">
                        <i class="fa-solid fa-magnifying-glass text-sm"></i>
                    </button>

                    <!-- Shopping Cart CTA -->
                    <button type="button" onclick="toggleCartDrawer(true)" aria-label="Buka Keranjang Belanja"
                       class="relative flex items-center justify-center w-11 h-11 bg-primary-50 text-primary-700 hover:bg-primary-100 rounded-full border border-primary-200 transition-all hover:scale-105 shrink-0 cursor-pointer">
                        <i class="fa-solid fa-shopping-cart text-lg"></i>
                        <span id="cart-count-badge" class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[9px] font-extrabold w-5 h-5 rounded-full flex items-center justify-center border-2 border-white hidden">0</span>
                    </button>

                    <!-- Instagram CTA -->
                    @if(!empty($instagramUrl) && $showInstagramNav)
                    <a href="{{ $instagramUrl }}" target="_blank" aria-label="Instagram Berkah Mulia"
                       class="flex items-center justify-center w-11 h-11 bg-pink-50 rounded-full border border-pink-200 transition-all hover:scale-105 shrink-0">
                        <i class="fa-brands fa-instagram text-lg" style="color: #e1306c;"></i>
                    </a>
                    @endif

                    <!-- TikTok CTA -->
                    @if(!empty($tiktokUrl) && $showTiktokNav)
                    <a href="{{ $tiktokUrl }}" target="_blank" aria-label="TikTok Berkah Mulia"
                       class="flex items-center justify-center w-11 h-11 bg-slate-50 text-slate-800 hover:bg-slate-100 rounded-full border border-slate-200 transition-all hover:scale-105 shrink-0">
                        <i class="fa-brands fa-tiktok text-lg text-black"></i>
                    </a>
                    @endif

                    <!-- Shopee CTA -->
                    @if(!empty($shopeeUrl))
                    <a href="{{ $shopeeUrl }}" target="_blank" aria-label="Shopee Berkah Mulia"
                       class="flex items-center justify-center w-11 h-11 bg-orange-50 text-orange-700 hover:bg-orange-100 rounded-full border border-orange-200 transition-all hover:scale-105 shrink-0">
                        <svg viewBox="0 0 109.59 122.88" class="w-5 h-5 fill-[#EE4D2D]">
                            <path d="M74.98,91.98C76.15,82.36,69.96,76.22,53.6,71c-7.92-2.7-11.66-6.24-11.57-11.12 c0.33-5.4,5.36-9.34,12.04-9.47c4.63,0.09,9.77,1.22,14.76,4.56c0.59,0.37,1.01,0.32,1.35-0.2c0.46-0.74,1.61-2.53,2-3.17 c0.26-0.42,0.31-0.96-0.35-1.44c-0.95-0.7-3.6-2.13-5.03-2.72c-3.88-1.62-8.23-2.64-12.86-2.63c-9.77,0.04-17.47,6.22-18.12,14.47 c-0.42,5.95,2.53,10.79,8.86,14.47c1.34,0.78,8.6,3.67,11.49,4.57c9.08,2.83,13.8,7.9,12.69,13.81c-1.01,5.36-6.65,8.83-14.43,8.93 c-6.17-0.24-11.71-2.75-16.02-6.1c-0.11-0.08-0.65-0.5-0.72-0.56c-0.53-0.42-1.11-0.39-1.47,0.15c-0.26,0.4-1.92,2.8-2.34,3.43 c-0.39,0.55-0.18,0.86,0.23,1.2c1.8,1.5,4.18,3.14,5.81,3.97c4.47,2.28,9.32,3.53,14.48,3.72c3.32,0.22,7.5-0.49,10.63-1.81 C70.63,102.67,74.25,97.92,74.98,91.98L74.98,91.98z M54.79,7.18c-10.59,0-19.22,9.98-19.62,22.47h39.25 C74.01,17.16,65.38,7.18,54.79,7.18L54.79,7.18z M94.99,122.88l-0.41,0l-80.82-0.01h0c-5.5-0.21-9.54-4.66-10.09-10.19l-0.05-1 l-3.61-79.5v0C0,32.12,0,32.06,0,32c0-1.28,1.03-2.33,2.3-2.35l0,0h25.48C28.41,13.15,40.26,0,54.79,0s26.39,13.15,27.01,29.65 h25.4h0.04c1.3,0,2.35,1.05,2.35,2.35c0,0.04,0,0.08,0,0.12v0l-3.96,79.81l-0.04,0.68C105.12,118.21,100.59,122.73,94.99,122.88 L94.99,122.88z"/>
                        </svg>
                    </a>
                    @endif


                    <!-- WhatsApp CTA -->
                    @if(\App\Models\Setting::get('show_whatsapp_nav', true))
                    <a href="{{ $whatsappLink }}" target="_blank" aria-label="Hubungi Admin Berkah Mulia via WhatsApp"
                       class="flex items-center justify-center gap-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 px-3.5 sm:px-4 h-11 rounded-full text-xs font-semibold border border-emerald-200 transition-all hover:scale-105 shrink-0">
                        <i class="fa-brands fa-whatsapp text-lg text-emerald-500"></i>
                        <span class="hidden md:inline">Hubungi Admin</span>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Mobile Search Bar (Expandable) -->
            <div id="mobile-search-bar" class="sm:hidden transition-all duration-300 ease-out max-h-0 opacity-0 overflow-hidden px-3">
                <form action="{{ route('catalog.index') }}" method="GET" class="mt-2 pb-3">
                    <div class="relative">
                        <input type="text" name="search" id="mobile-search-input" aria-label="Cari pakaian anak dan kode SKU mobile" placeholder="Cari pakaian anak, kode SKU..." value="{{ request('search') }}"
                               class="w-full bg-slate-50 border border-slate-200 text-slate-700 pl-10 pr-16 py-2.5 rounded-full focus:outline-none focus:ring-2 focus:ring-primary-400 focus:bg-white transition-all text-sm">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 flex items-center">
                            <i class="fa-solid fa-magnifying-glass text-sm"></i>
                        </div>
                        <button type="submit" class="absolute right-2 top-1.5 bg-primary-500 hover:bg-primary-600 text-white px-4 py-1.5 rounded-full text-xs font-semibold transition-all" aria-label="Kirim pencarian mobile">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Category Main Navigation (Carter's-inspired) -->
        <div class="bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="overflow-x-auto no-scrollbar py-2.5 px-1">
                    <div class="flex items-center justify-start gap-1.5 sm:gap-4 text-sm font-medium w-max max-w-full md:mx-auto">
                        <a href="{{ route('home') }}" 
                           class="shrink-0 px-2.5 py-1 rounded-full flex items-center gap-1.5 {{ request()->routeIs('home') ? 'bg-primary-500 text-white shadow-sm font-semibold' : 'text-slate-800 hover:text-primary-500 hover:bg-slate-50 font-semibold' }} transition-all">
                            <i class="fa-solid fa-house text-xs"></i>
                            <span>Beranda</span>
                        </a>
                        <a href="{{ route('catalog.index') }}" 
                           class="shrink-0 px-2.5 py-1 rounded-full flex items-center gap-1.5 {{ (request()->routeIs('catalog.index') && !request('category')) ? 'bg-primary-500 text-white shadow-sm font-semibold' : 'text-slate-800 hover:text-primary-500 hover:bg-slate-50 font-semibold' }} transition-all">
                            <i class="fa-solid fa-store text-xs"></i>
                            <span>Katalog</span>
                        </a>
                        
                        <div class="h-5 w-px bg-slate-200 shrink-0 mx-2 self-center"></div>

                        @foreach($navCategories as $cat)
                            <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" 
                               class="shrink-0 px-2.5 py-1 rounded-full {{ request('category') === $cat->slug ? 'bg-primary-500 text-white shadow-sm font-semibold' : 'text-slate-600 hover:text-primary-500 hover:bg-slate-50' }} transition-all">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 pt-16 pb-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <!-- About -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="/logo.webp" alt="Berkah Mulia Logo" width="40" height="40" class="h-10 w-auto rounded-lg shadow-sm border border-slate-700 object-cover">
                        <span class="flex items-center gap-0.5 text-lg tracking-tight leading-none select-none">
                            <span class="logo-letter text-apricot-cream-300">B</span>
                            <span class="logo-letter text-pearl-aqua-300">e</span>
                            <span class="logo-letter text-sky-blue-300">r</span>
                            <span class="logo-letter text-vanilla-custard-300">k</span>
                            <span class="logo-letter text-thistle-300">a</span>
                            <span class="logo-letter text-frozen-water-300">h</span>
                            <span class="w-1"></span>
                            <span class="logo-letter text-peach-fuzz-300">M</span>
                            <span class="logo-letter text-frozen-water-u-300">u</span>
                            <span class="logo-letter text-vanilla-custard-l-300">l</span>
                            <span class="logo-letter text-thistle-300">i</span>
                            <span class="logo-letter text-thistle-300">a</span>
                        </span>
                    </div>
                    <p class="text-sm leading-relaxed text-slate-400">
                        Pusat grosir dan eceran pakaian bayi, anak-anak, dan underwear berkualitas premium dengan harga bersahabat. Kami membantu memudahkan Anda melihat katalog produk kami secara online.
                    </p>
                </div>
                
                <!-- Categories Quick Links -->
                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Kategori Utama</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($navCategories->take(5) as $cat)
                            <li>
                                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="hover:text-primary-400 transition-colors">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Extra Categories -->
                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Kategori Lainnya</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach($navCategories->skip(5)->take(5) as $cat)
                            <li>
                                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="hover:text-primary-400 transition-colors">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Hubungi Kami</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-location-dot mt-0.5 text-primary-400 shrink-0"></i>
                            <span>{{ $storeAddress }}</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-primary-400"></i>
                            <a href="mailto:{{ config('app.admin_email', 'info@bmberkahmulia.com') }}" class="hover:text-primary-400 transition-colors">{{ config('app.admin_email', 'info@bmberkahmulia.com') }}</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-emerald-400 text-lg"></i>
                            <a href="https://wa.me/{{ config('app.whatsapp_number', '628123456789') }}" target="_blank" class="hover:text-primary-400 transition-colors">
                                +{{ config('app.whatsapp_number', '628123456789') }} (Sales Admin)
                            </a>
                        </li>
                        @if(!empty($instagramUrl))
                        @php
                            $instagramUsername = 'Instagram';
                            $parsedUrl = parse_url($instagramUrl);
                            if (isset($parsedUrl['path'])) {
                                $path = trim($parsedUrl['path'], '/');
                                $segments = explode('/', $path);
                                $firstSegment = isset($segments[0]) ? trim($segments[0]) : '';
                                if (!empty($firstSegment) && !in_array(strtolower($firstSegment), ['explore', 'p', 'reels', 'stories'])) {
                                    $instagramUsername = '@' . $firstSegment;
                                }
                            }
                        @endphp
                        <li class="flex items-center gap-2">
                            <i class="fa-brands fa-instagram text-lg" style="color: #e1306c;"></i>
                            <a href="{{ $instagramUrl }}" target="_blank" class="hover:text-primary-400 transition-colors">
                                {{ $instagramUsername }}
                            </a>
                        </li>
                        @endif
                        @if(!empty($tiktokUrl))
                        @php
                            $tiktokUsername = !empty($tiktokName) ? $tiktokName : 'TikTok';
                            if (empty($tiktokName)) {
                                $parsedUrl = parse_url($tiktokUrl);
                                if (isset($parsedUrl['path'])) {
                                    $path = trim($parsedUrl['path'], '/');
                                    $segments = explode('/', $path);
                                    $firstSegment = isset($segments[0]) ? trim($segments[0]) : '';
                                    if (!empty($firstSegment) && !in_array(strtolower($firstSegment), ['explore', 'p', 'reels', 'stories', 'share', 't'])) {
                                        if (strpos($firstSegment, '@') !== 0) {
                                            $tiktokUsername = '@' . $firstSegment;
                                        } else {
                                            $tiktokUsername = $firstSegment;
                                        }
                                    }
                                }
                            }
                        @endphp
                        <li class="flex items-center gap-2">
                            <i class="fa-brands fa-tiktok text-lg text-white"></i>
                            <a href="{{ $tiktokUrl }}" target="_blank" class="hover:text-primary-400 transition-colors">
                                {{ $tiktokUsername }}
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between text-xs">
                <p>&copy; {{ date('Y') }} Berkah Mulia Catalog. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="{{ route('catalog.index') }}" class="hover:text-white transition-colors">Katalog Produk</a>
                    <a href="{{ route('admin.login') }}" class="hover:text-white transition-colors">Login Admin</a>
                </div>
            </div>
        </div>
    </footer>

    @php
        $showWhatsappFloating = \App\Models\Setting::get('show_whatsapp_floating', true);
        $backToTopBottom = $showWhatsappFloating ? '80px' : '24px';
    @endphp

    <!-- WhatsApp Floating Button -->
    @if($showWhatsappFloating)
    <a href="{{ $whatsappLink }}" target="_blank" id="whatsapp-floating-button" style="bottom: 24px;"
       class="fixed right-6 z-40 w-12 h-12 bg-emerald-500 hover:bg-emerald-600 text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 group" 
       aria-label="Tanya Admin via WhatsApp">
        <i class="fa-brands fa-whatsapp text-2xl animate-pulse"></i>
        <!-- Tooltip / Label -->
        <span class="absolute bg-slate-900 text-white text-xs font-semibold px-3 py-1.5 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap pointer-events-none shadow-md"
              style="right: calc(100% + 12px); top: 50%; transform: translateY(-50%);">
            Ada Pertanyaan? Chat Kami!
        </span>
        <!-- Pulse ring animation -->
        <span class="absolute inset-0 rounded-full bg-emerald-500/30 animate-ping -z-10"></span>
    </a>
    @endif

    <!-- Back to Top Button -->
    <button id="back-to-top" onclick="scrollToTopWithAnimation()" style="bottom: {{ $backToTopBottom }};" class="fixed right-6 z-40 w-10 h-10 bg-primary-500 hover:bg-primary-600 text-white rounded-full shadow-lg flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 translate-y-4 hover:scale-110 active:scale-95 group" aria-label="Kembali ke atas">
        <i class="fa-solid fa-chevron-up text-sm group-hover:-translate-y-1 transition-transform duration-300"></i>
    </button>

    @yield('scripts')

    <script>
    // Micro smooth scroll animation to top
    function scrollToTopWithAnimation() {
        const startY = window.scrollY;
        if (startY === 0) return;
        
        const duration = 450; // fast & smooth duration in ms
        const startTime = performance.now();

        // Cubic-bezier like easing out
        function easeOutCubic(t) {
            return 1 - Math.pow(1 - t, 3);
        }

        function animate(currentTime) {
            const timeElapsed = currentTime - startTime;
            const progress = Math.min(timeElapsed / duration, 1);
            const ease = easeOutCubic(progress);

            window.scrollTo(0, startY * (1 - ease));

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);
    }

    // Back to Top visibility
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            backToTop.classList.remove('opacity-0','pointer-events-none','translate-y-4');
            backToTop.classList.add('opacity-100','pointer-events-auto','translate-y-0');
        } else {
            backToTop.classList.remove('opacity-100','pointer-events-auto','translate-y-0');
            backToTop.classList.add('opacity-0','pointer-events-none','translate-y-4');
        }
    });

    // Mobile search bar toggle
    const mobileSearchToggle = document.getElementById('mobile-search-toggle');
    const mobileSearchBar = document.getElementById('mobile-search-bar');
    const mobileSearchInput = document.getElementById('mobile-search-input');

    if (mobileSearchToggle && mobileSearchBar) {
        mobileSearchToggle.addEventListener('click', () => {
            const isOpen = mobileSearchBar.classList.contains('max-h-24');
            if (isOpen) {
                mobileSearchBar.classList.remove('max-h-24', 'opacity-100');
                mobileSearchBar.classList.add('max-h-0', 'opacity-0');
            } else {
                mobileSearchBar.classList.remove('max-h-0', 'opacity-0');
                mobileSearchBar.classList.add('max-h-24', 'opacity-100');
                setTimeout(() => mobileSearchInput.focus(), 300);
            }
        });
    }

    // #2 - Form loading state (prevent double-click)
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
                const originalHtml = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Memproses...';
                setTimeout(() => { btn.disabled = false; btn.style.opacity = '1'; btn.innerHTML = originalHtml; }, 8000);
            }
        });
    });
    </script>

    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registered successfully!', reg.scope))
                    .catch(err => console.error('Service Worker registration failed:', err));
            });

            // Reload otomatis jika cache lama dibersihkan oleh Service Worker versi baru
            navigator.serviceWorker.addEventListener('message', event => {
                if (event.data && event.data.type === 'CACHE_CLEARED') {
                    console.log('Cache diperbarui. Memuat ulang halaman...');
                    window.location.reload();
                }
            });
        }
    </script>

    <!-- Quick Add to Cart Modal -->
    <div id="quick-add-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs hidden opacity-0 transition-opacity duration-300 flex items-center justify-center p-4" style="z-index: 1010;" onclick="closeQuickAddModal()">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden transform scale-95 transition-transform duration-300 ease-out border border-slate-100 flex flex-col" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">Pilih Varian</span>
                <button type="button" onclick="closeQuickAddModal()" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer" aria-label="Tutup Modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <!-- Body -->
            <div class="p-5 space-y-4">
                <!-- Product Overview -->
                <div class="flex gap-4 pb-4 border-b border-slate-100">
                    <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 shrink-0 relative">
                        <img id="quick-product-image" src="" alt="" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                        <div class="hidden absolute inset-0 flex-col items-center justify-center bg-slate-100 text-slate-500 p-1">
                            <i class="fa-regular fa-image text-xl mb-0.5"></i>
                            <span class="text-[8px] text-slate-600 font-medium text-center">Gambar tidak tersedia</span>
                        </div>
                    </div>
                    <div class="flex flex-col justify-between py-1">
                        <div>
                            <h3 id="quick-product-name" class="text-xs font-bold text-slate-800 line-clamp-1"></h3>
                            <span id="quick-product-sku" class="text-[9px] text-slate-400 font-semibold tracking-wider uppercase mt-0.5 block"></span>
                        </div>
                        <span id="quick-product-price" class="text-sm font-extrabold text-primary-500"></span>
                    </div>
                </div>

                <!-- Sizes -->
                <div id="quick-size-section">
                    <h4 class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Ukuran:</h4>
                    <div id="quick-sizes-container" class="flex flex-wrap gap-2"></div>
                </div>

                <!-- Colors -->
                <div id="quick-color-section">
                    <h4 class="text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Warna:</h4>
                    <div id="quick-colors-container" class="flex flex-wrap gap-2"></div>
                </div>

                <!-- Stock & Qty Selector -->
                <div class="flex items-center justify-between pt-2 border-t border-slate-100 gap-4">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kuantitas:</span>
                        <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50/50 shadow-inner px-0.5 py-0.5 w-max">
                            <button type="button" onclick="decrementQuickQty()" class="w-8 h-8 flex items-center justify-center text-md font-bold text-slate-500 hover:text-slate-850 hover:bg-slate-150 rounded-lg transition-colors cursor-pointer">-</button>
                            <input type="text" id="quick-product-qty" value="1" readonly class="w-8 text-center font-bold text-slate-750 bg-transparent text-xs border-0 focus:ring-0 select-none">
                            <button type="button" onclick="incrementQuickQty()" class="w-8 h-8 flex items-center justify-center text-md font-bold text-slate-500 hover:text-slate-850 hover:bg-slate-150 rounded-lg transition-colors cursor-pointer">+</button>
                        </div>
                    </div>

                    <!-- Stock indicator inside modal -->
                    <div class="flex-1 text-right">
                        <div id="quick-stock-indicator" class="text-[11px] font-bold text-slate-555 bg-slate-50 py-2 px-3 rounded-lg border border-slate-100 inline-block">
                            Silakan pilih varian...
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer (Confirm Button) -->
            <div class="p-5 border-t border-slate-100 bg-slate-50/50 flex gap-3">
                <button type="button" onclick="closeQuickAddModal()" class="flex-1 py-3 border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold rounded-xl text-[11px] uppercase tracking-wider transition-colors cursor-pointer text-center">
                    Batal
                </button>
                <button type="button" id="quick-add-submit-btn" onclick="submitQuickAddToCart()" class="flex-1 py-3 bg-primary-500 hover:bg-primary-600 text-white font-bold rounded-xl text-[11px] uppercase tracking-wider transition-colors shadow-lg shadow-primary-550/10 cursor-pointer text-center">
                    Tambah
                </button>
            </div>
        </div>
    </div>

    <!-- Shopping Cart Backdrop Overlay -->
    <div id="cart-backdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs hidden opacity-0 transition-opacity duration-300" style="z-index: 1000;" onclick="toggleCartDrawer(false)"></div>

    <!-- Shopping Cart Side Drawer -->
    <div id="cart-drawer" class="fixed right-0 top-0 max-w-md w-full bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out border-l border-slate-100" style="z-index: 1001; height: 100vh; height: 100dvh;">
        <!-- Header -->
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary-50 flex items-center justify-center text-primary-600">
                    <i class="fa-solid fa-shopping-cart text-sm"></i>
                </div>
                <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Keranjang Belanja</h2>
            </div>
            <button type="button" onclick="toggleCartDrawer(false)" class="text-slate-400 hover:text-slate-600 hover:bg-slate-50 w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer" aria-label="Tutup Keranjang Belanja">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Scrollable Item List Container -->
        <div id="cart-items-container" class="flex-1 overflow-y-auto p-5 space-y-4">
            <!-- Cart items dynamically rendered via Javascript -->
        </div>

        <!-- Footer -->
        <div id="cart-footer" class="p-5 border-t border-slate-100 bg-slate-50/50 space-y-4 hidden">
            <div class="flex items-center justify-between text-slate-700">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Subtotal</span>
                <span id="cart-subtotal" class="text-lg font-extrabold text-primary-600">Rp 0</span>
            </div>
            <a id="cart-checkout-btn" href="#" target="_blank"
               class="w-full flex items-center justify-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-500/10 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 text-xs uppercase tracking-wider text-center">
                <i class="fa-brands fa-whatsapp text-lg"></i>
                <span>Kirim Pesanan via WhatsApp</span>
            </a>
        </div>
    </div>

    <!-- Global Cart Script -->
    <script>
        // Cart Global State
        let cart = [];

        // Load cart from localStorage on DOM load
        document.addEventListener("DOMContentLoaded", function() {
            loadCart();
            updateCartBadge();
            renderCart();
        });

        function loadCart() {
            try {
                const stored = localStorage.getItem("berkah_mulia_cart");
                cart = stored ? JSON.parse(stored) : [];
            } catch (e) {
                console.error("Gagal membaca keranjang belanja:", e);
                cart = [];
            }
        }

        function saveCart() {
            try {
                localStorage.setItem("berkah_mulia_cart", JSON.stringify(cart));
                updateCartBadge();
                renderCart();
            } catch (e) {
                console.error("Gagal menyimpan keranjang belanja:", e);
            }
        }

        function updateCartBadge() {
            const badge = document.getElementById("cart-count-badge");
            if (!badge) return;
            const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
            if (totalItems > 0) {
                badge.textContent = totalItems;
                badge.classList.remove("hidden");
            } else {
                badge.classList.add("hidden");
            }
        }

        function toggleCartDrawer(show) {
            const drawer = document.getElementById("cart-drawer");
            const backdrop = document.getElementById("cart-backdrop");
            if (!drawer || !backdrop) return;

            if (show) {
                // Render freshest cart data before opening
                renderCart();
                
                // Adjust height dynamically for mobile Chrome viewport bug
                drawer.style.height = `${window.innerHeight}px`;
                
                backdrop.classList.remove("hidden");
                // Trigger reflow to apply smooth transition
                requestAnimationFrame(() => {
                    backdrop.classList.remove("opacity-0");
                    backdrop.classList.add("opacity-100");
                    drawer.classList.remove("translate-x-full");
                    drawer.classList.add("translate-x-0");
                });
                document.body.classList.add("overflow-hidden");
            } else {
                backdrop.classList.remove("opacity-100");
                backdrop.classList.add("opacity-0");
                drawer.classList.remove("translate-x-0");
                drawer.classList.add("translate-x-full");
                setTimeout(() => {
                    backdrop.classList.add("hidden");
                }, 300);
                document.body.classList.remove("overflow-hidden");
            }
        }

        // Adjust drawer height on window resize to prevent cut-off in mobile browsers when URL bar is toggled
        window.addEventListener('resize', () => {
            const drawer = document.getElementById("cart-drawer");
            if (drawer && !drawer.classList.contains("translate-x-full")) {
                drawer.style.height = `${window.innerHeight}px`;
            }
        });

        // Global Toast Notification function
        function showToast(message, type = 'success') {
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'fixed top-4 left-4 right-4 md:left-auto md:w-96 z-[9999] flex flex-col gap-2 pointer-events-none';
                document.body.appendChild(container);
            }
            
            const toast = document.createElement('div');
            toast.className = 'transform translate-y-[-20px] opacity-0 transition-all duration-300 pointer-events-auto bg-white/95 backdrop-blur-xs border border-slate-100 shadow-xl px-4 py-3.5 rounded-2xl flex items-center gap-3 w-full';
            
            let icon = '<i class="fa-solid fa-circle-check text-emerald-500 text-lg shrink-0"></i>';
            if (type === 'error') {
                icon = '<i class="fa-solid fa-circle-xmark text-rose-500 text-lg shrink-0"></i>';
            }
            
            toast.innerHTML = `
                ${icon}
                <p class="text-xs font-bold text-slate-800 flex-1">${message}</p>
                <button type="button" class="text-slate-400 hover:text-slate-650 transition-colors shrink-0 cursor-pointer">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            `;
            
            // Close button functionality
            toast.querySelector('button').onclick = () => {
                toast.classList.add('opacity-0', 'translate-y-[-20px]');
                setTimeout(() => toast.remove(), 300);
            };
            
            container.appendChild(toast);
            
            // Trigger transition
            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-[-20px]');
                toast.classList.add('opacity-100', 'translate-y-0');
            });
            
            // Auto-dismiss
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.add('opacity-0', 'translate-y-[-20px]');
                    setTimeout(() => {
                        if (toast.parentElement) {
                            toast.remove();
                        }
                    }, 300);
                }
            }, 3000);
        }

        function renderCart() {
            const container = document.getElementById("cart-items-container");
            const footer = document.getElementById("cart-footer");
            const subtotalText = document.getElementById("cart-subtotal");
            const checkoutBtn = document.getElementById("cart-checkout-btn");
            if (!container || !footer || !subtotalText || !checkoutBtn) return;

            if (cart.length === 0) {
                // Render empty state
                container.innerHTML = `
                    <div class="h-full flex flex-col items-center justify-center text-center p-6 select-none grow bg-white">
                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mb-4 border border-slate-100">
                            <i class="fa-solid fa-cart-shopping text-2xl"></i>
                        </div>
                        <h3 class="text-sm font-bold text-slate-700">Keranjang Belanja Kosong</h3>
                        <p class="text-xs text-slate-400 mt-1 max-w-[200px] leading-relaxed">
                            Anda belum menambahkan produk apa pun ke keranjang belanja Anda.
                        </p>
                        <button type="button" onclick="toggleCartDrawer(false)" class="mt-5 inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white text-xs font-bold rounded-xl shadow-xs transition-all cursor-pointer">
                            Mulai Belanja
                        </button>
                    </div>
                `;
                footer.classList.add("hidden");
                return;
            }

            footer.classList.remove("hidden");
            
            let html = "";
            let subtotal = 0;

            cart.forEach((item, index) => {
                const itemTotal = item.price * item.qty;
                subtotal += itemTotal;

                html += `
                    <div class="flex gap-3 bg-white p-3.5 rounded-2xl border border-slate-100 shadow-xs hover:border-slate-200 transition-all select-none group">
                        <!-- Thumbnail -->
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 shrink-0 relative flex items-center justify-center">
                            ${item.image ? `
                                <img src="/storage/${item.image}" alt="${item.name}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                            ` : ''}
                            <div class="${item.image ? 'hidden' : 'flex'} absolute inset-0 flex-col items-center justify-center bg-slate-100 text-slate-500 p-1">
                                <i class="fa-regular fa-image text-lg mb-0.5"></i>
                                <span class="text-[8px] text-slate-600 font-medium text-center leading-tight">Gambar tidak tersedia</span>
                            </div>
                        </div>
                        
                        <!-- Item details -->
                        <div class="flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-1">
                                    <h4 class="text-xs font-bold text-slate-800 line-clamp-1">${item.name}</h4>
                                    <button type="button" onclick="removeCartItem(${index})" class="text-slate-300 hover:text-rose-500 w-5 h-5 flex items-center justify-center rounded-full hover:bg-rose-50 transition-all shrink-0 cursor-pointer">
                                        <i class="fa-regular fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                                <p class="text-[10px] text-slate-400 font-semibold mt-0.5 uppercase tracking-wide">
                                    ${item.size && item.size !== '-' ? `U: ${item.size}` : ''} ${item.color && item.color !== '-' ? ` | W: ${item.color}` : ''}
                                </p>
                            </div>
                            
                            <div class="flex items-center justify-between gap-2 mt-2">
                                <!-- Qty selector -->
                                <div class="flex items-center border border-slate-200 rounded-lg bg-slate-50/50 shadow-inner">
                                    <button type="button" onclick="changeCartQty(${index}, -1)" class="w-6 h-6 flex items-center justify-center text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-l-lg transition-colors cursor-pointer">-</button>
                                    <span class="w-7 text-center text-xs font-bold text-slate-700">${item.qty}</span>
                                    <button type="button" onclick="changeCartQty(${index}, 1)" class="w-6 h-6 flex items-center justify-center text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-r-lg transition-colors cursor-pointer">+</button>
                                </div>
                                <!-- Price -->
                                <span class="text-xs font-extrabold text-primary-500">Rp ${formatRupiah(itemTotal)}</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
            subtotalText.textContent = "Rp " + formatRupiah(subtotal);

            // Generate WhatsApp Checkout Message
            let message = "Halo Berkah Mulia, saya ingin memesan produk-produk berikut:\n\n";
            cart.forEach((item, index) => {
                const itemTotal = item.price * item.qty;
                message += `${index + 1}. *${item.name}*\n`;
                if (item.size && item.size !== '-') message += `   - Ukuran: ${item.size}\n`;
                if (item.color && item.color !== '-') message += `   - Warna: ${item.color}\n`;
                if (item.sku) message += `   - SKU: ${item.sku}\n`;
                message += `   - Qty: ${item.qty} x Rp ${formatRupiah(item.price)} = *Rp ${formatRupiah(itemTotal)}*\n\n`;
            });
            message += `*Total Pemesanan: Rp ${formatRupiah(subtotal)}*\n\nMohon diinfokan ketersediaan stok dan kelanjutan pembayarannya. Terima kasih!`;

            checkoutBtn.href = `https://wa.me/${getWhatsAppAdminNumber()}?text=${encodeURIComponent(message)}`;
        }

        function changeCartQty(index, delta) {
            if (!cart[index]) return;
            cart[index].qty += delta;
            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            } else if (cart[index].qty > 99) {
                cart[index].qty = 99;
            }
            saveCart();
        }

        function removeCartItem(index) {
            if (!cart[index]) return;
            cart.splice(index, 1);
            saveCart();
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat("id-ID").format(number);
        }

        function getWhatsAppAdminNumber() {
            return "{{ config('app.whatsapp_number', '628123456789') }}";
        }

        // Quick Add Modal state variables
        let quickProduct = null;
        let quickSelectedSize = null;
        let quickSelectedColor = null;

        function openQuickAddModal(id, name, price, sku, image, variantsJson) {
            quickProduct = { id, name, price, sku, image, variants: variantsJson };
            quickSelectedSize = null;
            quickSelectedColor = null;

            // Update UI contents
            document.getElementById("quick-product-name").textContent = name;
            document.getElementById("quick-product-sku").textContent = sku;
            document.getElementById("quick-product-price").textContent = "Rp " + formatRupiah(price);
            
            const quickImg = document.getElementById("quick-product-image");
            quickImg.style.display = ""; // Reset display
            if (quickImg.nextElementSibling) {
                quickImg.nextElementSibling.classList.remove("flex");
                quickImg.nextElementSibling.classList.add("hidden");
            }
            quickImg.src = image ? `/storage/${image}` : '';
            
            document.getElementById("quick-product-qty").value = 1;

            // Generate variants lists (unique sizes & colors)
            const uniqueSizes = [...new Set(quickProduct.variants.map(v => v.size).filter(s => s && s !== '-'))];
            const uniqueColors = [...new Set(quickProduct.variants.map(v => v.color).filter(c => c && c !== '-'))];

            const sizeSection = document.getElementById("quick-size-section");
            const sizesContainer = document.getElementById("quick-sizes-container");
            if (uniqueSizes.length > 0) {
                sizeSection.classList.remove("hidden");
                sizesContainer.innerHTML = uniqueSizes.map(s => `
                    <button type="button" onclick="selectQuickSize('${s}', this)" class="quick-size-btn inline-block px-3 py-2 border border-slate-200 text-[11px] font-bold rounded-lg text-slate-600 bg-white hover:border-slate-350 hover:bg-slate-50 transition-all select-none cursor-pointer">
                        ${s}
                    </button>
                `).join("");
            } else {
                sizeSection.classList.add("hidden");
            }

            const colorSection = document.getElementById("quick-color-section");
            const colorsContainer = document.getElementById("quick-colors-container");
            if (uniqueColors.length > 0) {
                colorSection.classList.remove("hidden");
                colorsContainer.innerHTML = uniqueColors.map(c => `
                    <button type="button" onclick="selectQuickColor('${c}', this)" class="quick-color-btn inline-block px-3 py-2 border border-slate-200 text-[11px] font-bold rounded-lg text-slate-600 bg-white hover:border-slate-350 hover:bg-slate-50 transition-all select-none cursor-pointer">
                        ${c}
                    </button>
                `).join("");
            } else {
                colorSection.classList.add("hidden");
            }

            // Reset stock indicator
            updateQuickStockDetails();

            // Show Modal
            const modal = document.getElementById("quick-add-modal");
            modal.classList.remove("hidden");
            requestAnimationFrame(() => {
                modal.classList.remove("opacity-0");
                modal.classList.add("opacity-100");
                const dialog = modal.querySelector(".bg-white");
                if (dialog) {
                    dialog.classList.remove("scale-95");
                    dialog.classList.add("scale-100");
                }
            });
            document.body.classList.add("overflow-hidden");
        }

        function closeQuickAddModal() {
            const modal = document.getElementById("quick-add-modal");
            modal.classList.remove("opacity-100");
            modal.classList.add("opacity-0");
            const dialog = modal.querySelector(".bg-white");
            if (dialog) {
                dialog.classList.remove("scale-100");
                dialog.classList.add("scale-95");
            }
            setTimeout(() => {
                modal.classList.add("hidden");
            }, 300);
            document.body.classList.remove("overflow-hidden");
        }

        function selectQuickSize(size, btn) {
            quickSelectedSize = size;
            const btns = document.querySelectorAll(".quick-size-btn");
            btns.forEach(b => {
                b.classList.remove("border-primary-500", "text-primary-600", "bg-primary-50/40");
                b.classList.add("border-slate-200", "text-slate-600", "bg-white");
            });
            btn.classList.remove("border-slate-200", "text-slate-600", "bg-white");
            btn.classList.add("border-primary-500", "text-primary-600", "bg-primary-50/40");
            updateQuickStockDetails();
        }

        function selectQuickColor(color, btn) {
            quickSelectedColor = color;
            const btns = document.querySelectorAll(".quick-color-btn");
            btns.forEach(b => {
                b.classList.remove("border-primary-500", "text-primary-600", "bg-primary-50/40");
                b.classList.add("border-slate-200", "text-slate-600", "bg-white");
            });
            btn.classList.remove("border-slate-200", "text-slate-600", "bg-white");
            btn.classList.add("border-primary-500", "text-primary-600", "bg-primary-50/40");
            updateQuickStockDetails();
        }

        function incrementQuickQty() {
            const qtyInput = document.getElementById("quick-product-qty");
            let qty = parseInt(qtyInput.value) || 1;
            if (qty < 99) {
                qtyInput.value = qty + 1;
            }
        }

        function decrementQuickQty() {
            const qtyInput = document.getElementById("quick-product-qty");
            let qty = parseInt(qtyInput.value) || 1;
            if (qty > 1) {
                qtyInput.value = qty - 1;
            }
        }

        function updateQuickStockDetails() {
            if (!quickProduct) return;
            const hasSizes = document.getElementById("quick-size-section").classList.contains("hidden") === false;
            const hasColors = document.getElementById("quick-color-section").classList.contains("hidden") === false;

            const sizeSelected = !hasSizes || quickSelectedSize !== null;
            const colorSelected = !hasColors || quickSelectedColor !== null;

            const stockIndicator = document.getElementById("quick-stock-indicator");
            const submitBtn = document.getElementById("quick-add-submit-btn");

            if (sizeSelected && colorSelected) {
                const sizeVal = quickSelectedSize || '-';
                const colorVal = quickSelectedColor || '-';

                const match = quickProduct.variants.find(v => {
                    const sizeMatch = !hasSizes || v.size === sizeVal;
                    const colorMatch = !hasColors || v.color === colorVal;
                    return sizeMatch && colorMatch;
                });

                if (match) {
                    const stock = match.stock;
                    if (stock > 0) {
                        stockIndicator.innerHTML = `<span class="text-emerald-600 font-bold"><i class="fa-solid fa-circle-check mr-1"></i>Stok: ${stock} pcs</span>`;
                        submitBtn.disabled = false;
                        submitBtn.classList.remove("opacity-50", "pointer-events-none");
                    } else {
                        stockIndicator.innerHTML = `<span class="text-rose-500 font-bold"><i class="fa-solid fa-circle-xmark mr-1"></i>Stok Habis</span>`;
                        submitBtn.disabled = true;
                        submitBtn.classList.add("opacity-50", "pointer-events-none");
                    }
                } else {
                    stockIndicator.innerHTML = `<span class="text-amber-500 font-semibold"><i class="fa-solid fa-circle-exclamation mr-1"></i>Tidak Tersedia</span>`;
                    submitBtn.disabled = true;
                    submitBtn.classList.add("opacity-50", "pointer-events-none");
                }
            } else {
                stockIndicator.innerHTML = `<span class="text-slate-400 font-medium">Pilih varian...</span>`;
                submitBtn.disabled = true;
                submitBtn.classList.add("opacity-50", "pointer-events-none");
            }
        }

        function submitQuickAddToCart() {
            if (!quickProduct) return;
            const hasSizes = document.getElementById("quick-size-section").classList.contains("hidden") === false;
            const hasColors = document.getElementById("quick-color-section").classList.contains("hidden") === false;

            const sizeSelected = !hasSizes || quickSelectedSize !== null;
            const colorSelected = !hasColors || quickSelectedColor !== null;

            if (!sizeSelected || !colorSelected) return;

            const sizeVal = quickSelectedSize || '-';
            const colorVal = quickSelectedColor || '-';

            const match = quickProduct.variants.find(v => {
                const sizeMatch = !hasSizes || v.size === sizeVal;
                const colorMatch = !hasColors || v.color === colorVal;
                return sizeMatch && colorMatch;
            });

            if (!match || match.stock <= 0) return;

            const qtyInput = document.getElementById("quick-product-qty");
            const qtyToAdd = parseInt(qtyInput.value) || 1;

            if (qtyToAdd > match.stock) {
                alert(`Kuantitas melebihi stok yang tersedia (Maks. ${match.stock} pcs)`);
                return;
            }

            let existingIndex = -1;
            for (let i = 0; i < cart.length; i++) {
                if (cart[i].id === quickProduct.id && cart[i].size === sizeVal && cart[i].color === colorVal) {
                    existingIndex = i;
                    break;
                }
            }

            if (existingIndex !== -1) {
                const newQty = cart[existingIndex].qty + qtyToAdd;
                if (newQty > match.stock) {
                    cart[existingIndex].qty = match.stock;
                } else {
                    cart[existingIndex].qty = newQty;
                }
            } else {
                const newItem = {
                    id: quickProduct.id,
                    name: quickProduct.name,
                    qty: qtyToAdd,
                    price: quickProduct.price,
                    size: sizeVal,
                    color: colorVal,
                    sku: quickProduct.sku,
                    image: quickProduct.image
                };
                cart.push(newItem);
            }

            saveCart();
            closeQuickAddModal();
            showToast(`Berhasil menambahkan "${quickProduct.name}" ke keranjang!`);
        }
    </script>
</body>
</html>
