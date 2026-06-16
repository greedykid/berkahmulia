<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') | Berkah Mulia</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Compiled Tailwind CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- FontAwesome for icons (asynchronous load to prevent render blocking) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-brands-400.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/webfonts/fa-solid-900.woff2" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    
</head>
<body class="bg-slate-100 text-slate-800 font-sans h-screen h-dvh flex flex-col md:flex-row overflow-hidden">

@php
    $outOfStockBadge = \App\Models\ProductVariant::whereHas('product', function($query) {
        $query->whereNull('deleted_at');
    })->where('stock', 0)->count();
@endphp

    <!-- Mobile Header -->
    <header class="bg-slate-900 text-white p-4 md:hidden flex justify-between items-center z-40 shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-0.5 text-sm tracking-tight leading-none select-none">
            <span class="logo-letter text-apricot-cream-300">B</span>
            <span class="logo-letter text-pearl-aqua-300">e</span>
            <span class="logo-letter text-sky-blue-300">r</span>
            <span class="logo-letter text-vanilla-custard-300">k</span>
            <span class="logo-letter text-thistle-300">a</span>
            <span class="logo-letter text-frozen-water-300">h</span>
            <span class="w-0.5"></span>
            <span class="logo-letter text-peach-fuzz-300">M</span>
            <span class="logo-letter text-frozen-water-u-300">u</span>
            <span class="logo-letter text-vanilla-custard-l-300">l</span>
            <span class="logo-letter text-thistle-300">i</span>
            <span class="logo-letter text-thistle-300">a</span>
        </a>
        <button id="mobile-menu-btn" class="text-slate-400 hover:text-white focus:outline-none" aria-label="Toggle menu">
            <i class="fa-solid fa-bars text-xl" id="mobile-menu-icon"></i>
        </button>
    </header>

    <!-- Mobile Drawer Overlay -->
    <div id="mobile-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 hidden opacity-0 transition-opacity duration-300 md:hidden" onclick="closeMobileDrawer()"></div>

    <!-- Mobile Drawer Sidebar -->
    <aside id="mobile-drawer" class="fixed top-0 left-0 h-dvh w-72 bg-slate-900 text-slate-300 z-50 flex flex-col justify-between py-6 px-4 transform -translate-x-full transition-transform duration-300 ease-out md:hidden overflow-y-auto">
        <!-- Close button -->
        <div class="absolute top-4 right-4">
            <button onclick="closeMobileDrawer()" class="text-slate-400 hover:text-white p-1">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="space-y-8">
            <!-- Brand -->
            <div class="flex items-center gap-3 px-2">
                <img src="{{ asset('logo.webp') }}" alt="Logo" class="h-10 w-auto rounded-lg shadow-sm shrink-0 object-cover">
                <div class="flex flex-col">
                    <span class="flex items-center gap-0.5 text-sm tracking-tight leading-none select-none mb-0.5">
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
                    <span class="text-[8px] text-slate-400 uppercase tracking-wider mt-0.5 font-semibold">Admin Panel</span>
                </div>
            </div>

            <!-- Nav Links -->
            <nav class="space-y-1.5">
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line text-lg w-5 text-center shrink-0"></i>
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-tags text-lg w-5 text-center shrink-0"></i>
                    <span>Kelola Kategori</span>
                </a>
                
                <a href="{{ route('admin.products.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked text-lg w-5 text-center shrink-0"></i>
                    <span class="flex-1">Kelola Produk</span>
                    @if($outOfStockBadge > 0)
                        <span class="bg-rose-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-none">{{ $outOfStockBadge }}</span>
                    @endif
                </a>

                <div class="space-y-0.5">
                    <button type="button" onclick="toggleMobileSettingsMenu()"
                       class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-sliders text-lg w-5 text-center shrink-0"></i>
                        <span class="flex-1 text-left">Pengaturan Toko</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 {{ request()->routeIs('admin.settings.*') ? 'rotate-180' : '' }}" id="mobile-settings-chevron"></i>
                    </button>
                    <div id="mobile-settings-submenu" class="pl-8 space-y-0.5 {{ request()->routeIs('admin.settings.*') ? '' : 'hidden' }}">
                        <a href="{{ route('admin.settings.banner') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.banner') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-images mr-2 text-[10px]"></i>Kelola Banner
                        </a>
                        <a href="{{ route('admin.settings.lokasi') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.lokasi') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-map-pin mr-2 text-[10px]"></i>Lokasi Toko
                        </a>
                        <a href="{{ route('admin.settings.kontak') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.kontak') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-brands fa-whatsapp mr-2 text-[10px]"></i>Kontak Toko
                        </a>
                        <a href="{{ route('admin.settings.panduanUkuran') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.panduanUkuran') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-ruler mr-2 text-[10px]"></i>Panduan Ukuran
                        </a>
                        <a href="{{ route('admin.settings.password') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.password') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-lock mr-2 text-[10px]"></i>Ubah Password
                        </a>
                    </div>
                </div>
                
                <hr class="border-slate-800 my-4">
                
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-globe text-lg w-5 text-center text-slate-500 shrink-0"></i>
                    <span>Lihat Halaman Toko</span>
                </a>
            </nav>
        </div>

        <!-- Profile & Logout -->
        <div class="pt-4 border-t border-slate-800 space-y-4">
            <div class="px-2 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold text-sm uppercase shrink-0">A</div>
                <div class="truncate text-xs">
                    <p class="text-white font-medium truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-slate-500 truncate text-[10px]">{{ Auth::user()->email ?? 'admin@bmberkahmulia.com' }}</p>
                </div>
            </div>
            <a href="{{ route('admin.logout') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all bg-red-950/40 text-red-400 hover:bg-red-900 hover:text-white w-full">
                <i class="fa-solid fa-right-from-bracket text-lg w-5 text-center shrink-0"></i>
                <span>Keluar Akun</span>
            </a>
        </div>
    </aside>

    <!-- Desktop Sidebar Navigation -->
    <aside id="sidebar" class="bg-slate-900 text-slate-300 h-screen h-dvh sticky top-0 hidden md:flex flex-col justify-between py-6 px-4 transition-all duration-300 z-30 shrink-0 md:w-64">
        <div class="space-y-8">
            <!-- Brand & Toggle Button -->
            <div class="flex items-center justify-between px-2 min-h-[40px]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset('logo.webp') }}" alt="Logo" class="h-10 w-auto rounded-lg shadow-sm shrink-0 object-cover">
                    <div class="flex flex-col sidebar-expanded-only">
                        <span class="flex items-center gap-0.5 text-sm tracking-tight leading-none select-none mb-0.5">
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
                        <span class="text-[8px] text-slate-400 uppercase tracking-wider mt-0.5 font-semibold">Admin Panel</span>
                    </div>
                </a>
            </div>

            <!-- Nav Links -->
            <nav class="space-y-1.5">
                <a href="{{ route('admin.dashboard') }}" title="Dashboard"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-line text-lg w-5 text-center shrink-0"></i>
                    <span class="sidebar-expanded-only truncate">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" title="Kelola Kategori"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-tags text-lg w-5 text-center shrink-0"></i>
                    <span class="sidebar-expanded-only truncate">Kelola Kategori</span>
                </a>
                
                <a href="{{ route('admin.products.index') }}" title="Kelola Produk"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked text-lg w-5 text-center shrink-0"></i>
                    <span class="sidebar-expanded-only truncate flex-1">Kelola Produk</span>
                    @if($outOfStockBadge > 0)
                        <span class="sidebar-expanded-only bg-rose-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center leading-none">{{ $outOfStockBadge }}</span>
                    @endif
                </a>

                <div class="space-y-0.5">
                    <!-- Collapsed: simple link -->
                    <a href="{{ route('admin.settings.banner') }}" title="Pengaturan Toko"
                       class="sidebar-collapsed-only hidden items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-sliders text-lg w-5 text-center shrink-0"></i>
                    </a>
                    <!-- Expanded: dropdown button -->
                    <button type="button" onclick="toggleSettingsMenu()" title="Pengaturan Toko"
                       class="sidebar-expanded-only w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white shadow-sm' : 'hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-sliders text-lg w-5 text-center shrink-0"></i>
                        <span class="truncate flex-1 text-left">Pengaturan Toko</span>
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200 {{ request()->routeIs('admin.settings.*') ? 'rotate-180' : '' }}" id="settings-chevron"></i>
                    </button>
                    <div id="settings-submenu" class="sidebar-expanded-only pl-8 space-y-0.5 {{ request()->routeIs('admin.settings.*') ? '' : 'hidden' }}">
                        <a href="{{ route('admin.settings.banner') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.banner') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-images mr-2 text-[10px]"></i>Kelola Banner
                        </a>
                        <a href="{{ route('admin.settings.lokasi') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.lokasi') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-map-pin mr-2 text-[10px]"></i>Lokasi Toko
                        </a>
                        <a href="{{ route('admin.settings.kontak') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.kontak') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-brands fa-whatsapp mr-2 text-[10px]"></i>Kontak Toko
                        </a>
                        <a href="{{ route('admin.settings.panduanUkuran') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.panduanUkuran') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-ruler mr-2 text-[10px]"></i>Panduan Ukuran
                        </a>
                        <a href="{{ route('admin.settings.password') }}" 
                           class="block px-3 py-2 rounded-lg text-xs font-medium transition-all {{ request()->routeIs('admin.settings.password') ? 'bg-slate-700 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                            <i class="fa-solid fa-lock mr-2 text-[10px]"></i>Ubah Password
                        </a>
                    </div>
                </div>
                
                <hr class="border-slate-800 my-4">
                
                <a href="{{ route('home') }}" target="_blank" title="Lihat Halaman Toko"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all hover:bg-slate-800 hover:text-white">
                    <i class="fa-solid fa-globe text-lg w-5 text-center text-slate-500 shrink-0"></i>
                    <span class="sidebar-expanded-only truncate">Lihat Halaman Toko</span>
                </a>
            </nav>
        </div>

        <!-- Logout & Profile Account Info -->
        <div class="pt-4 border-t border-slate-800 space-y-4">
            <div class="px-2 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold text-sm uppercase shrink-0">
                    A
                </div>
                <div class="truncate text-xs sidebar-expanded-only">
                    <p class="text-white font-medium truncate">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-slate-500 truncate text-[10px]">{{ Auth::user()->email ?? 'admin@bmberkahmulia.com' }}</p>
                </div>
            </div>
            
            <a href="{{ route('admin.logout') }}" title="Keluar Akun"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all bg-red-950/40 text-red-400 hover:bg-red-900 hover:text-white w-full">
                <i class="fa-solid fa-right-from-bracket text-lg w-5 text-center shrink-0"></i>
                <span class="sidebar-expanded-only truncate">Keluar Akun</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="grow flex flex-col min-w-0 h-screen h-dvh overflow-hidden">
        <!-- Top Navbar in desktop (Title block) -->
        <header class="bg-white border-b border-slate-200 hidden md:flex items-center justify-between px-8 py-4 shrink-0">
            <div class="flex items-center gap-4">
                <!-- Desktop Sidebar Toggle Button -->
                <button id="sidebar-toggle-btn" class="text-slate-500 hover:text-slate-800 focus:outline-none p-2 rounded-lg hover:bg-slate-100 transition-colors flex items-center justify-center" title="Toggle Sidebar">
                    <i class="fa-solid fa-bars text-lg" id="sidebar-toggle-icon"></i>
                </button>
                <h1 class="font-semibold text-lg text-slate-800">@yield('page_title', 'Dashboard')</h1>
            </div>
            <div class="text-sm text-slate-500 flex items-center gap-2">
                <i class="fa-solid fa-calendar text-slate-400"></i>
                <span>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </header>

        <!-- Main Body Page (Independently scrollable) -->
        <main class="grow p-4 md:p-8 overflow-y-auto">
            <!-- Toast Notifications (auto-dismiss) -->
            @if(session('success'))
                <div id="toast-success" class="fixed top-4 right-4 z-[60] bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in max-w-sm">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg shrink-0"></i>
                    <p class="text-sm font-medium flex-1">{{ session('success') }}</p>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 shrink-0"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif
            
            @if(session('error'))
                <div id="toast-error" class="fixed top-4 right-4 z-[60] bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in max-w-sm">
                    <i class="fa-solid fa-circle-xmark text-rose-500 text-lg shrink-0"></i>
                    <p class="text-sm font-medium flex-1">{{ session('error') }}</p>
                    <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600 shrink-0"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Toggle Sidebar & Mobile Menu Javascript -->
    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenuIcon = document.getElementById('mobile-menu-icon');
        const mobileDrawer = document.getElementById('mobile-drawer');
        const mobileOverlay = document.getElementById('mobile-overlay');
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebar-toggle-btn');
        const sidebarToggleIcon = document.getElementById('sidebar-toggle-icon');

        // Mobile Drawer Open/Close
        function openMobileDrawer() {
            mobileOverlay.classList.remove('hidden');
            requestAnimationFrame(() => {
                mobileOverlay.classList.remove('opacity-0');
                mobileOverlay.classList.add('opacity-100');
                mobileDrawer.classList.remove('-translate-x-full');
                mobileDrawer.classList.add('translate-x-0');
            });
            mobileMenuIcon.classList.remove('fa-bars');
            mobileMenuIcon.classList.add('fa-xmark');
        }

        function closeMobileDrawer() {
            mobileOverlay.classList.remove('opacity-100');
            mobileOverlay.classList.add('opacity-0');
            mobileDrawer.classList.remove('translate-x-0');
            mobileDrawer.classList.add('-translate-x-full');
            mobileMenuIcon.classList.remove('fa-xmark');
            mobileMenuIcon.classList.add('fa-bars');
            setTimeout(() => {
                mobileOverlay.classList.add('hidden');
            }, 300);
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                const isOpen = mobileDrawer.classList.contains('translate-x-0');
                if (isOpen) {
                    closeMobileDrawer();
                } else {
                    openMobileDrawer();
                }
            });
        }

        // Mobile settings submenu toggle
        function toggleMobileSettingsMenu() {
            const submenu = document.getElementById('mobile-settings-submenu');
            const chevron = document.getElementById('mobile-settings-chevron');
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                submenu.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }

        // Desktop Collapsible Sidebar
        const expandedElements = document.querySelectorAll('.sidebar-expanded-only');
        const collapsedElements = document.querySelectorAll('.sidebar-collapsed-only');

        function toggleSidebar(isCollapsed) {
            if (isCollapsed) {
                // Collapse sidebar
                sidebar.classList.remove('md:w-64');
                sidebar.classList.add('md:w-20');
                
                expandedElements.forEach(el => { el.classList.add('hidden'); });
                collapsedElements.forEach(el => { el.classList.remove('hidden'); });
                
                localStorage.setItem('sidebar_collapsed', '1');
            } else {
                // Expand sidebar
                sidebar.classList.remove('md:w-20');
                sidebar.classList.add('md:w-64');
                
                expandedElements.forEach(el => { el.classList.remove('hidden'); });
                collapsedElements.forEach(el => { el.classList.add('hidden'); });
                
                localStorage.setItem('sidebar_collapsed', '0');
            }
        }

        if (sidebarToggleBtn) {
            sidebarToggleBtn.addEventListener('click', () => {
                const isCollapsed = sidebar.classList.contains('md:w-20');
                toggleSidebar(!isCollapsed);
            });
        }

        // Initialize state from local storage on load
        document.addEventListener("DOMContentLoaded", function() {
            // Apply immediately to prevent layout shifts
            const isCollapsed = localStorage.getItem('sidebar_collapsed') === '1';
            toggleSidebar(isCollapsed);
        });

        // Settings submenu toggle
        function toggleSettingsMenu() {
            const submenu = document.getElementById('settings-submenu');
            const chevron = document.getElementById('settings-chevron');
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                chevron.classList.add('rotate-180');
            } else {
                submenu.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }
        }
    </script>

    <!-- Custom Delete Confirmation Modal -->
    <div id="delete-confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none opacity-0 transition-opacity duration-300" aria-labelledby="delete-modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeDeleteModal()"></div>
        <div id="delete-modal-panel" class="relative z-10 bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform scale-95 transition-all duration-300 ease-out w-full max-w-sm p-6 sm:p-8 flex flex-col items-center">
            <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 id="delete-modal-title" class="text-base font-bold text-slate-800 text-center mb-2">Konfirmasi Hapus</h3>
            <p id="delete-modal-message" class="text-xs text-slate-500 text-center mb-6 leading-relaxed">Apakah Anda yakin ingin menghapus data ini?</p>
            <div class="flex gap-3 w-full">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold py-2.5 rounded-xl transition-all text-xs text-center cursor-pointer">Batal</button>
                <button type="button" onclick="executeDelete()" class="flex-1 bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-sm text-xs text-center cursor-pointer">Hapus</button>
            </div>
        </div>
    </div>

    <!-- Generic Confirmation Modal (for bulk actions etc.) -->
    <div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none opacity-0 transition-opacity duration-300" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeConfirmModal()"></div>
        <div id="confirm-modal-panel" class="relative z-10 bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform scale-95 transition-all duration-300 ease-out w-full max-w-sm p-6 sm:p-8 flex flex-col items-center">
            <div id="confirm-modal-icon" class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <h3 id="confirm-modal-title" class="text-base font-bold text-slate-800 text-center mb-2">Konfirmasi</h3>
            <p id="confirm-modal-message" class="text-xs text-slate-500 text-center mb-6 leading-relaxed"></p>
            <div class="flex gap-3 w-full">
                <button type="button" onclick="closeConfirmModal()" class="flex-1 border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold py-2.5 rounded-xl transition-all text-xs text-center cursor-pointer">Batal</button>
                <button type="button" id="confirm-modal-btn" onclick="executeConfirm()" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-sm text-xs text-center cursor-pointer">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
        // Delete Confirmation Modal
        let deleteFormToSubmit = null;

        function confirmDelete(button, message = 'Apakah Anda yakin ingin menghapus data ini?') {
            deleteFormToSubmit = button.closest('form');
            const modal = document.getElementById('delete-confirm-modal');
            const panel = document.getElementById('delete-modal-panel');
            const messageEl = document.getElementById('delete-modal-message');
            if (modal && messageEl) {
                messageEl.textContent = message;
                modal.classList.remove('pointer-events-none');
                requestAnimationFrame(() => { modal.classList.remove('opacity-0'); modal.classList.add('opacity-100'); panel.classList.remove('scale-95'); panel.classList.add('scale-100'); });
            }
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-confirm-modal');
            const panel = document.getElementById('delete-modal-panel');
            if (modal) { modal.classList.remove('opacity-100'); modal.classList.add('opacity-0'); panel.classList.remove('scale-100'); panel.classList.add('scale-95'); setTimeout(() => modal.classList.add('pointer-events-none'), 300); }
            deleteFormToSubmit = null;
        }

        function executeDelete() { if (deleteFormToSubmit) deleteFormToSubmit.submit(); }

        // Generic Confirmation Modal
        let confirmCallback = null;

        function showConfirmModal(title, message, btnText, btnClass, callback) {
            confirmCallback = callback;
            document.getElementById('confirm-modal-title').textContent = title;
            document.getElementById('confirm-modal-message').textContent = message;
            const btn = document.getElementById('confirm-modal-btn');
            btn.textContent = btnText || 'Ya, Lanjutkan';
            btn.className = `flex-1 font-bold py-2.5 rounded-xl transition-all shadow-sm text-xs text-center cursor-pointer ${btnClass || 'bg-indigo-600 hover:bg-indigo-700 text-white'}`;
            const modal = document.getElementById('confirm-modal');
            const panel = document.getElementById('confirm-modal-panel');
            modal.classList.remove('pointer-events-none');
            requestAnimationFrame(() => { modal.classList.remove('opacity-0'); modal.classList.add('opacity-100'); panel.classList.remove('scale-95'); panel.classList.add('scale-100'); });
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirm-modal');
            const panel = document.getElementById('confirm-modal-panel');
            modal.classList.remove('opacity-100'); modal.classList.add('opacity-0'); panel.classList.remove('scale-100'); panel.classList.add('scale-95');
            setTimeout(() => modal.classList.add('pointer-events-none'), 300);
            confirmCallback = null;
        }

        function executeConfirm() { if (confirmCallback) confirmCallback(); closeConfirmModal(); }

        document.addEventListener('keydown', function(event) { if (event.key === 'Escape') { closeDeleteModal(); closeConfirmModal(); } });
    </script>

    <script>
    // Auto-dismiss toasts after 4 seconds
    ['toast-success','toast-error'].forEach(id => {
        const el = document.getElementById(id);
        if (el) { setTimeout(() => { el.style.opacity='0'; el.style.transform='translateX(20px)'; el.style.transition='all 0.3s ease'; setTimeout(() => el.remove(), 300); }, 4000); }
    });

    // Form loading state (prevent double-click) for admin
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button[type="submit"]');
            if (btn && !btn.disabled) {
                btn.disabled = true;
                btn.style.opacity = '0.7';
                const orig = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Memproses...';
                setTimeout(() => { btn.disabled = false; btn.style.opacity = '1'; btn.innerHTML = orig; }, 8000);
            }
        });
    });
    </script>

    @yield('scripts')
</body>
</html>
