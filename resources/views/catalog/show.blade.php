@extends('layouts.app')

@section('title', $product->name)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($product->description ?: 'Beli ' . $product->name . ' berkualitas di Berkah Mulia. Pakaian bayi dan anak-anak premium.'), 150))
@section('og_image', $product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/assets/product_baju.webp'))
@section('canonical_url', route('catalog.show', $product->slug))

@section('content')
<div class="bg-slate-50 border-b border-slate-100 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs text-slate-400 mb-2 gap-2">
            <a href="{{ route('home') }}" class="hover:text-slate-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('catalog.index') }}" class="hover:text-slate-600">Katalog</a>
            <span>/</span>
            <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}" class="hover:text-slate-600">{{ $product->category->name }}</a>
            <span>/</span>
            <span class="text-slate-600 font-medium truncate">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-5 pb-10">
    <div class="bg-white border border-slate-100 rounded-3xl p-6 sm:p-10 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
            
            <!-- Left: Gallery Section -->
            <div class="space-y-4 lg:sticky lg:top-28">
                <!-- Main Image Display -->
                <div class="aspect-square bg-slate-50 rounded-2xl overflow-hidden border border-slate-100 relative group product-card-zoom cursor-pointer" onclick="openLightbox()">
                    @if($product->images->isNotEmpty())
                        <img id="main-image" src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                             alt="{{ $product->name }}" 
                             width="600" 
                             height="600"
                             class="w-full h-full object-cover transition-all duration-300"
                             onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                        <div class="hidden absolute inset-0 flex flex-col items-center justify-center bg-slate-100 text-slate-500 p-4">
                            <i class="fa-regular fa-image text-6xl mb-2"></i>
                            <span class="text-xs sm:text-sm font-semibold text-slate-600">Gambar tidak tersedia</span>
                        </div>
                    @else
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-100 text-slate-500 p-4">
                            <i class="fa-regular fa-image text-6xl mb-2"></i>
                            <span class="text-xs sm:text-sm font-semibold text-slate-600">Gambar tidak tersedia</span>
                        </div>
                    @endif
                    
                    <!-- Zoom Overlay -->
                    <div class="absolute inset-0 bg-slate-900/20 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all duration-300">
                        <div class="bg-white/95 text-slate-800 w-12 h-12 rounded-full shadow-lg flex items-center justify-center transform scale-90 group-hover:scale-100 transition-all duration-200">
                            <i class="fa-solid fa-magnifying-glass-plus text-lg text-slate-600"></i>
                        </div>
                    </div>

                    <!-- Left/Right Carousel Controls -->
                    @if($product->images->count() > 1)
                        <button type="button" onclick="event.stopPropagation(); prevImage();" 
                                class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/95 hover:bg-white text-slate-800 w-10 h-10 rounded-full shadow-md flex items-center justify-center opacity-100 md:opacity-0 md:group-hover:opacity-100 hover:scale-110 active:scale-95 transition-all duration-200 z-10 cursor-pointer"
                                aria-label="Gambar sebelumnya">
                            <i class="fa-solid fa-chevron-left text-sm"></i>
                        </button>
                        <button type="button" onclick="event.stopPropagation(); nextImage();" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/95 hover:bg-white text-slate-800 w-10 h-10 rounded-full shadow-md flex items-center justify-center opacity-100 md:opacity-0 md:group-hover:opacity-100 hover:scale-110 active:scale-95 transition-all duration-200 z-10 cursor-pointer"
                                aria-label="Gambar berikutnya">
                            <i class="fa-solid fa-chevron-right text-sm"></i>
                        </button>
                    @endif
                    
                    @if($product->status !== 'ready')
                        <div class="absolute top-4 left-4 bg-slate-900/80 text-white text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider backdrop-blur-sm">
                            {{ $product->status === 'po' ? 'Pre-Order' : 'Habis' }}
                        </div>
                    @endif
                </div>

                <!-- Gallery Thumbnails -->
                @if($product->images->count() > 1)
                    <div id="gallery-thumbnails" class="flex gap-3 overflow-x-auto pb-1 no-scrollbar">
                        @foreach($product->images as $index => $img)
                            <button type="button" onclick="changeImage('{{ asset('storage/' . $img->image_path) }}', this)"
                                    class="w-20 h-20 rounded-xl border-2 border-slate-100 overflow-hidden shrink-0 focus:outline-none transition-all relative {{ $index === 0 ? 'thumbnail-active' : '' }}">
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="Preview" width="80" height="80" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                                <div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400">
                                    <i class="fa-regular fa-image text-lg"></i>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Details Section -->
            <div class="flex flex-col justify-between">
                <div>
                    <!-- Category & SKU -->
                    <div class="flex items-center justify-between gap-4 mb-3">
                        <span class="bg-primary-50 text-primary-600 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                            {{ $product->category->name }}
                        </span>
                        @if($product->sku)
                            <span class="text-xs text-slate-400 font-mono">SKU: {{ $product->sku }}</span>
                        @endif
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight mb-2">
                        {{ $product->name }}
                    </h1>

                    <!-- Price -->
                    <p class="text-2xl font-extrabold text-primary-500 mb-6">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    <!-- Description -->
                    <div class="prose prose-sm text-slate-500 mb-8 border-t border-slate-100 pt-6">
                        <h3 class="font-bold text-slate-800 text-sm mb-2">Deskripsi Produk:</h3>
                        <p class="leading-relaxed">{{ $product->description ?: 'Tidak ada deskripsi produk.' }}</p>
                    </div>

                    <!-- Variants Section -->
                    <div class="border-t border-slate-100 pt-6 space-y-5">
                        
                        <!-- Size Selection -->
                        @php
                            $uniqueSizes = $product->variants->whereNotNull('size')->pluck('size')->unique();
                            $uniqueColors = $product->variants->whereNotNull('color')->pluck('color')->unique();
                        @endphp

                        @if($uniqueSizes->isNotEmpty())
                            <div>
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pilih Ukuran:</h3>
                                    <!-- Size Guide Link -->
                                    <button type="button" onclick="toggleSizeModal(true)" class="text-[10px] text-secondary-900 bg-secondary-50 hover:bg-secondary-100 hover:text-primary-650 px-2.5 py-1 rounded-lg border border-slate-200 transition-all font-bold flex items-center gap-1 shadow-sm uppercase tracking-wider">
                                        <i class="fa-solid fa-ruler-horizontal text-slate-455"></i>
                                        <span>Panduan Ukuran</span>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($uniqueSizes as $size)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="size_select" value="{{ $size }}" class="sr-only peer" onchange="updateVariantDetails()">
                                            <span class="inline-block px-4 py-2.5 border border-slate-200 text-xs font-bold rounded-xl text-slate-650 bg-white peer-checked:border-primary-500 peer-checked:text-primary-600 peer-checked:bg-primary-50/40 hover:border-slate-300 hover:bg-slate-50 transition-all select-none shadow-sm">
                                                {{ $size }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Color Selection -->
                        @if($uniqueColors->isNotEmpty())
                            <div>
                                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Pilih Warna:</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($uniqueColors as $color)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="color_select" value="{{ $color }}" class="sr-only peer" onchange="updateVariantDetails()">
                                            <span class="inline-block px-4 py-2.5 border border-slate-200 text-xs font-bold rounded-xl text-slate-650 bg-white peer-checked:border-primary-500 peer-checked:text-primary-600 peer-checked:bg-primary-50/40 hover:border-slate-300 hover:bg-slate-50 transition-all select-none shadow-sm">
                                                {{ $color }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Stock Indicator -->
                        <div id="stock-indicator" class="text-xs font-semibold text-slate-500 mt-2 bg-slate-50/50 py-3 px-4 rounded-xl flex items-center gap-2 border border-slate-100 w-full shadow-inner">
                            <i class="fa-solid fa-circle-info text-slate-400"></i>
                            <span>Silakan pilih ukuran & warna untuk melihat ketersediaan stok.</span>
                        </div>

                    </div>
                </div>

                <!-- Call To Action (WhatsApp & Cart Buttons) -->
                <div class="border-t border-slate-100 pt-6 sm:pt-8 mt-6 sm:mt-8 space-y-4">
                    <!-- Qty & Cart Row -->
                    <div class="flex items-center gap-3">
                        <!-- Quantity Selector -->
                        <div class="flex items-center border border-slate-200 rounded-2xl bg-slate-50/50 shadow-inner px-1 py-1">
                            <button type="button" onclick="decrementProductQty()" class="w-10 h-10 flex items-center justify-center text-lg font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100/50 rounded-xl transition-all cursor-pointer">-</button>
                            <input type="text" id="product-qty" value="1" readonly class="w-10 text-center font-bold text-slate-700 bg-transparent text-sm border-0 focus:ring-0 select-none">
                            <button type="button" onclick="incrementProductQty()" class="w-10 h-10 flex items-center justify-center text-lg font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-100/50 rounded-xl transition-all cursor-pointer">+</button>
                        </div>
                        
                        <!-- Add to Cart Button -->
                        <button type="button" onclick="addToCart()"
                                class="flex-1 flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 active:scale-95 text-white font-bold py-3.5 px-4 rounded-2xl transition-all duration-200 text-xs sm:text-sm cursor-pointer shadow-md shadow-primary-500/10 border border-primary-400">
                            <i class="fa-solid fa-cart-plus text-base"></i>
                            <span>Tambah ke Keranjang</span>
                        </button>
                    </div>

                    <!-- WhatsApp CTA -->
                    <a id="whatsapp-cta" href="https://wa.me/{{ config('app.whatsapp_number', '628123456789') }}" target="_blank"
                       class="w-full flex items-center justify-center gap-2 sm:gap-2.5 bg-emerald-500 hover:bg-emerald-650 text-white font-bold sm:font-extrabold py-3.5 sm:py-4 px-4 rounded-2xl shadow-lg shadow-emerald-500/20 hover:scale-[1.01] active:scale-[0.99] transition-all duration-200 text-xs sm:text-sm tracking-wide sm:tracking-wider">
                        <i class="fa-brands fa-whatsapp text-xl sm:text-2xl shrink-0"></i>
                        <span>Tanyakan Stok / Beli via WhatsApp</span>
                    </a>
                    <p class="text-[10px] text-slate-400 text-center mt-2.5">
                        *Klik tombol di atas untuk mengirim pesan langsung kepada admin disertai detail ukuran & warna yang Anda pilih.
                    </p>

                    <!-- Share Buttons -->
                    <div class="flex items-center justify-center gap-2 mt-4 pt-4 border-t border-slate-100">
                        <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Bagikan:</span>
                        <a href="https://wa.me/?text={{ urlencode($product->name . ' - Rp ' . number_format($product->price, 0, ',', '.') . ' | Lihat di ' . url()->current()) }}" target="_blank"
                           class="w-8 h-8 flex items-center justify-center rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 transition-all text-sm">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <button type="button" onclick="copyProductLink()" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200 transition-all text-sm" id="copy-link-btn">
                            <i class="fa-solid fa-link"></i>
                        </button>
                        <span id="copy-feedback" class="text-[10px] text-emerald-600 font-semibold hidden ml-1">Tersalin!</span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <div class="mt-16">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-800 mb-8 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-primary-500 rounded-full"></span>
                <span>Koleksi Terkait</span>
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $relProduct)
                    <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 product-card-shadow product-card-zoom flex flex-col justify-between">
                        <a href="{{ route('catalog.show', $relProduct->slug) }}" class="block relative group">
                            <div class="aspect-square bg-slate-50 relative overflow-hidden">
                                @if($relProduct->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $relProduct->images->first()->image_path) }}" 
                                         alt="{{ $relProduct->name }}" 
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
                            </div>
                        </a>
                        <div class="p-4 flex flex-col justify-between grow">
                            <div>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider block mb-1">
                                    {{ $relProduct->category->name }}
                                </span>
                                <a href="{{ route('catalog.show', $relProduct->slug) }}" class="text-sm font-bold text-slate-800 hover:text-primary-500 transition-colors line-clamp-2 mb-2 min-h-[40px]">
                                    {{ $relProduct->name }}
                                </a>
                            </div>
                            <div>
                                <p class="text-primary-500 font-bold text-base mb-3">
                                    Rp {{ number_format($relProduct->price, 0, ',', '.') }}
                                </p>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('catalog.show', $relProduct->slug) }}" 
                                       class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-bold text-secondary-500 bg-secondary-50 hover:bg-primary-500 hover:text-white transition-all font-sans">
                                        <span>Detail</span>
                                        <i class="fa-solid fa-chevron-right text-[9px]"></i>
                                    </a>
                                    <button type="button" onclick="openQuickAddModal({{ $relProduct->id }}, '{{ addslashes($relProduct->name) }}', {{ $relProduct->price }}, '{{ $relProduct->sku ?: 'BM-' . $relProduct->id }}', '{{ $relProduct->images->isNotEmpty() ? $relProduct->images->first()->image_path : '' }}', {{ $relProduct->variants->toJson() }})"
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
        </div>
    @endif
