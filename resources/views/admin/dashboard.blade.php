@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8 animate-fade-in">
    <!-- Total Products -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Produk</p>
            <h3 class="text-xl font-bold text-slate-800">{{ $totalProducts }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-boxes-stacked text-indigo-600"></i>
        </div>
    </div>

    <!-- Total Categories -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Kategori</p>
            <h3 class="text-xl font-bold text-slate-800">{{ $totalCategories }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-tags text-emerald-600"></i>
        </div>
    </div>

    <!-- Low Stock -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Stok Menipis</p>
            <h3 class="text-xl font-bold text-amber-600">{{ $lowStockCount }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
        </div>
    </div>

    <!-- Out of Stock -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Stok Habis</p>
            <h3 class="text-xl font-bold text-rose-600">{{ $outOfStockCount }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-rose-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-circle-xmark text-rose-600"></i>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm mb-6">
    <div class="flex items-center gap-3 mb-5">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
            <i class="fa-solid fa-bolt text-indigo-600"></i>
        </div>
        <div>
            <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Aksi Cepat</h4>
            <p class="text-[11px] text-slate-500 mt-0.5">Pintasan untuk tugas yang sering dilakukan.</p>
        </div>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <a href="{{ route('admin.products.index', ['add' => 1]) }}" onclick="event.preventDefault(); window.location='{{ route('admin.products.index') }}';" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-slate-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all group cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-plus text-indigo-600 text-sm"></i>
            </div>
            <span class="text-xs font-semibold text-slate-600 group-hover:text-indigo-700 text-center">Tambah Produk</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all group cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-tags text-emerald-600 text-sm"></i>
            </div>
            <span class="text-xs font-semibold text-slate-600 group-hover:text-emerald-700 text-center">Kategori</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-slate-100 hover:border-amber-200 hover:bg-amber-50/30 transition-all group cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-sliders text-amber-600 text-sm"></i>
            </div>
            <span class="text-xs font-semibold text-slate-600 group-hover:text-amber-700 text-center">Pengaturan</span>
        </a>
        <a href="{{ route('admin.products.exportCsv') }}" class="flex flex-col items-center gap-2 p-4 rounded-xl border border-slate-100 hover:border-sky-200 hover:bg-sky-50/30 transition-all group cursor-pointer">
            <div class="w-10 h-10 rounded-xl bg-sky-50 group-hover:bg-sky-100 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-file-arrow-down text-sky-600 text-sm"></i>
            </div>
            <span class="text-xs font-semibold text-slate-600 group-hover:text-sky-700 text-center">Export CSV</span>
        </a>
    </div>
</div>

<!-- Recent Products + Low Stock -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Recent Products Table -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm lg:col-span-2">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <i class="fa-solid fa-clock-rotate-left text-indigo-600"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Produk Baru Ditambahkan</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">5 produk terakhir yang ditambahkan ke katalog.</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1 transition-colors">
                <span>Lihat Semua</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-slate-600">
                <thead>
                    <tr class="border-b border-slate-100 font-semibold text-xs text-left select-none">
                        <th class="py-3 px-4 text-slate-500">Nama Produk</th>
                        <th class="py-3 px-4 text-slate-500">Kategori</th>
                        <th class="py-3 px-4 text-slate-500">Harga</th>
                        <th class="py-3 px-4 text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latestProducts as $prod)
                        <tr class="hover:bg-slate-50/40 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg overflow-hidden border border-slate-100/80 bg-slate-50 shrink-0 relative">
                                        @if($prod->images->isNotEmpty())
                                            <img src="{{ asset('storage/' . $prod->images->first()->image_path) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                                            <div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400">
                                                <i class="fa-regular fa-image text-sm"></i>
                                            </div>
                                        @else
                                            <div class="absolute inset-0 flex items-center justify-center bg-slate-100 text-slate-400">
                                                <i class="fa-regular fa-image text-sm"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 leading-none truncate max-w-[150px]" title="{{ $prod->name }}">{{ $prod->name }}</p>
                                        <p class="text-[10px] text-slate-400 font-mono mt-1">SKU: {{ $prod->sku ?: '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-500">{{ $prod->category->name }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">Rp {{ number_format($prod->price, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4">
                                @if($prod->status === 'ready')
                                    <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-100">Ready</span>
                                @elseif($prod->status === 'po')
                                    <span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-amber-100">Pre-Order</span>
                                @else
                                    <span class="bg-rose-50 text-rose-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-rose-100">Habis</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-xs">Belum ada data produk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sidebar: Low Stock -->
    <div class="space-y-6">
        <!-- Low Stock Alerts -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Peringatan Stok</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Varian dengan stok menipis (1-5 pcs).</p>
            </div>
        </div>
        
        <div class="space-y-3">
            @forelse($lowStockVariants as $variant)
                <div class="flex items-start justify-between border-b border-slate-100 pb-3 last:border-0 last:pb-0 gap-3">
                    <div class="min-w-0">
                        <p class="font-semibold text-xs text-slate-800 truncate" title="{{ $variant->product->name ?? 'Produk' }}">
                            {{ $variant->product->name ?? 'Produk Terhapus' }}
                        </p>
                        <p class="text-[10px] text-slate-400 mt-1">
                            <span class="font-semibold text-slate-500">{{ $variant->size ?: '-' }}</span> / <span class="font-semibold text-slate-500">{{ $variant->color ?: '-' }}</span>
                        </p>
                    </div>
                    <div class="shrink-0">
                        <span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-amber-100">
                            Sisa {{ $variant->stock }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="py-10 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl mb-2 block"></i>
                    Semua stok varian aman!
                </div>
            @endforelse
        </div>
    </div>
    </div> <!-- end sidebar -->
</div>
@endsection
