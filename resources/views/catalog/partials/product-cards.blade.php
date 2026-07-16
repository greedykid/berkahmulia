@foreach($products as $product)
    <div class="bg-white rounded-2xl overflow-hidden border border-slate-100 product-card-shadow product-card-zoom flex flex-col justify-between product-card-appear">
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
                    {{ $product->formatted_price }}
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
