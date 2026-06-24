@extends('layouts.app')

@section('title', 'Katalog Produk')

@php
    $canonicalParams = [];
    if (request()->has('category')) {
        $canonicalParams['category'] = request('category');
    } elseif (request()->has('categories')) {
        $canonicalParams['categories'] = request('categories');
    }
    $canonicalUrl = route('catalog.index', $canonicalParams);
@endphp
@section('canonical_url', $canonicalUrl)

@section('content')
<div class="bg-slate-50 border-b border-slate-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs text-slate-400 mb-2 gap-2">
            <a href="{{ route('home') }}" class="hover:text-slate-600">Beranda</a>
            <span>/</span>
            <span class="text-slate-600 font-medium">Katalog</span>
        </nav>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Katalog Koleksi Pakaian</h1>
        <p class="text-sm text-slate-500 mt-1">Menampilkan koleksi pakaian bayi, balita, anak, dan pakaian dalam premium kami.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-10">
    <form action="{{ route('catalog.index') }}" method="GET" id="filter-form">
        <!-- Preserve search query -->
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif

        <div class="flex flex-col lg:flex-row gap-4 sm:gap-8">
            
            <!-- Sidebar Filters (Left side 1/4) -->
            <aside class="w-full lg:w-64 shrink-0">

                <!-- Drawer Backdrop (Mobile Only) -->
                <div id="filter-drawer-backdrop" class="fixed inset-x-0 bottom-0 bg-slate-900/50 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity duration-300 opacity-0 top-[108px] sm:top-[128px]"></div>

                <!-- Filters Wrapper (Drawer on Mobile, Sidebar on Desktop) -->
                <div id="filters-container" class="fixed bottom-0 left-0 z-50 w-80 max-w-[calc(100vw-3rem)] bg-white shadow-2xl flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out overflow-y-auto lg:translate-x-0 lg:sticky lg:top-36 lg:w-auto lg:h-auto lg:bg-transparent lg:shadow-none lg:z-auto lg:space-y-4 lg:flex lg:overflow-y-visible top-[108px] sm:top-[128px]">
                    
                    <!-- Drawer Header (Mobile Only) -->
                    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 lg:hidden shrink-0">
                        <span class="font-bold text-slate-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-sliders text-primary-500 text-xs"></i>
                            Filter Produk
                        </span>
                        <button type="button" id="mobile-filter-close" class="text-slate-400 hover:text-slate-655 p-1.5 rounded-lg hover:bg-slate-50 transition-colors">
                            <i class="fa-solid fa-xmark text-lg"></i>
                        </button>
                    </div>

                    <!-- Sidebar Header (Desktop Only) -->
                    <div class="hidden lg:flex items-center gap-2.5 pb-1 pl-1 shrink-0 select-none">
                        <i class="fa-solid fa-sliders text-primary-500 text-sm"></i>
                        <span class="font-bold text-slate-800 text-xs uppercase tracking-wider">Filter Produk</span>
                    </div>

                    <!-- Unified Filter Card -->
                    <div class="bg-white border-0 lg:border border-slate-200 rounded-none lg:rounded-2xl shadow-none lg:shadow-sm overflow-hidden flex flex-col grow lg:grow-0">
                        <div class="p-5 sm:p-6 space-y-6 overflow-y-auto lg:overflow-visible grow">
                            <!-- Categories list -->
                            <details class="group select-none" open>
                                <summary class="flex items-center justify-between font-bold text-slate-800 text-xs uppercase tracking-wider cursor-pointer list-none pb-2 border-b border-slate-100 [&::-webkit-details-marker]:hidden">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-tags text-slate-400 text-[10px]"></i>
                                        <span>Kategori</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-450 group-open:rotate-180 transition-transform duration-200"></i>
                                </summary>
                                <div class="mt-4 space-y-2 text-sm max-h-44 overflow-y-auto thin-scrollbar pr-1">
                                    @foreach($categories as $cat)
                                        @php
                                            $isChecked = (is_array(request('categories')) && in_array($cat->slug, request('categories'))) || (request('category') === $cat->slug);
                                        @endphp
                                        <label class="flex items-center gap-3 p-2.5 rounded-xl border cursor-pointer transition-all duration-200 select-none group {{ $isChecked ? 'border-primary-200 bg-primary-50/40 text-primary-900 shadow-sm' : 'border-slate-100 bg-white text-slate-700 hover:border-slate-200 hover:bg-slate-50' }}">
                                            <input type="checkbox" name="categories[]" value="{{ $cat->slug }}" 
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   onchange="handleFilterChange(this);"
                                                   class="rounded border-slate-300 text-primary-500 focus:ring-primary-400 w-4 h-4 cursor-pointer transition-all">
                                            <span class="text-xs font-semibold {{ $isChecked ? 'text-primary-600' : 'text-slate-600 group-hover:text-slate-800' }}">{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </details>

                            <!-- Size Checkboxes -->
                            @if(count($availableSizes) > 0)
                                <details class="group select-none" open>
                                    <summary class="flex items-center justify-between font-bold text-slate-800 text-xs uppercase tracking-wider cursor-pointer list-none pb-2 border-b border-slate-100 [&::-webkit-details-marker]:hidden">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-ruler-horizontal text-slate-400 text-[10px]"></i>
                                            <span>Ukuran</span>
                                        </div>
                                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-450 group-open:rotate-180 transition-transform duration-200"></i>
                                    </summary>
                                    <div class="mt-4 space-y-2 text-sm max-h-44 overflow-y-auto thin-scrollbar pr-1">
                                        @foreach($availableSizes as $size)
                                            @php
                                                $sizeChecked = is_array(request('sizes')) && in_array($size, request('sizes'));
                                            @endphp
                                            <label class="flex items-center gap-3 p-2.5 rounded-xl border cursor-pointer transition-all duration-200 select-none group {{ $sizeChecked ? 'border-primary-200 bg-primary-50/40 text-primary-900 shadow-sm' : 'border-slate-100 bg-white text-slate-700 hover:border-slate-200 hover:bg-slate-50' }}">
                                                <input type="checkbox" name="sizes[]" value="{{ $size }}" 
                                                       {{ $sizeChecked ? 'checked' : '' }}
                                                       onchange="handleFilterChange(this);"
                                                       class="rounded border-slate-300 text-primary-500 focus:ring-primary-400 w-4 h-4 cursor-pointer transition-all">
                                                <span class="text-xs font-semibold {{ $sizeChecked ? 'text-primary-600' : 'text-slate-600 group-hover:text-slate-800' }}">{{ $size }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </details>
                            @endif

                            <!-- Price range -->
                            <details class="group select-none" open>
                                <summary class="flex items-center justify-between font-bold text-slate-800 text-xs uppercase tracking-wider cursor-pointer list-none pb-2 border-b border-slate-100 [&::-webkit-details-marker]:hidden">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-rupiah-sign text-slate-400 text-[10px]"></i>
                                        <span>Rentang Harga (Rp)</span>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-450 group-open:rotate-180 transition-transform duration-200"></i>
                                </summary>
                                <div class="mt-4 space-y-4">
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-2 text-[9px] font-bold text-slate-400 uppercase select-none">Min</span>
                                        <input type="number" name="price_min" placeholder="0" value="{{ request('price_min') }}"
                                               class="w-full bg-slate-50 border border-slate-200 text-slate-700 pl-12 pr-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-400 text-xs font-semibold transition-all">
                                    </div>
                                    <div class="relative">
                                        <span class="absolute left-3.5 top-2 text-[9px] font-bold text-slate-400 uppercase select-none">Max</span>
                                        <input type="number" name="price_max" placeholder="Limit" value="{{ request('price_max') }}"
                                               class="w-full bg-slate-50 border border-slate-200 text-slate-700 pl-12 pr-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-400 text-xs font-semibold transition-all">
                                    </div>
                                </div>
                            </details>
                        </div>
                        
                        <!-- Apply Filter Footer (Visible on both mobile & desktop) -->
                        <div class="p-4 bg-slate-50 border-t border-slate-100 shrink-0">
                            <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 active:scale-[0.98] text-white font-bold py-3.5 rounded-xl text-xs transition-all shadow-sm flex items-center justify-center gap-2">
                                <i class="fa-solid fa-circle-check text-xs"></i>
                                <span>Terapkan Filter</span>
                            </button>
                        </div>
                    </div>

                    <!-- Reset Filters Button -->
                    @if(request('category') || request('price_min') || request('price_max') || request('sizes') || request('search'))
                        <div class="px-4 pb-4 lg:p-0 shrink-0">
                            <a href="{{ route('catalog.index') }}" 
                               class="w-full flex items-center justify-center gap-2 border border-slate-200 hover:bg-slate-50 bg-white text-slate-600 font-bold py-3.5 rounded-2xl text-xs transition-all shadow-sm group">
                                <i class="fa-solid fa-trash-can group-hover:rotate-12 transition-transform duration-200"></i>
                                <span>Bersihkan Semua Filter</span>
                            </a>
                        </div>
                    @endif
                </div>
            </aside>

            <!-- Products List Area (Right side 3/4) -->
            <div class="grow space-y-4 sm:space-y-6">
                <!-- Sorting & Stats Bar -->
                <div class="bg-white border border-slate-200 px-6 py-4 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-2 text-slate-500 text-xs sm:text-sm">
                        <i class="fa-solid fa-circle-info text-slate-400 text-sm"></i>
                        <span>
                            Menampilkan <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded-lg text-xs font-mono">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span> dari <span class="font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded-lg text-xs font-mono">{{ $products->total() }}</span> produk
                            @if(request('search'))
                                untuk "<span class="font-bold text-primary-550">{{ request('search') }}</span>"
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex items-center gap-2 self-stretch sm:self-auto justify-between sm:justify-end relative grow sm:grow-0">
                        <!-- Mobile Filter Button (Visible on mobile only) -->
                        <button type="button" id="mobile-filter-toggle" class="lg:hidden flex items-center gap-2 bg-white border border-slate-200 hover:border-primary-300 text-slate-700 px-4 py-2.5 rounded-xl text-xs font-semibold cursor-pointer transition-all shadow-sm hover:shadow active:scale-[0.99] grow sm:grow-0 justify-center">
                            <i class="fa-solid fa-sliders text-primary-500 text-xs"></i>
                            <span>Filter</span>
                        </button>

                        <div class="flex items-center gap-2 grow sm:grow-0 justify-end">
                            <label class="hidden sm:flex text-xs font-bold text-slate-700 whitespace-nowrap items-center gap-1.5">
                                <i class="fa-solid fa-arrow-down-wide-short text-slate-400 text-sm"></i>
                                <span>Urutkan:</span>
                            </label>
                            <!-- Hidden native select for form submission -->
                            <select name="sort" id="sort" class="hidden">
                                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                            </select>
                            <!-- Custom Dropdown -->
                            <div id="sort-dropdown" class="relative grow sm:grow-0">
                                <button type="button" id="sort-btn"
                                    class="flex items-center gap-2 bg-white border border-slate-200 hover:border-primary-300 text-slate-700 pl-4 pr-3 py-2.5 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-primary-400/30 focus:border-primary-400 cursor-pointer transition-all shadow-sm hover:shadow w-full sm:min-w-[180px] justify-between">
                                    <span id="sort-btn-text">
                                        @if(request('sort') === 'price_asc') Harga: Terendah ke Tertinggi
                                        @elseif(request('sort') === 'price_desc') Harga: Tertinggi ke Terendah
                                        @else Terbaru
                                        @endif
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200" id="sort-chevron"></i>
                                </button>
                                <div id="sort-menu" class="absolute right-0 top-full mt-2 w-full min-w-[220px] bg-white border border-slate-100 rounded-xl shadow-lg shadow-slate-200/50 z-50 py-1.5 transition-all duration-200 ease-out origin-top scale-95 opacity-0 pointer-events-none">
                                    <button type="button" data-value="latest"
                                        class="sort-option w-full text-left px-4 py-2.5 text-xs font-medium hover:bg-primary-50 hover:text-primary-600 transition-colors flex items-center gap-2.5 {{ request('sort', 'latest') === 'latest' ? 'text-primary-600 bg-primary-50/50' : 'text-slate-600' }}">
                                        <i class="fa-solid fa-clock text-sm {{ request('sort', 'latest') === 'latest' ? 'text-primary-500' : 'text-slate-300' }}"></i>
                                        <span>Terbaru</span>
                                        @if(request('sort', 'latest') === 'latest')<i class="fa-solid fa-check text-primary-500 ml-auto text-[10px]"></i>@endif
                                    </button>
                                    <button type="button" data-value="price_asc"
                                        class="sort-option w-full text-left px-4 py-2.5 text-xs font-medium hover:bg-primary-50 hover:text-primary-600 transition-colors flex items-center gap-2.5 {{ request('sort') === 'price_asc' ? 'text-primary-600 bg-primary-50/50' : 'text-slate-600' }}">
                                        <i class="fa-solid fa-arrow-up-1-9 text-sm {{ request('sort') === 'price_asc' ? 'text-primary-500' : 'text-slate-300' }}"></i>
                                        <span>Harga: Terendah ke Tertinggi</span>
                                        @if(request('sort') === 'price_asc')<i class="fa-solid fa-check text-primary-500 ml-auto text-[10px]"></i>@endif
                                    </button>
                                    <button type="button" data-value="price_desc"
                                        class="sort-option w-full text-left px-4 py-2.5 text-xs font-medium hover:bg-primary-50 hover:text-primary-600 transition-colors flex items-center gap-2.5 {{ request('sort') === 'price_desc' ? 'text-primary-600 bg-primary-50/50' : 'text-slate-600' }}">
                                        <i class="fa-solid fa-arrow-down-9-1 text-sm {{ request('sort') === 'price_desc' ? 'text-primary-500' : 'text-slate-300' }}"></i>
                                        <span>Harga: Tertinggi ke Terendah</span>
                                        @if(request('sort') === 'price_desc')<i class="fa-solid fa-check text-primary-500 ml-auto text-[10px]"></i>@endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Active Filter Badges -->
                @if(request('categories') || request('category') || request('sizes') || request('price_min') || request('price_max') || request('search'))
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="text-xs text-slate-500 font-medium">Filter aktif:</span>
                    
                    @if(request('search'))
                        <a href="{{ request()->fullUrlWithoutQuery(['search']) }}" class="inline-flex items-center gap-1.5 bg-primary-50 text-primary-700 border border-primary-200 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-primary-100 transition-colors group">
                            <i class="fa-solid fa-magnifying-glass text-[9px] text-primary-400"></i>
                            <span>"{{ request('search') }}"</span>
                            <i class="fa-solid fa-xmark text-[10px] text-primary-400 group-hover:text-primary-600 ml-0.5"></i>
                        </a>
                    @endif

                    @if(request('category'))
                        @php $catName = $categories->firstWhere('slug', request('category'))?->name ?? request('category'); @endphp
                        <a href="{{ request()->fullUrlWithoutQuery(['category']) }}" class="inline-flex items-center gap-1.5 bg-primary-50 text-primary-700 border border-primary-200 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-primary-100 transition-colors group">
                            <i class="fa-solid fa-tag text-[9px] text-primary-400"></i>
                            <span>{{ $catName }}</span>
                            <i class="fa-solid fa-xmark text-[10px] text-primary-400 group-hover:text-primary-600 ml-0.5"></i>
                        </a>
                    @endif

                    @if(is_array(request('categories')))
                        @foreach(request('categories') as $catSlug)
                            @php $catName = $categories->firstWhere('slug', $catSlug)?->name ?? $catSlug; @endphp
                            @php
                                $remainingCats = array_filter(request('categories'), fn($c) => $c !== $catSlug);
                                $url = request()->fullUrlWithoutQuery(['categories']) . (count($remainingCats) ? '&' . http_build_query(['categories' => array_values($remainingCats)]) : '');
                            @endphp
                            <a href="{{ $url }}" class="inline-flex items-center gap-1.5 bg-primary-50 text-primary-700 border border-primary-200 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-primary-100 transition-colors group">
                                <i class="fa-solid fa-tag text-[9px] text-primary-400"></i>
                                <span>{{ $catName }}</span>
                                <i class="fa-solid fa-xmark text-[10px] text-primary-400 group-hover:text-primary-600 ml-0.5"></i>
                            </a>
                        @endforeach
                    @endif

                    @if(is_array(request('sizes')))
                        @foreach(request('sizes') as $size)
                            @php
                                $remainingSizes = array_filter(request('sizes'), fn($s) => $s !== $size);
                                $url = request()->fullUrlWithoutQuery(['sizes']) . (count($remainingSizes) ? '&' . http_build_query(['sizes' => array_values($remainingSizes)]) : '');
                            @endphp
                            <a href="{{ $url }}" class="inline-flex items-center gap-1.5 bg-secondary-50 text-secondary-700 border border-secondary-200 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-secondary-100 transition-colors group">
                                <i class="fa-solid fa-ruler text-[9px] text-secondary-400"></i>
                                <span>{{ $size }}</span>
                                <i class="fa-solid fa-xmark text-[10px] text-secondary-400 group-hover:text-secondary-600 ml-0.5"></i>
                            </a>
                        @endforeach
                    @endif

                    @if(request('price_min') || request('price_max'))
                        <a href="{{ request()->fullUrlWithoutQuery(['price_min', 'price_max']) }}" class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-emerald-100 transition-colors group">
                            <i class="fa-solid fa-rupiah-sign text-[9px] text-emerald-400"></i>
                            <span>Rp{{ number_format(request('price_min', 0), 0, ',', '.') }} - Rp{{ number_format(request('price_max', 0), 0, ',', '.') }}</span>
                            <i class="fa-solid fa-xmark text-[10px] text-emerald-400 group-hover:text-emerald-600 ml-0.5"></i>
                        </a>
                    @endif

                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full text-xs font-semibold hover:bg-slate-200 transition-colors">
                        <i class="fa-solid fa-rotate-left text-[9px]"></i>
                        <span>Reset Semua</span>
                    </a>
                </div>
                @endif

                <!-- Products Grid -->
                @if($products->isEmpty())
                    <div class="bg-white border border-slate-100 py-16 px-4 rounded-3xl text-center shadow-sm animate-fade-in">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-box-open text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-lg mb-1">Produk Tidak Ditemukan</h3>
                        <p class="text-slate-500 text-sm max-w-sm mx-auto">Kami tidak dapat menemukan produk yang sesuai dengan filter atau kata pencarian Anda. Silakan coba atur ulang filter.</p>
                        <a href="{{ route('catalog.index') }}" class="inline-block mt-5 bg-primary-500 hover:bg-primary-600 text-white font-semibold px-6 py-2 rounded-full text-xs shadow transition-all">
                            Lihat Semua Koleksi
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 product-card-shadow product-card-zoom flex flex-col justify-between">
                                <a href="{{ route('catalog.show', $product->slug) }}" class="block relative group">
                                    <!-- Image aspect-square -->
                                    <div class="aspect-square bg-slate-50 relative overflow-hidden">
                                        @if($product->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                                 alt="{{ $product->name }}" 
                                                 width="300" 
                                                 height="300"
                                                 class="w-full h-full object-cover"
                                                 loading="lazy"
                                                 onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                                            <div class="hidden absolute inset-0 flex-col items-center justify-center bg-slate-100 text-slate-400 p-2">
                                                <i class="fa-regular fa-image text-3xl mb-1"></i>
                                                <span class="text-[10px] text-slate-400 font-medium">Gambar tidak tersedia</span>
                                            </div>
                                        @else
                                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-100 text-slate-400 p-2">
                                                <i class="fa-regular fa-image text-3xl mb-1"></i>
                                                <span class="text-[10px] text-slate-400 font-medium">Gambar tidak tersedia</span>
                                            </div>
                                        @endif
                                        
                                        <!-- Status Badge -->
                                        @if($product->status !== 'ready')
                                            <div class="absolute top-2 left-2 bg-slate-900/80 text-white text-[10px] px-2 py-0.5 rounded-full font-semibold uppercase tracking-wider backdrop-blur-sm">
                                                {{ $product->status === 'po' ? 'Pre-Order' : 'Habis' }}
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                <!-- Details -->
                                <div class="p-4 flex flex-col grow justify-between">
                                    <div>
                                        <!-- Category -->
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                                            {{ $product->category->name }}
                                        </span>
                                        
                                        <!-- Title -->
                                        <a href="{{ route('catalog.show', $product->slug) }}" class="text-sm font-bold text-slate-800 hover:text-primary-500 transition-colors line-clamp-2 mb-2 min-h-[40px]">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                    
                                    <div>
                                        <!-- Price -->
                                        <p class="text-primary-500 font-bold text-base mb-3">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </p>
                                        
                                        <!-- Button CTA -->
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('catalog.show', $product->slug) }}" 
                                               class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold text-secondary-500 bg-secondary-50 hover:bg-primary-500 hover:text-white transition-all font-sans">
                                                <span>Detail</span>
                                                <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                            </a>
                                            <button type="button" onclick="openQuickAddModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->sku ?: 'BM-' . $product->id }}', '{{ $product->images->isNotEmpty() ? $product->images->first()->image_path : '' }}', {{ $product->variants->toJson() }})"
                                                    class="w-8 h-8 rounded-xl flex items-center justify-center bg-primary-50 text-primary-600 hover:bg-primary-500 hover:text-white border border-primary-100 transition-all cursor-pointer shrink-0"
                                                    title="Tambah ke Keranjang">
                                                <i class="fa-solid fa-cart-plus text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-6">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Handle filter checkbox changes
    function handleFilterChange(element) {
        // Toggle styling on check/uncheck
        const label = element.closest('label');
        if (label) {
            const span = label.querySelector('span');
            if (element.checked) {
                label.className = "flex items-center gap-3 p-2.5 rounded-xl border cursor-pointer transition-all duration-200 select-none group border-primary-200 bg-primary-50/40 text-primary-900 shadow-sm";
                if (span) {
                    span.className = "text-xs font-semibold text-primary-600";
                }
            } else {
                label.className = "flex items-center gap-3 p-2.5 rounded-xl border cursor-pointer transition-all duration-200 select-none group border-slate-100 bg-white text-slate-700 hover:border-slate-200 hover:bg-slate-50";
                if (span) {
                    span.className = "text-xs font-semibold text-slate-600 group-hover:text-slate-800";
                }
            }
        }
    }

    // Drawer Filter on mobile
    const toggleBtn = document.getElementById('mobile-filter-toggle');
    const container = document.getElementById('filters-container');
    const backdrop = document.getElementById('filter-drawer-backdrop');
    const closeBtn = document.getElementById('mobile-filter-close');

    function openDrawer() {
        if (backdrop && container) {
            if (window.innerWidth < 1024) {
                const header = document.querySelector('header');
                if (header) {
                    const headerRect = header.getBoundingClientRect();
                    const headerBottom = headerRect.bottom;
                    container.style.top = `${headerBottom}px`;
                    backdrop.style.top = `${headerBottom}px`;
                }
            }
            backdrop.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
            }, 10);
            container.classList.remove('-translate-x-full');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeDrawer() {
        if (backdrop && container) {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            container.classList.add('-translate-x-full');
            document.body.classList.remove('overflow-hidden');
            setTimeout(() => {
                backdrop.classList.add('hidden');
                container.style.top = '';
                backdrop.style.top = '';
            }, 300);
        }
    }

    if (toggleBtn && container) {
        toggleBtn.addEventListener('click', openDrawer);
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', closeDrawer);
    }
    if (backdrop) {
        backdrop.addEventListener('click', closeDrawer);
    }

    // Custom sort dropdown
    const sortBtn = document.getElementById('sort-btn');
    const sortMenu = document.getElementById('sort-menu');
    const sortChevron = document.getElementById('sort-chevron');
    const sortSelect = document.getElementById('sort');
    const sortOptions = document.querySelectorAll('.sort-option');

    function openSortMenu() {
        sortMenu.classList.remove('scale-95', 'opacity-0', 'pointer-events-none');
        sortMenu.classList.add('scale-100', 'opacity-100', 'pointer-events-auto');
        sortChevron.style.transform = 'rotate(180deg)';
    }

    function closeSortMenu() {
        sortMenu.classList.remove('scale-100', 'opacity-100', 'pointer-events-auto');
        sortMenu.classList.add('scale-95', 'opacity-0', 'pointer-events-none');
        sortChevron.style.transform = 'rotate(0deg)';
    }

    if (sortBtn && sortMenu) {
        sortBtn.addEventListener('click', () => {
            const isOpen = sortMenu.classList.contains('scale-100');
            if (isOpen) {
                closeSortMenu();
            } else {
                openSortMenu();
            }
        });

        // Close on click outside
        document.addEventListener('click', (e) => {
            if (!sortBtn.contains(e.target) && !sortMenu.contains(e.target)) {
                closeSortMenu();
            }
        });

        // Option click
        sortOptions.forEach(option => {
            option.addEventListener('click', () => {
                const value = option.getAttribute('data-value');
                sortSelect.value = value;
                closeSortMenu();
                document.getElementById('filter-form').submit();
            });
        });
    }
</script>
@endsection