</div>

<!-- Size Chart Modal -->
<div id="size-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none opacity-0 transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Overlay -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="toggleSizeModal(false)"></div>

    <!-- Modal Panel -->
    <div id="size-modal-panel" class="relative z-10 bg-white rounded-3xl text-left overflow-hidden shadow-2xl w-full max-w-lg p-6 sm:p-8 transform scale-95 transition-all duration-300 ease-out max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-ruler text-primary-500"></i>
                    <span>Tabel Panduan Ukuran Pakaian Bayi/Anak</span>
                </h3>
                <button type="button" onclick="toggleSizeModal(false)" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            @php
                $defaultSizeGuide = [
                    ['size' => 'Newborn', 'height' => 's/d 55 cm', 'weight' => 's/d 4 kg'],
                    ['size' => '0-3m / S', 'height' => '55 - 61 cm', 'weight' => '4 - 5.7 kg'],
                    ['size' => '3-6m / M', 'height' => '61 - 67 cm', 'weight' => '5.7 - 7.5 kg'],
                    ['size' => '6-9m / L', 'height' => '67 - 72 cm', 'weight' => '7.5 - 9.3 kg'],
                    ['size' => '9-12m / XL', 'height' => '72 - 78 cm', 'weight' => '9.3 - 11.1 kg'],
                    ['size' => '12-18m / XXL', 'height' => '78 - 83 cm', 'weight' => '11.1 - 12.5 kg'],
                    ['size' => '18-24m', 'height' => '83 - 86 cm', 'weight' => '12.5 - 13.6 kg'],
                ];
                $sizeGuide = \App\Models\Setting::get('size_guide', $defaultSizeGuide);
                $sizeGuideNote = \App\Models\Setting::get('size_guide_note', '*Ukuran di atas adalah estimasi rata-rata standar. Disarankan mengukur tinggi dan berat badan anak terlebih dahulu sebelum melakukan pemesanan.');
            @endphp

            <div class="overflow-x-auto rounded-xl border border-slate-200">
                <table class="min-w-full text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-semibold text-xs border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4 text-left">Ukuran</th>
                            <th class="py-3 px-4 text-left">Tinggi Badan</th>
                            <th class="py-3 px-4 text-left">Berat Badan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($sizeGuide as $row)
                        <tr>
                            <td class="py-2.5 px-4 font-semibold text-slate-800">{{ $row['size'] }}</td>
                            <td class="py-2.5 px-4">{{ $row['height'] }}</td>
                            <td class="py-2.5 px-4">{{ $row['weight'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($sizeGuideNote)
            <p class="text-[11px] text-slate-400 mt-4 leading-relaxed">
                {{ $sizeGuideNote }}
            </p>
            @endif
        </div>
</div>

@endsection

@section('scripts')
<script>
    // Copy product link
    function copyProductLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const feedback = document.getElementById('copy-feedback');
            feedback.classList.remove('hidden');
            setTimeout(() => feedback.classList.add('hidden'), 2000);
        });
    }

    // List of product variants passed from controller
    const productVariants = {!! json_encode($product->variants) !!};
    const productName = "{{ $product->name }}";
    const productCategory = "{{ $product->category->name }}";
    const whatsappBaseUrl = "https://wa.me/{{ config('app.whatsapp_number', '628123456789') }}";
    
    // Product details for cart
    const productId = {{ $product->id }};
    const productPrice = {{ $product->price }};
    const productSku = "{{ $product->sku ?: 'BM-' . $product->id }}";
    const productImage = "{{ $product->images->isNotEmpty() ? $product->images->first()->image_path : '' }}";

    const productImages = [
        @foreach($product->images as $img)
            "{{ asset('storage/' . $img->image_path) }}",
        @endforeach
    ];
    let currentImageIndex = 0;

    // Change preview image on thumbnail click
    function changeImage(src, btn) {
        document.getElementById('main-image').src = src;
        
        // Update currentImageIndex based on src
        const idx = productImages.indexOf(src);
        if (idx !== -1) {
            currentImageIndex = idx;
        }
        
        // Remove active class from all thumbnails
        const thumbnails = btn.parentNode.children;
        for (let i = 0; i < thumbnails.length; i++) {
            thumbnails[i].classList.remove('thumbnail-active');
        }
        
        // Add active class to clicked thumbnail
        btn.classList.add('thumbnail-active');
    }

    function prevImage() {
        if (productImages.length <= 1) return;
        currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
        updateCarouselImage();
    }

    // Toggle Carousel Right
    function nextImage() {
        if (productImages.length <= 1) return;
        currentImageIndex = (currentImageIndex + 1) % productImages.length;
        updateCarouselImage();
    }

    // Update active carousel slide image
    function updateCarouselImage() {
        const newSrc = productImages[currentImageIndex];
        document.getElementById('main-image').src = newSrc;
        
        // Sync thumbnail active class
        const thumbnailContainer = document.getElementById('gallery-thumbnails');
        if (thumbnailContainer) {
            const thumbnails = thumbnailContainer.children;
            for (let i = 0; i < thumbnails.length; i++) {
                if (i === currentImageIndex) {
                    thumbnails[i].classList.add('thumbnail-active');
                    thumbnails[i].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
                } else {
                    thumbnails[i].classList.remove('thumbnail-active');
                }
            }
        }
    }

    // Toggle Size Guide Modal with smooth animation
    function toggleSizeModal(show) {
        const modal = document.getElementById('size-modal');
        const panel = document.getElementById('size-modal-panel');
        if (show) {
            modal.classList.remove('pointer-events-none');
            requestAnimationFrame(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                panel.classList.remove('scale-95');
                panel.classList.add('scale-100');
            });
        } else {
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            panel.classList.remove('scale-100');
            panel.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('pointer-events-none');
            }, 300);
        }
    }

    // Update WhatsApp link & stock dynamically based on radio inputs
    function updateVariantDetails() {
        // Find checked size
        const sizeInput = document.querySelector('input[name="size_select"]:checked');
        const sizeVal = sizeInput ? sizeInput.value : '-';
        
        // Find checked color
        const colorInput = document.querySelector('input[name="color_select"]:checked');
        const colorVal = colorInput ? colorInput.value : '-';
        
        const stockIndicator = document.getElementById('stock-indicator');
        const whatsappBtn = document.getElementById('whatsapp-cta');
        
        // If both are selected (or whichever is present on the page)
        const totalSizes = document.getElementsByName('size_select').length;
        const totalColors = document.getElementsByName('color_select').length;
        
        const sizeSelected = totalSizes === 0 || sizeInput !== null;
        const colorSelected = totalColors === 0 || colorInput !== null;
        
        let stock = 0;
        let variantFound = false;

        // Search for matching variant in list
        if (sizeSelected && colorSelected) {
            const match = productVariants.find(v => {
                const sizeMatch = totalSizes === 0 || v.size === sizeVal;
                const colorMatch = totalColors === 0 || v.color === colorVal;
                return sizeMatch && colorMatch;
            });

            if (match) {
                stock = match.stock;
                variantFound = true;
            }
        }

        if (sizeSelected && colorSelected && variantFound) {
            if (stock > 0) {
                stockIndicator.innerHTML = `<span class="text-emerald-600 font-bold"><i class="fa-solid fa-circle-check mr-1"></i>Stok Tersedia (${stock} pcs)</span>`;
                whatsappBtn.classList.remove('opacity-60', 'pointer-events-none');
                whatsappBtn.innerHTML = `<i class="fa-brands fa-whatsapp text-2xl"></i><span>Tanyakan Stok / Beli via WhatsApp</span>`;
            } else {
                stockIndicator.innerHTML = `<span class="text-rose-500 font-bold"><i class="fa-solid fa-circle-xmark mr-1"></i>Stok Habis</span>`;
                whatsappBtn.classList.remove('opacity-60', 'pointer-events-none');
                whatsappBtn.innerHTML = `<i class="fa-solid fa-envelope text-lg mr-1"></i><span>Pre-Order via WhatsApp</span>`;
            }
        } else if (sizeSelected && colorSelected && !variantFound) {
            // Combination does not exist
            stockIndicator.innerHTML = `<span class="text-amber-500 font-semibold"><i class="fa-solid fa-circle-exclamation mr-1"></i>Kombinasi ini tidak tersedia</span>`;
            whatsappBtn.classList.add('opacity-60', 'pointer-events-none');
        } else {
            stockIndicator.innerHTML = `<i class="fa-solid fa-circle-info text-slate-400"></i> <span>Silakan pilih ukuran & warna untuk melihat ketersediaan stok.</span>`;
            whatsappBtn.classList.remove('opacity-60', 'pointer-events-none');
        }

        // Generate Pre-filled message
        const template = `Halo Berkah Mulia, saya tertarik dengan produk ${productName}\n- Kategori: ${productCategory}\n- Ukuran: ${sizeVal}\n- Warna: ${colorVal}`;
        const encodedMessage = encodeURIComponent(template);
        
        whatsappBtn.href = `${whatsappBaseUrl}?text=${encodedMessage}`;
    }

    // Product quantity helper functions
    function incrementProductQty() {
        const qtyInput = document.getElementById('product-qty');
        if (!qtyInput) return;
        let qty = parseInt(qtyInput.value) || 1;
        if (qty < 99) {
            qtyInput.value = qty + 1;
        }
    }

    function decrementProductQty() {
        const qtyInput = document.getElementById('product-qty');
        if (!qtyInput) return;
        let qty = parseInt(qtyInput.value) || 1;
        if (qty > 1) {
            qtyInput.value = qty - 1;
        }
    }

    function addToCart() {
        // Find selected size & color
        const sizeInput = document.querySelector('input[name="size_select"]:checked');
        const colorInput = document.querySelector('input[name="color_select"]:checked');
        
        const totalSizes = document.getElementsByName('size_select').length;
        const totalColors = document.getElementsByName('color_select').length;
        
        const sizeSelected = totalSizes === 0 || sizeInput !== null;
        const colorSelected = totalColors === 0 || colorInput !== null;
        
        const stockIndicator = document.getElementById('stock-indicator');
        
        // 1. Validation: Make sure size and color are selected if they exist
        if (!sizeSelected || !colorSelected) {
            if (stockIndicator) {
                stockIndicator.innerHTML = `<span class="text-rose-500 font-bold"><i class="fa-solid fa-circle-exclamation mr-1 animate-pulse"></i>Silakan pilih ${!sizeSelected && !colorSelected ? 'Ukuran dan Warna' : (!sizeSelected ? 'Ukuran' : 'Warna')} terlebih dahulu!</span>`;
                stockIndicator.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Add a temporary red border/shadow highlight to stock indicator
                stockIndicator.classList.add('border-rose-300', 'bg-rose-50/50');
                setTimeout(() => {
                    stockIndicator.classList.remove('border-rose-300', 'bg-rose-50/50');
                }, 2000);
            }
            return;
        }
        
        const sizeVal = sizeInput ? sizeInput.value : '-';
        const colorVal = colorInput ? colorInput.value : '-';
        
        // 2. Find matching variant & check stock
        let stock = 0;
        let variantFound = false;
        
        const match = productVariants.find(v => {
            const sizeMatch = totalSizes === 0 || v.size === sizeVal;
            const colorMatch = totalColors === 0 || v.color === colorVal;
            return sizeMatch && colorMatch;
        });

        if (match) {
            stock = match.stock;
            variantFound = true;
        }
        
        if (!variantFound) {
            if (stockIndicator) {
                stockIndicator.innerHTML = `<span class="text-amber-600 font-semibold"><i class="fa-solid fa-circle-exclamation mr-1"></i>Kombinasi ini tidak tersedia!</span>`;
            }
            return;
        }
        
        if (stock <= 0) {
            if (stockIndicator) {
                stockIndicator.innerHTML = `<span class="text-rose-500 font-bold"><i class="fa-solid fa-circle-xmark mr-1"></i>Stok Habis! Tidak bisa menambahkan ke keranjang.</span>`;
            }
            return;
        }
        
        // 3. Read quantity
        const qtyInput = document.getElementById('product-qty');
        const qtyToAdd = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
        
        // Check if there's enough stock
        if (qtyToAdd > stock) {
            if (stockIndicator) {
                stockIndicator.innerHTML = `<span class="text-rose-500 font-bold"><i class="fa-solid fa-circle-exclamation mr-1"></i>Jumlah melebihi stok yang tersedia (Maks. ${stock} pcs)</span>`;
            }
            return;
        }
        
        // 4. Update or push to cart
        let existingIndex = -1;
        for (let i = 0; i < cart.length; i++) {
            if (cart[i].id === productId && cart[i].size === sizeVal && cart[i].color === colorVal) {
                existingIndex = i;
                break;
            }
        }
        
        if (existingIndex !== -1) {
            const newQty = cart[existingIndex].qty + qtyToAdd;
            if (newQty > stock) {
                // Warn user and cap at stock
                if (stockIndicator) {
                    stockIndicator.innerHTML = `<span class="text-rose-500 font-bold"><i class="fa-solid fa-circle-exclamation mr-1"></i>Total di keranjang (${newQty}) melebihi stok yang tersedia (Maks. ${stock} pcs)</span>`;
                }
                cart[existingIndex].qty = stock;
            } else {
                cart[existingIndex].qty = newQty;
            }
        } else {
            const newItem = {
                id: productId,
                name: productName,
                qty: qtyToAdd,
                price: productPrice,
                size: sizeVal,
                color: colorVal,
                sku: productSku,
                image: productImage
            };
            cart.push(newItem);
        }
        
        // Save and update
        saveCart();
        
        // Show success visual feedback on stock indicator or button
        if (stockIndicator) {
            stockIndicator.innerHTML = `<span class="text-emerald-600 font-bold animate-pulse"><i class="fa-solid fa-circle-check mr-1"></i>Berhasil ditambahkan ke keranjang belanja!</span>`;
            setTimeout(() => {
                updateVariantDetails();
            }, 2000);
        }
        
        // 5. Show toast notification instead of opening side drawer automatically
        showToast(`Berhasil menambahkan "${productName}" ke keranjang!`);
    }

    // Run once on page load to pre-initialize the message
    document.addEventListener("DOMContentLoaded", function() {
        updateVariantDetails();
    });

    // Lightbox Modal Functions
    function openLightbox() {
        const lightboxModal = document.getElementById('lightbox-modal');
        const lightboxImage = document.getElementById('lightbox-image');
        const mainImage = document.getElementById('main-image');
        
        if (lightboxModal && lightboxImage && mainImage) {
            // Reset display style and hide fallback in case it previously failed
            lightboxImage.style.display = '';
            if (lightboxImage.nextElementSibling) {
                lightboxImage.nextElementSibling.classList.add('hidden');
            }
            
            lightboxImage.src = mainImage.src;
            lightboxModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }

    function closeLightbox() {
        const lightboxModal = document.getElementById('lightbox-modal');
        if (lightboxModal) {
            lightboxModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    function prevLightboxImage() {
        if (productImages.length <= 1) return;
        currentImageIndex = (currentImageIndex - 1 + productImages.length) % productImages.length;
        updateCarouselImage();
        document.getElementById('lightbox-image').src = productImages[currentImageIndex];
    }

    function nextLightboxImage() {
        if (productImages.length <= 1) return;
        currentImageIndex = (currentImageIndex + 1) % productImages.length;
        updateCarouselImage();
        document.getElementById('lightbox-image').src = productImages[currentImageIndex];
    }

    document.addEventListener('keydown', function(event) {
        const lightboxModal = document.getElementById('lightbox-modal');
        if (lightboxModal && !lightboxModal.classList.contains('hidden')) {
            if (event.key === 'Escape') {
                closeLightbox();
            } else if (event.key === 'ArrowLeft') {
                prevLightboxImage();
            } else if (event.key === 'ArrowRight') {
                nextLightboxImage();
            }
        }
    });
</script>

<!-- Lightbox Fullscreen Modal -->
<div id="lightbox-modal" class="fixed inset-0 z-100 bg-slate-900/90 hidden backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300" onclick="closeLightbox()">
    <!-- Close Button -->
    <button type="button" class="absolute top-6 right-6 text-white/80 hover:text-white hover:scale-110 focus:outline-none transition-all duration-200 z-101">
        <i class="fa-solid fa-xmark text-3xl"></i>
    </button>
    
    <!-- Lightbox Left/Right Controls -->
    @if($product->images->count() > 1)
        <button type="button" onclick="event.stopPropagation(); prevLightboxImage();" 
                class="absolute left-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-200 z-101 cursor-pointer"
                aria-label="Gambar sebelumnya">
            <i class="fa-solid fa-chevron-left text-xl"></i>
        </button>
        <button type="button" onclick="event.stopPropagation(); nextLightboxImage();" 
                class="absolute right-6 top-1/2 -translate-y-1/2 bg-white/10 hover:bg-white/20 text-white w-12 h-12 rounded-full flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-200 z-101 cursor-pointer"
                aria-label="Gambar berikutnya">
            <i class="fa-solid fa-chevron-right text-xl"></i>
        </button>
    @endif

    <!-- Lightbox Image Wrapper -->
    <div class="relative max-w-5xl max-h-[90vh] flex items-center justify-center" onclick="event.stopPropagation()">
        <img id="lightbox-image" src="" alt="Fullscreen Preview" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl border border-white/10 object-contain animate-zoom-in" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
        <div class="hidden flex flex-col items-center justify-center text-slate-300 p-8">
            <i class="fa-regular fa-image text-8xl mb-3 text-slate-400"></i>
            <span class="text-sm font-medium text-slate-200">Gambar tidak tersedia</span>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    @keyframes zoom-in {
        from {
            transform: scale(0.95);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    .animate-zoom-in {
        animation: zoom-in 0.25s ease-out forwards;
    }
</style>
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@type": "Product",
  "name": "{{ $product->name }}",
  "image": [
    "{{ $product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image_path) : asset('storage/assets/product_baju.webp') }}"
  ],
  "description": "{{ strip_tags($product->description ?: 'Pakaian berkualitas untuk bayi dan anak-anak di Berkah Mulia.') }}",
  "sku": "{{ $product->sku ?: 'BM-' . $product->id }}",
  "brand": {
    "@type": "Brand",
    "name": "Berkah Mulia"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "IDR",
    "price": "{{ $product->price }}",
    "priceValidUntil": "{{ \Carbon\Carbon::now()->addYear()->format('Y-m-d') }}",
    "itemCondition": "https://schema.org/NewCondition",
    "availability": "{{ $product->status === 'ready' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
  }
}
</script>
@endsection
