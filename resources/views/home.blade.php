@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<!-- Hero Banner Section (Clean split grid design constrained to content layout width) -->
<section class="bg-slate-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 bg-slate-100 rounded-3xl overflow-hidden shadow-sm border border-slate-100">
            <!-- Left Side: Copywriting Content -->
            <div class="lg:col-span-6 px-6 py-12 sm:px-12 sm:py-16 lg:py-20 flex flex-col justify-center select-none">
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
            <div class="lg:col-span-6 h-64 sm:h-80 lg:h-auto relative bg-slate-100 flex items-stretch overflow-hidden">
                @php
                    $customSlides = array_map(fn($b) => 'storage/' . $b, $banners);
                    $defaultSlides = [
                        'storage/assets/hero_banner.webp',
                        'storage/assets/product_bedong.webp',
                        'storage/assets/product_aksesoris.webp',
                        'storage/assets/product_baju.webp'
                    ];
                    $slides = array_merge($customSlides, $defaultSlides);
                @endphp
                <!-- Carousel Track -->
                <div id="hero-carousel-track" class="flex w-full h-full transition-transform duration-500 ease-out" style="transform: translateX(0%);">
                    @foreach($slides as $slide)
                        <div class="w-full h-full shrink-0 relative">
                            <img class="w-full h-full object-cover" src="{{ asset($slide) }}" alt="Banner Slide {{ $loop->iteration }}" width="600" height="400" @if($loop->first) fetchpriority="high" @endif>
                        </div>
                    @endforeach
                </div>

                @if(count($slides) > 1)
                <!-- Carousel Controls (Prev/Next) -->
                <button type="button" onclick="prevHeroSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 hover:bg-white text-slate-800 flex items-center justify-center shadow-md transition-all cursor-pointer z-20" aria-label="Slide sebelumnya">
                    <i class="fa-solid fa-chevron-left text-sm"></i>
                </button>
                <button type="button" onclick="nextHeroSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/80 hover:bg-white text-slate-800 flex items-center justify-center shadow-md transition-all cursor-pointer z-20" aria-label="Slide berikutnya">
                    <i class="fa-solid fa-chevron-right text-sm"></i>
                </button>
 
                <!-- Carousel Indicators (Dots) -->
                <div class="absolute bottom-5 left-1/2 -translate-x-1/2 flex items-center gap-1 z-20">
                    @foreach($slides as $index => $slide)
                        <button type="button" onclick="goToHeroSlide({{ $index }})" class="flex items-center justify-center w-8 h-8 cursor-pointer" aria-label="Lihat slide {{ $index + 1 }}">
                            <span class="hero-dot w-2 h-2 rounded-full bg-white/60 hover:bg-white transition-all"></span>
                        </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Visual Category Grid (Carter's Style Circle Cards) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center max-w-xl mx-auto mb-12">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Belanja Berdasarkan Kategori</h2>
        <p class="mt-2 text-slate-500 text-sm">Temukan pakaian yang tepat berdasarkan kategori produk khusus kami</p>
    </div>
    
    <!-- Category Carousel -->
    <div class="relative group/carousel" id="category-carousel-wrapper">
        <!-- Left Arrow (hidden on mobile) -->
        <button id="cat-prev" class="hidden sm:flex absolute -left-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white shadow-md rounded-full w-9 h-9 items-center justify-center text-slate-600 hover:text-primary-500 transition-all opacity-0 group-hover/carousel:opacity-100" aria-label="Sebelumnya">
            <i class="fa-solid fa-chevron-left text-sm"></i>
        </button>
        <!-- Right Arrow (hidden on mobile) -->
        <button id="cat-next" class="hidden sm:flex absolute -right-2 top-1/2 -translate-y-1/2 z-10 bg-white/90 hover:bg-white shadow-md rounded-full w-9 h-9 items-center justify-center text-slate-600 hover:text-primary-500 transition-all opacity-0 group-hover/carousel:opacity-100" aria-label="Berikutnya">
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
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white shadow-sm flex items-center justify-center overflow-hidden border-2 border-slate-100 group-hover:border-primary-400 group-hover:shadow-md transition-all duration-300 relative">
                            @if(isset($cat->image_path) && $cat->image_path)
                                <img src="{{ asset('storage/' . $cat->image_path) }}" alt="{{ $cat->name }}" width="80" height="80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                            @else
                                <img src="{{ asset('storage/assets/' . $imgName) }}" alt="{{ $cat->name }}" width="80" height="80" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
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
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
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
                            <img src="{{ asset('storage/assets/product_baju.webp') }}" 
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
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        
                        <!-- Button CTA -->
                        <a href="{{ route('catalog.show', $product->slug) }}" 
                           class="w-full flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold text-secondary-800 bg-secondary-50 hover:bg-primary-500 hover:text-white transition-all">
                            <span>Detail Produk</span>
                            <i class="fa-solid fa-chevron-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<!-- Store Location Section -->
<section class="bg-slate-50 py-16 border-t border-slate-100">
    @php
        $storeAddress = \App\Models\Setting::get('store_address', 'Jl. Berkah Mulia Raya No. 88, Central Business District, Kota Surakarta, Jawa Tengah 57132');
        $storeHours = \App\Models\Setting::get('store_hours', 'Senin - Sabtu: 08.00 - 17.00 WIB (Minggu Libur)');
        $storePhone = \App\Models\Setting::get('store_phone', '628123456789');
        $storeMapIframe = \App\Models\Setting::get('store_map_iframe', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.0863812739343!2d110.82583857500171!3d-7.56555549244837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a16f2c3d0b2f5%3A0x86da51ccbf56bc2e!2sSurakarta%2C%20Surakarta%20City%2C%20Central%20Java!5e0!3m2!1sen!2sid!4v1718000000000!5m2!1sen!2sid');
        $storeMapLink = \App\Models\Setting::get('store_map_link', 'https://maps.google.com/?q=Berkah+Mulia+Surakarta');
        $storeImage = \App\Models\Setting::get('store_image');
        $storeImagePath = $storeImage ? asset('storage/' . $storeImage) : asset('storefront_location.webp');
    @endphp
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <!-- Left Side: Interactive Photo / Map Tab Switcher -->
            <div class="bg-white rounded-3xl p-3 shadow-md border border-slate-100/80 flex flex-col h-[320px] sm:h-[380px] transition-all duration-300">
                <!-- Tab Headers -->
                <div class="flex bg-slate-100 p-1 rounded-2xl mb-3">
                    <button type="button" onclick="switchLocationTab('photo')" id="tab-btn-photo" 
                            class="flex-1 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer bg-white text-slate-800 shadow-sm">
                        <i class="fa-solid fa-store text-indigo-600"></i>
                        <span>Foto Toko</span>
                    </button>
                    <button type="button" onclick="switchLocationTab('map')" id="tab-btn-map" 
                            class="flex-1 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer text-slate-600 hover:text-slate-800">
                        <i class="fa-solid fa-map-location-dot"></i>
                        <span>Peta Lokasi</span>
                    </button>
                </div>

                <!-- Tab Contents -->
                <div class="grow rounded-2xl overflow-hidden relative">
                    <!-- Photo Tab Content -->
                    <div id="tab-content-photo" class="absolute inset-0 w-full h-full transition-opacity duration-300 opacity-100">
                        <img src="{{ $storeImagePath }}" alt="Toko Berkah Mulia" class="w-full h-full object-cover rounded-2xl">
                    </div>

                    <!-- Map Tab Content -->
                    <div id="tab-content-map" class="absolute inset-0 w-full h-full transition-opacity duration-300 opacity-0 pointer-events-none">
                        @if($storeMapIframe)
                            <iframe 
                                data-src="{{ $storeMapIframe }}" 
                                src="about:blank"
                                class="w-full h-full rounded-2xl border-0" 
                                title="Peta Lokasi Toko Berkah Mulia"
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        @else
                            <div class="w-full h-full rounded-2xl bg-slate-50 flex flex-col items-center justify-center text-slate-400">
                                <i class="fa-solid fa-map-marked-alt text-4xl mb-2"></i>
                                <span class="text-xs">Peta Lokasi Belum Ditentukan</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side: Location Details -->
            <div class="space-y-6 select-none">
                <div>
                    <span class="inline-block text-xs uppercase tracking-widest font-semibold text-secondary-800 bg-secondary-50 px-3 py-1 rounded-full mb-3">
                        {{ $locationBadge }}
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight font-sans">
                        {{ $locationTitle }}
                    </h2>
                    <p class="mt-3 text-slate-500 text-sm leading-relaxed">
                        {{ $locationDescription }}
                    </p>
                </div>

                <div class="space-y-4">
                    <!-- Address -->
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-650 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Alamat Lengkap</h3>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                {{ $storeAddress }}
                            </p>
                        </div>
                    </div>

                    <!-- Operational Hours -->
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-650 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Jam Operasional</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $storeHours }}
                            </p>
                        </div>
                    </div>

                    <!-- Contact -->
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-650 flex items-center justify-center shrink-0 text-sm">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Hubungi Kami</h3>
                            <p class="text-xs text-slate-500 mt-1">
                                WhatsApp: +{{ $storePhone }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    @if($storeMapLink)
                        <a href="{{ $storeMapLink }}" target="_blank"
                           class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold px-6 py-3 rounded-xl shadow-sm transition-all text-xs cursor-pointer">
                            <i class="fa-solid fa-map-location-dot text-indigo-600 text-sm"></i>
                            <span>Petunjuk Arah Google Maps</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
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
                    dot.classList.remove('bg-white/60', 'w-2');
                    dot.classList.add('bg-white', 'w-4');
                } else {
                    dot.classList.remove('bg-white', 'w-4');
                    dot.classList.add('bg-white/60', 'w-2');
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

    // === Category Carousel ===
    document.addEventListener('DOMContentLoaded', function() {
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
            carousel.style.overflowX = 'hidden';
            carousel.style.scrollSnapType = '';
            carousel.style.webkitOverflowScrolling = '';

            track.querySelectorAll('.clone-item').forEach(el => el.remove());

            items = Array.from(track.querySelectorAll('.category-item:not(.clone-item)'));
            itemCount = items.length;
            if (itemCount === 0) return;

            itemWidth = getItemWidth();
            
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

    // Tab switcher logic
    function switchLocationTab(tab) {
        const btnPhoto = document.getElementById('tab-btn-photo');
        const btnMap = document.getElementById('tab-btn-map');
        const contentPhoto = document.getElementById('tab-content-photo');
        const contentMap = document.getElementById('tab-content-map');
 
        if (tab === 'photo') {
            btnPhoto.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer bg-white text-slate-800 shadow-sm";
            btnMap.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer text-slate-600 hover:text-slate-800";
            contentPhoto.classList.remove('opacity-0');
            contentPhoto.classList.add('opacity-100');
            contentMap.classList.remove('opacity-100');
            contentMap.classList.add('opacity-0');
            contentMap.classList.add('pointer-events-none');
            contentPhoto.classList.remove('pointer-events-none');
        } else {
            btnPhoto.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer text-slate-600 hover:text-slate-800";
            btnMap.className = "flex-1 py-2 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2 cursor-pointer bg-white text-slate-800 shadow-sm";
            
            // Dynamic load map iframe
            const iframe = contentMap.querySelector('iframe');
            if (iframe && iframe.getAttribute('src') === 'about:blank') {
                iframe.setAttribute('src', iframe.getAttribute('data-src'));
            }

            contentPhoto.classList.remove('opacity-100');
            contentPhoto.classList.add('opacity-0');
            contentMap.classList.remove('opacity-0');
            contentMap.classList.add('opacity-100');
            contentMap.classList.remove('pointer-events-none');
            contentPhoto.classList.add('pointer-events-none');
        }
    }
</script>
@endsection
