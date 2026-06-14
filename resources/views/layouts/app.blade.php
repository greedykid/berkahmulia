<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Katalog') | Berkah Mulia - Pakaian Bayi, Anak-anak & Underwear</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="@yield('meta_description', 'Pusat grosir dan eceran pakaian bayi, anak-anak, dan underwear berkualitas premium dengan harga bersahabat di Berkah Mulia.')">
    
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
    
    <!-- FontAwesome for icons (asynchronous load to prevent render blocking) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-regular-400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    
    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen">

    <!-- Top Announcement Bar -->
    <div id="announcement-bar" class="bg-primary-600 text-white text-xs py-2 px-4 text-center font-medium tracking-wide relative">
        <i class="fa-solid fa-gift mr-1 animate-pulse"></i>
        Selamat datang di Berkah Mulia! Koleksi Pakaian Bayi, Anak-anak & Pakaian Dalam Terbaik.
        <button onclick="document.getElementById('announcement-bar').style.display='none'" class="absolute right-3 top-1/2 -translate-y-1/2 text-white hover:text-slate-200 transition-colors" aria-label="Tutup">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>

    <!-- Header Section -->
    <header class="bg-white border-b border-slate-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20 gap-3 sm:gap-4">
                
                <!-- Logo -->
                <div class="shrink-0" id="header-logo">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3">
                        <img src="{{ asset('logo.webp') }}" alt="Berkah Mulia Logo" width="48" height="48" class="h-10 sm:h-12 w-auto rounded-xl shadow-sm border border-slate-100 object-cover">
                        <div class="flex flex-col">
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

                    <!-- WhatsApp CTA -->
                    <a href="https://wa.me/{{ config('app.whatsapp_number', '628123456789') }}" target="_blank" aria-label="Hubungi Admin Berkah Mulia via WhatsApp"
                       class="flex items-center gap-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 px-3.5 sm:px-4 py-2.5 rounded-full text-xs font-semibold border border-emerald-200 transition-all">
                        <i class="fa-brands fa-whatsapp text-lg text-emerald-500"></i>
                        <span class="hidden md:inline">Hubungi Admin</span>
                    </a>
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
                        <img src="{{ asset('logo.webp') }}" alt="Berkah Mulia Logo" width="40" height="40" class="h-10 w-auto rounded-lg shadow-sm border border-slate-700 object-cover">
                        <span class="text-lg font-bold bg-linear-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent">
                            Berkah Mulia
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
                            <span>Jl. Raya Berkah Mulia No. 123, Indonesia</span>
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

    <!-- Back to Top Button -->
    <button id="back-to-top" onclick="scrollToTopWithAnimation()" class="fixed bottom-6 right-6 z-40 w-10 h-10 bg-primary-500 hover:bg-primary-600 text-white rounded-full shadow-lg flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 translate-y-4 hover:scale-110 active:scale-95 group" aria-label="Kembali ke atas">
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
</body>
</html>
