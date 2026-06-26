@extends('layouts.app')

@section('title', 'Beranda')
@section('meta_description', 'Toko Berkah Mulia - Pusat grosir & eceran pakaian bayi, anak-anak, dan underwear berkualitas premium dengan harga terjangkau di Depok.')
@section('canonical_url', route('home'))

@section('content')
<!-- Hero Banner Section (Clean split grid design constrained to content layout width) -->
<section class="bg-slate-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 bg-slate-100 rounded-3xl overflow-hidden shadow-sm border border-slate-100">
            <!-- Left Side: Copywriting Content -->
            <div class="lg:col-span-6 px-6 py-12 sm:px-12 sm:py-16 lg:py-20 flex flex-col justify-center select-none order-2 lg:order-1">
                <span class="inline-block self-start text-xs uppercase tracking-widest font-semibold text-primary-600 bg-primary-50 px-3 py-1 rounded-full mb-4">
                    {{ $heroBadge }}
                </span>
                <h1 class="text-3xl tracking-tight font-extrabold text-slate-900 sm:text-4xl md:text-5xl leading-tight">
                    <span class="block">{{ $heroTitleLine1 }}</span>
                    <span class="block text-secondary-700 mt-1">{{ $heroTitleLine2 }}</span>
                </h1>
                <p class="mt-4 text-sm sm:text-base text-slate-600 leading-relaxed max-w-lg">
                    {{ $heroDescription }}
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-primary-500 hover:bg-primary-600 shadow-sm transition-all text-center">
                        Lihat Semua Koleksi
                    </a>
                    <a href="https://wa.me/{{ config('app.whatsapp_number', '628123456789') }}" target="_blank" class="inline-flex items-center justify-center px-6 py-3 border border-slate-200 text-sm font-semibold rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition-all text-center">
                        Tanya Stok Grosir
                    </a>
                </div>
            </div>

            <!-- Right Side: Contained Visual Image Carousel (Aligned with parent grid) -->
            <div id="hero-carousel-container" class="lg:col-span-6 aspect-square sm:aspect-auto sm:h-80 lg:h-auto relative bg-slate-100 flex items-stretch overflow-hidden group/carousel-hero order-1 lg:order-2">
                @php
                    $fileExists = function($path) {
                        if (!$path) return false;
                        $cleanPath = preg_replace('/^storage\//', '', $path);
                        return file_exists(public_path($path)) || file_exists(storage_path('app/public/' . $cleanPath));
                    };

                    $customSlides = [];
                    foreach ($banners as $b) {
                        $path = 'storage/' . $b;
                        if ($fileExists($path)) {
                            $customSlides[] = [
                                'path' => $path,
                                'title' => 'Koleksi Terbaru',
                                'description' => 'Pilihan pakaian anak berkualitas',
                                'url' => route('catalog.index')
                            ];
                        }
                    }

                    $defaultSlides = [];
                    foreach ($randomBanners as $rb) {
                        $path = $rb['image_path'] ?? null;
                        $hasImage = $path && $fileExists($path);
                        $defaultSlides[] = [
                            'path' => $hasImage ? $path : null,
                            'title' => $rb['name'],
                            'description' => $rb['formatted_price'],
                            'url' => route('catalog.show', $rb['slug'])
                        ];
                    }
                    
                    // Fallback to static assets if there are not enough product images in database
                    if (count($defaultSlides) < 4) {
                        $fallbacks = [
                            [
                                'path' => 'storage/assets/hero_banner.webp',
                                'title' => 'Pakaian Lembut & Nyaman',
                                'description' => 'Koleksi Bayi & Anak Premium',
                                'url' => route('catalog.index')
                            ],
                            [
                                'path' => 'storage/assets/product_bedong.webp',
                                'title' => 'Koleksi Bedong & Aksesoris',
                                'description' => 'Bahan Katun Alami 100%',
                                'url' => route('catalog.index', ['category' => 'bedong'])
                            ]
                        ];
                        foreach ($fallbacks as $fb) {
                            if ($fileExists($fb['path'])) {
                                $defaultSlides[] = $fb;
                            }
                        }
                    }
                    
                    $slides = array_merge($customSlides, $defaultSlides);
                @endphp
                @if(count($slides) > 0)
                    <!-- Carousel Track -->
                    <div id="hero-carousel-track" class="flex w-full h-full transition-transform duration-500 ease-out" style="transform: translateX(0%);">
                        @foreach($slides as $slide)
                            <div class="w-full h-full shrink-0 relative flex items-stretch">
                                @if($slide['path'])
                                    <a href="{{ $slide['url'] }}" class="block w-full h-full cursor-pointer">
                                        <img class="w-full h-full object-cover" src="{{ '/' . $slide['path'] }}" alt="{{ $slide['title'] }}" width="600" height="400" @if($loop->first) fetchpriority="high" @endif onerror="this.parentElement.style.display='none'; this.parentElement.nextElementSibling.classList.replace('hidden', 'flex');">
                                    </a>
                                @endif
                                <div class="{{ $slide['path'] ? 'hidden' : 'flex' }} absolute inset-0 flex-col items-center justify-center bg-gradient-to-tr from-primary-50 via-slate-50 to-secondary-50/50 text-slate-700 p-6 text-center w-full h-full select-none relative overflow-hidden">
                                    <!-- Decorative background shapes -->
                                    <div class="absolute -top-10 -right-10 w-32 h-32 rounded-full bg-primary-100/20 blur-2xl"></div>
                                    <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-secondary-100/20 blur-2xl"></div>

                                    <div class="relative w-16 h-16 rounded-2xl bg-white shadow-xs flex items-center justify-center border border-slate-100 mb-3 hover:scale-105 transition-transform duration-300">
                                        <svg class="w-8 h-8 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <h2 class="text-sm font-bold text-slate-800 tracking-tight px-4 leading-snug">{{ $slide['title'] }}</h2>
                                    <p class="text-[11px] text-slate-500 font-semibold mt-1.5">{{ $slide['description'] }}</p>
                                    <a href="{{ $slide['url'] }}" class="mt-4 inline-flex items-center justify-center px-4 py-2 bg-primary-500 hover:bg-primary-600 active:scale-95 text-white text-xs font-bold rounded-xl shadow-xs transition-all z-10">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Fallback if there are no images at all (Premium SVG + icon style) -->
                    <div class="w-full h-full bg-gradient-to-tr from-primary-50 via-slate-50 to-secondary-50/50 flex flex-col items-center justify-center p-8 text-center select-none min-h-[300px] lg:min-h-full relative overflow-hidden grow">
                        <!-- Decorative floating circles behind -->
                        <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-primary-100/20 blur-2xl"></div>
                        <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-secondary-100/20 blur-2xl"></div>
                        
                        <!-- Stylized Glass Container for the Icon -->
                        <div class="relative w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-white/80 shadow-md flex items-center justify-center border border-white/60 mb-5 hover:scale-105 transition-all duration-300">
                            <!-- SVG Image + Baby clothes illustration -->
                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <!-- Outline of a clothes hanger with baby shirt -->
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3a2 2 0 00-2 2v2H5.5A1.5 1.5 0 004 8.5v6A1.5 1.5 0 005.5 16h13a1.5 1.5 0 001.5-1.5v-6A1.5 1.5 0 0018.5 7H14V5a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3 3-3" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5h.01" />
                            </svg>
                            <span class="absolute -bottom-1 -right-1 bg-secondary-500 text-white w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center text-[8px] sm:text-[10px] font-bold shadow-sm">
                                <i class="fa-solid fa-heart"></i>
                            </span>
                        </div>
                        
                        <!-- Title & Subtitle -->
                        <h2 class="text-sm sm:text-base font-bold text-slate-800 tracking-tight">Berkah Mulia</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500 mt-1.5 max-w-xs leading-relaxed font-semibold">
                            Pusat pakaian bayi & anak berkualitas premium. Gambar sedang disiapkan oleh admin.
                        </p>
                    </div>
                @endif

                @if(count($slides) > 1)
                <!-- Carousel Controls (Prev/Next) -->
                <button type="button" onclick="prevHeroSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center shadow-md border border-slate-100 hover:scale-105 transition-all duration-300 cursor-pointer z-20 md:opacity-0 md:group-hover/carousel-hero:opacity-100 md:translate-x-2 md:group-hover/carousel-hero:translate-x-0" aria-label="Slide sebelumnya">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button type="button" onclick="nextHeroSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center shadow-md border border-slate-100 hover:scale-105 transition-all duration-300 cursor-pointer z-20 md:opacity-0 md:group-hover/carousel-hero:opacity-100 md:-translate-x-2 md:group-hover/carousel-hero:translate-x-0" aria-label="Slide berikutnya">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>
 
                <!-- Carousel Indicators (Dots) -->
                <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex items-center gap-0.5 z-20 select-none">
                    @foreach($slides as $index => $slide)
                        <button type="button" onclick="goToHeroSlide({{ $index }})" class="flex items-center justify-center w-7 h-7 cursor-pointer group" aria-label="Lihat slide {{ $index + 1 }}">
                            <span class="hero-dot {{ $loop->first ? 'w-5 bg-white' : 'w-2 bg-white/50' }} h-2 rounded-full group-hover:bg-white transition-all duration-300 shadow-[0_1.5px_3px_rgba(0,0,0,0.4)]"></span>
                        </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Visual Category Grid (Carter's Style Circle Cards) -->
<section class="bg-white border-y border-slate-100 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-xl mx-auto mb-12">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kategori Pilihan Terbaik</h2>
        <p class="mt-2 text-slate-500 text-sm">Temukan pakaian yang tepat berdasarkan kategori produk khusus kami</p>
    </div>
    
    <!-- Category Carousel -->
    <div class="relative group/carousel" id="category-carousel-wrapper">
        <!-- Left Arrow (hidden on mobile) -->
        <button id="cat-prev" class="hidden sm:flex absolute -left-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white shadow-md rounded-full w-11 h-11 items-center justify-center text-slate-600 hover:text-primary-500 transition-all opacity-0 group-hover/carousel:opacity-100" aria-label="Sebelumnya">
            <i class="fa-solid fa-chevron-left text-sm"></i>
        </button>
        <!-- Right Arrow (hidden on mobile) -->
        <button id="cat-next" class="hidden sm:flex absolute -right-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white shadow-md rounded-full w-11 h-11 items-center justify-center text-slate-600 hover:text-primary-500 transition-all opacity-0 group-hover/carousel:opacity-100" aria-label="Berikutnya">
            <i class="fa-solid fa-chevron-right text-sm"></i>
        </button>

        <div id="category-carousel" class="overflow-hidden">
            <div id="category-track" class="flex">
                @php
                    $catImages = [
                        'baju' => 'product_baju.webp',
                        'bedong' => 'product_bedong.webp',
                        'aksesoris' => 'product_aksesoris.webp',
                        'celana' => 'product_baju.webp',
                        'popok' => 'product_aksesoris.webp',
                        'stelan' => 'product_baju.webp',
                        'rok' => 'product_aksesoris.webp',
                        'gendongan' => 'product_bedong.webp',
                        'underwear' => 'product_aksesoris.webp',
                        'singlet' => 'product_baju.webp',
                    ];
                @endphp
                @foreach($categories as $cat)
                    @php
                        $imgName = $catImages[$cat->slug] ?? 'product_baju.webp';
                    @endphp
                    <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" class="category-item group text-center flex flex-col items-center shrink-0 px-3 sm:px-4">
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-white shadow-sm flex items-center justify-center overflow-hidden border-2 border-slate-100 group-hover:border-primary-400 group-hover:shadow-md transition-all duration-300 relative">
                            @if(isset($cat->image_path) && $cat->image_path)
                                <img src="{{ '/storage/' . $cat->image_path }}" alt="{{ $cat->name }}" width="96" height="96" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                            @else
                                <img src="{{ '/storage/assets/' . $imgName }}" alt="{{ $cat->name }}" width="96" height="96" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                            @endif
                            <div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400">
                                <i class="fa-regular fa-image text-lg sm:text-xl"></i>
                            </div>
                        </div>
                        <span class="mt-3 text-xs sm:text-sm font-semibold text-slate-700 group-hover:text-primary-500 transition-colors whitespace-nowrap">
                            {{ $cat->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>



<!-- Featured Products (Showcase) -->
<section class="bg-slate-50/50 py-16 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between mb-10">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-sans">Koleksi Terpopuler</h2>
            <p class="mt-1 text-slate-500 text-sm">Produk unggulan terbaru dari etalase katalog kami</p>
        </div>
        <a href="{{ route('catalog.index') }}" class="text-sm font-bold text-secondary-700 hover:text-primary-500 flex items-center gap-1.5 transition-colors">
            <span>Lihat Semua</span>
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @foreach($featuredProducts as $product)
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 product-card-shadow product-card-zoom flex flex-col justify-between">
                <a href="{{ route('catalog.show', $product->slug) }}" class="block relative group">
                    <!-- Image -->
                    <div class="aspect-square bg-slate-50 relative overflow-hidden">
                        @if($product->images->isNotEmpty())
                            <img src="{{ '/storage/' . $product->images->first()->image_path }}" 
                                 alt="{{ $product->name }}" 
                                 width="300" 
                                 height="300"
                                 class="w-full h-full object-cover"
                                 loading="lazy"
                                 onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                            <div class="hidden absolute inset-0 flex-col items-center justify-center bg-slate-100 text-slate-500 p-2">
                                <i class="fa-regular fa-image text-3xl mb-1"></i>
                                <span class="text-[10px] text-slate-600 font-medium">Gambar tidak tersedia</span>
                            </div>
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-100 text-slate-500 p-2">
                                <i class="fa-regular fa-image text-3xl mb-1"></i>
                                <span class="text-[10px] text-slate-600 font-medium">Gambar tidak tersedia</span>
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
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">
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
                            {{ $product->formatted_price }}
                        </p>
                        
                        <!-- Button CTA -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('catalog.show', $product->slug) }}" 
                               class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold text-secondary-800 bg-secondary-50 hover:bg-primary-500 hover:text-white transition-all">
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
</section>

<!-- Store Location Section -->
<section class="bg-gradient-to-br from-white via-primary-50/5 to-white py-16 border-b border-slate-100">
    @php
        $storeAddress = \App\Models\Setting::get('store_address', 'Jl. Poin Mas 40, Sawangan , Kota Depok, Jawa Barat');
        $storeHours = \App\Models\Setting::get('store_hours', 'Senin - Sabtu: 08.00 - 17.00 WIB (Minggu Libur)');
        $storePhone = \App\Models\Setting::get('store_phone', '628123456789');
        $storeMapIframe = \App\Models\Setting::get('store_map_iframe', 'https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3964.9688145288073!2d106.79495617499184!3d-6.398020293592605!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNsKwMjMnNTIuOSJTIDEwNsKwNDcnNTEuMSJF!5e0!3m2!1sid!2sid!4v1781447494862!5m2!1sid!2sid');
        $storeMapLink = \App\Models\Setting::get('store_map_link', 'https://maps.app.goo.gl/mYnJQ52kxqzy784y8');
        $storeImage = \App\Models\Setting::get('store_image');
        $storeImagePath = $storeImage ? asset('storage/' . $storeImage) : asset('storefront_location.webp');

        // 1. Store Schema
        $storeSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Store',
            'name' => 'Berkah Mulia',
            'image' => asset('logo.webp'),
            '@id' => route('home'),
            'url' => route('home'),
            'telephone' => $storePhone,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $storeAddress,
                'addressLocality' => 'Depok',
                'addressRegion' => 'Jawa Barat',
                'addressCountry' => 'ID',
            ],
            'openingHoursSpecification' => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                ],
                'opens' => '08:00',
                'closes' => '17:00',
            ],
        ];

        // 2. WebSite Schema for Sitelinks Search Box
        $websiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'Berkah Mulia',
            'url' => route('home'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('catalog.index') . '?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];

        // 3. Organization Schema for Knowledge Graph
        $instagramUrl = \App\Models\Setting::get('instagram_url', '');
        $tiktokUrl = \App\Models\Setting::get('tiktok_url', '');
        $shopeeUrl = \App\Models\Setting::get('shopee_url', '');
        
        $sameAs = [];
        if (!empty($instagramUrl)) $sameAs[] = $instagramUrl;
        if (!empty($tiktokUrl)) $sameAs[] = $tiktokUrl;
        if (!empty($shopeeUrl)) $sameAs[] = $shopeeUrl;

        $orgSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Berkah Mulia',
            'url' => route('home'),
            'logo' => asset('logo.webp'),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '+' . preg_replace('/[^0-9]/', '', $storePhone),
                'contactType' => 'customer service',
            ],
        ];
        if (!empty($sameAs)) {
            $orgSchema['sameAs'] = $sameAs;
        }
    @endphp
    
    <!-- JSON-LD Store Schema for Local SEO -->
    <script type="application/ld+json">
    {!! json_encode($storeSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <!-- JSON-LD WebSite Schema for Search Box -->
    <script type="application/ld+json">
    {!! json_encode($websiteSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <!-- JSON-LD Organization Schema -->
    <script type="application/ld+json">
    {!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <!-- Left Side: Google Maps Location -->
            <div class="relative group bg-white rounded-3xl p-3 shadow-md border border-slate-100 hover:shadow-xl hover:shadow-primary-500/5 hover:border-primary-100 transition-all duration-500 flex flex-col h-[340px] sm:h-[400px]">
                <div class="grow rounded-2xl overflow-hidden relative">
                    @if($storeMapIframe)
                        <iframe 
                            data-src="{{ $storeMapIframe }}" 
                            class="w-full h-full rounded-2xl border-0 lazy-map-iframe" 
                            title="Peta Lokasi Toko Berkah Mulia"
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                let mapLoaded = false;
                                function loadMap() {
                                    if (mapLoaded) return;
                                    mapLoaded = true;
                                    const iframe = document.querySelector('.lazy-map-iframe');
                                    if (iframe) {
                                        const src = iframe.getAttribute('data-src');
                                        if (src) {
                                            iframe.setAttribute('src', src);
                                        }
                                    }
                                    window.removeEventListener('scroll', loadMap);
                                    document.removeEventListener('mousemove', loadMap);
                                    document.removeEventListener('touchstart', loadMap);
                                }
                                window.addEventListener('scroll', loadMap, { passive: true });
                                document.addEventListener('mousemove', loadMap, { passive: true });
                                document.addEventListener('touchstart', loadMap, { passive: true });
                                setTimeout(loadMap, 4000);
                            });
                        </script>
                    @else
                        <div class="w-full h-full rounded-2xl bg-slate-50 flex flex-col items-center justify-center text-slate-400">
                            <i class="fa-solid fa-map-marked-alt text-4xl mb-2"></i>
                            <span class="text-xs">Peta Lokasi Belum Ditentukan</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Side: Location Details -->
            <div class="space-y-6 select-none">
                <div>
                    <span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-widest font-extrabold text-primary-600 bg-primary-50 border border-primary-100/50 px-3 py-1 rounded-full mb-3">
                        <i class="fa-solid fa-store text-[9px] text-primary-500"></i>
                        <span>{{ $locationBadge }}</span>
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-sans">
                        {{ $locationTitle }}
                    </h2>
                    <p class="mt-3 text-slate-500 text-sm leading-relaxed">
                        {{ str_replace('datang langsung to toko', 'datang langsung ke toko', $locationDescription) }}
                    </p>
                </div>

                <div class="space-y-3.5">
                    <!-- Address Card -->
                    <div class="flex gap-4 items-start bg-white p-4 rounded-2xl border border-slate-100 shadow-xs hover:border-primary-200 hover:shadow-md hover:shadow-primary-500/2 transition-all duration-300 group/tile">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center shrink-0 text-sm group-hover/tile:scale-105 group-hover/tile:bg-primary-500 group-hover/tile:text-white transition-all duration-300">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Alamat Lengkap</h3>
                            <p class="text-xs text-slate-700 font-semibold leading-relaxed">
                                {{ $storeAddress }}
                            </p>
                        </div>
                    </div>

                    <!-- Hours Card -->
                    <div class="flex gap-4 items-start bg-white p-4 rounded-2xl border border-slate-100 shadow-xs hover:border-primary-200 hover:shadow-md hover:shadow-primary-500/2 transition-all duration-300 group/tile">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center shrink-0 text-sm group-hover/tile:scale-105 group-hover/tile:bg-primary-500 group-hover/tile:text-white transition-all duration-300">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Jam Operasional</h3>
                            <p class="text-xs text-slate-700 font-semibold leading-relaxed">
                                {{ $storeHours }}
                            </p>
                        </div>
                    </div>

                    <!-- Contact Card -->
                    <div class="flex gap-4 items-start bg-white p-4 rounded-2xl border border-slate-100 shadow-xs hover:border-primary-200 hover:shadow-md hover:shadow-primary-500/2 transition-all duration-300 group/tile">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center shrink-0 text-sm group-hover/tile:scale-105 group-hover/tile:bg-primary-500 group-hover/tile:text-white transition-all duration-300">
                            <i class="fa-brands fa-whatsapp text-[15px]"></i>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Hubungi Kami</h3>
                            <p class="text-xs text-slate-700 font-semibold leading-relaxed">
                                WhatsApp: <span class="font-mono">+{{ $storePhone }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    @if($storeMapLink)
                        <a href="{{ $storeMapLink }}" target="_blank"
                           class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 active:scale-95 text-white font-bold px-6 py-3.5 rounded-xl shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all text-xs cursor-pointer group">
                            <i class="fa-solid fa-map-location-dot text-sm group-hover:rotate-6 group-hover:scale-110 transition-transform"></i>
                            <span>Petunjuk Arah Google Maps</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Promotional Showcase Banner -->
<section class="bg-linear-to-r from-primary-500 to-secondary-500 text-white py-12 px-6 sm:px-12 text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[radial-gradient(ellipse_at_center,var(--tw-gradient-stops))] from-white via-transparent to-transparent"></div>
    <div class="relative z-10 max-w-4xl mx-auto">
        <h2 class="text-3xl font-extrabold mb-4">Menerima Pembelian Eceran & Grosir</h2>
        <p class="text-lg text-primary-100 max-w-xl mx-auto mb-6">
            Dapatkan harga spesial lebih murah untuk reseller, dropshipper, dan pembelian partai besar langsung dari produsen.
        </p>
        <a href="https://wa.me/{{ config('app.whatsapp_number', '628123456789') }}" target="_blank"
           class="inline-flex items-center gap-2 bg-white text-primary-600 hover:bg-slate-50 font-bold px-8 py-3 rounded-full shadow transition-all">
            <i class="fa-brands fa-whatsapp text-xl text-emerald-500"></i>
            <span>Dapatkan Harga Grosir</span>
        </a>
    </div>
</section>

@endsection

@section('scripts')
<style>
    #category-carousel::-webkit-scrollbar { display: none; }
    #category-carousel { -ms-overflow-style: none; scrollbar-width: none; }
</style>
<script>
    // === Hero Banner Carousel ===
    let currentHeroSlide = 0;
    const heroSlidesCount = {{ count($slides) }};
    const heroTrack = document.getElementById('hero-carousel-track');
    const heroDots = document.querySelectorAll('.hero-dot');
    let heroAutoplayTimer = null;

    function updateHeroCarousel() {
        if (heroTrack) {
            heroTrack.style.transform = `translateX(-${currentHeroSlide * 100}%)`;
        }
        
        // Update dots
        if (heroDots.length > 0) {
            heroDots.forEach((dot, index) => {
                if (index === currentHeroSlide) {
                    dot.classList.remove('bg-white/50', 'w-2');
                    dot.classList.add('bg-white', 'w-5');
                } else {
                    dot.classList.remove('bg-white', 'w-5');
                    dot.classList.add('bg-white/50', 'w-2');
                }
            });
        }
    }

    function nextHeroSlide() {
        if (heroSlidesCount <= 1) return;
        currentHeroSlide = (currentHeroSlide + 1) % heroSlidesCount;
        updateHeroCarousel();
        resetHeroAutoplay();
    }

    function prevHeroSlide() {
        if (heroSlidesCount <= 1) return;
        currentHeroSlide = (currentHeroSlide - 1 + heroSlidesCount) % heroSlidesCount;
        updateHeroCarousel();
        resetHeroAutoplay();
    }

    function goToHeroSlide(index) {
        if (heroSlidesCount <= 1) return;
        currentHeroSlide = index;
        updateHeroCarousel();
        resetHeroAutoplay();
    }

    function startHeroAutoplay() {
        if (heroSlidesCount <= 1) return;
        heroAutoplayTimer = setInterval(nextHeroSlide, 5000);
    }

    function resetHeroAutoplay() {
        if (heroAutoplayTimer) {
            clearInterval(heroAutoplayTimer);
            startHeroAutoplay();
        }
    }

    // === Swipe Support on Mobile for Hero Carousel ===
    document.addEventListener('DOMContentLoaded', function() {
        const heroContainer = document.getElementById('hero-carousel-container');
        if (heroContainer) {
            let touchStartX = 0;
            let touchEndX = 0;

            heroContainer.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            heroContainer.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleHeroSwipe();
            }, { passive: true });

            function handleHeroSwipe() {
                const swipeDistance = touchEndX - touchStartX;
                const threshold = 50; // minimum distance in pixels to count as swipe
                if (swipeDistance > threshold) {
                    // Swiped right (show previous slide)
                    prevHeroSlide();
                } else if (swipeDistance < -threshold) {
                    // Swiped left (show next slide)
                    nextHeroSlide();
                }
            }
        }
    });

    // === Category Carousel ===
    window.addEventListener('load', function() {
        // Init hero carousel
        updateHeroCarousel();
        startHeroAutoplay();

        // Init category carousel
        const track = document.getElementById('category-track');
        const carousel = document.getElementById('category-carousel');
        const prevBtn = document.getElementById('cat-prev');
        const nextBtn = document.getElementById('cat-next');

        if (!track || !carousel) return;

        const isMobile = () => window.innerWidth < 640;

        function setupMobile() {
            track.style.transform = '';
            track.style.transition = '';
            carousel.style.overflowX = 'auto';
            carousel.style.scrollSnapType = 'x mandatory';
            carousel.style.webkitOverflowScrolling = 'touch';
            carousel.style.scrollbarWidth = 'none';
            carousel.style.width = '';
            carousel.style.margin = '';
            track.querySelectorAll('.category-item').forEach(item => {
                item.style.scrollSnapAlign = 'center';
            });
        }

        let items, itemCount, itemWidth, currentIndex, autoplayInterval, isTransitioning, visibleItems;

        function getItemWidth() {
            const firstItem = track.querySelector('.category-item');
            if (!firstItem) return 100;
            return firstItem.offsetWidth;
        }

        function setupDesktop() {
            // Read layout geometry first before mutating DOM to prevent write-then-read layout thrashing (forced reflow)
            itemWidth = getItemWidth();

            carousel.style.overflowX = 'hidden';
            carousel.style.scrollSnapType = '';
            carousel.style.webkitOverflowScrolling = '';

            track.querySelectorAll('.clone-item').forEach(el => el.remove());

            items = Array.from(track.querySelectorAll('.category-item:not(.clone-item)'));
            itemCount = items.length;
            if (itemCount === 0) return;

            const maxVisible = Math.min(10, itemCount);
            const exactWidth = maxVisible * itemWidth;
            carousel.style.width = exactWidth + 'px';
            carousel.style.margin = '0 auto';

            const visibleCount = maxVisible;
            visibleItems = visibleCount;

            for (let i = 0; i < visibleCount + 1; i++) {
                const cloneAfter = items[i % itemCount].cloneNode(true);
                cloneAfter.classList.add('clone-item');
                track.appendChild(cloneAfter);

                const cloneBefore = items[itemCount - 1 - (i % itemCount)].cloneNode(true);
                cloneBefore.classList.add('clone-item');
                track.insertBefore(cloneBefore, track.firstChild);
            }

            currentIndex = visibleCount + 1;
            const offset = currentIndex * itemWidth;
            track.style.transition = 'none';
            track.style.transform = `translateX(-${offset}px)`;
            isTransitioning = false;
        }

        function slideDesktop(direction) {
            if (isTransitioning) return;
            isTransitioning = true;

            currentIndex += direction;
            const offset = currentIndex * itemWidth;
            track.style.transition = 'transform 0.4s ease';
            track.style.transform = `translateX(-${offset}px)`;

            setTimeout(() => {
                const visibleCount = visibleItems;
                const totalOriginal = itemCount;
                const cloneCount = visibleCount + 1;

                if (currentIndex >= totalOriginal + cloneCount) {
                    currentIndex = cloneCount;
                    track.style.transition = 'none';
                    track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
                }
                if (currentIndex < cloneCount - totalOriginal) {
                    currentIndex = totalOriginal + cloneCount - 1;
                    track.style.transition = 'none';
                    track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
                }
                isTransitioning = false;
            }, 420);
        }

        function startCatAutoplay() {
            stopCatAutoplay();
            autoplayInterval = setInterval(() => slideDesktop(1), 2500);
        }

        function stopCatAutoplay() {
            clearInterval(autoplayInterval);
        }

        function initCategory() {
            if (isMobile()) {
                stopCatAutoplay();
                setupMobile();
            } else {
                setupDesktop();
                startCatAutoplay();

                carousel.addEventListener('mouseenter', stopCatAutoplay);
                carousel.addEventListener('mouseleave', startCatAutoplay);
            }
        }

        initCategory();

        prevBtn.addEventListener('click', () => {
            if (isMobile()) return;
            stopCatAutoplay();
            slideDesktop(-1);
            startCatAutoplay();
        });

        nextBtn.addEventListener('click', () => {
            if (isMobile()) return;
            stopCatAutoplay();
            slideDesktop(1);
            startCatAutoplay();
        });

        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                stopCatAutoplay();
                track.querySelectorAll('.clone-item').forEach(el => el.remove());
                initCategory();
            }, 200);
        });
    });


</script>
@endsection
