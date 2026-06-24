@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page_title', 'Manajemen Kategori')

@section('content')
@php
    $sort = request('sort', 'created_at');
    $direction = request('direction', 'desc');

    if (!function_exists('getSortLink')) {
        function getSortLink($column, $currentSort, $currentDir) {
            $newDir = ($currentSort === $column && $currentDir === 'asc') ? 'desc' : 'asc';
            return request()->fullUrlWithQuery([
                'sort' => $column,
                'direction' => $newDir
            ]);
        }
    }

    if (!function_exists('getSortIcon')) {
        function getSortIcon($column, $currentSort, $currentDir) {
            if ($currentSort !== $column) {
                return '<i class="fa-solid fa-sort ms-1.5 text-slate-300 text-[9px] transition-colors"></i>';
            }
            return $currentDir === 'asc' 
                ? '<i class="fa-solid fa-sort-up ms-1.5 text-indigo-600 text-[10px]"></i>' 
                : '<i class="fa-solid fa-sort-down ms-1.5 text-indigo-600 text-[10px]"></i>';
        }
    }
@endphp

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8 animate-fade-in">
    <!-- Total Categories -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Kategori</p>
            <h3 class="text-xl font-bold text-slate-800">{{ $totalCategories }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-tags text-indigo-600"></i>
        </div>
    </div>

    <!-- Active Categories -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori Aktif</p>
            <h3 class="text-xl font-bold text-emerald-600">{{ $activeCategories }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
        </div>
    </div>

    <!-- Most Active Category -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori Terpopuler</p>
            <h3 class="text-lg font-bold text-slate-800 truncate max-w-[160px] leading-tight" title="{{ $mostActiveName }}">{{ $mostActiveName }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-fire text-amber-500"></i>
        </div>
    </div>

    <!-- Total Products -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Seluruh Produk</p>
            <h3 class="text-xl font-bold text-slate-800">{{ $totalProducts }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-sky-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-boxes-stacked text-sky-600"></i>
        </div>
    </div>
</div>

<!-- Table Card (Full Width) -->
<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-5">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-tags text-indigo-600"></i>
            </div>
            <div class="min-w-0">
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Daftar Kategori Produk</h4>
                <p class="text-[11px] text-slate-500 mt-0.5 hidden sm:block">Kelola kategori untuk mengelompokkan produk di etalase toko.</p>
            </div>
        </div>
        <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl shrink-0 self-start sm:self-auto">
            <button type="button" id="layout-toggle-table" onclick="toggleLayoutMode('table')" 
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer text-slate-500 hover:text-slate-700">
                <i class="fa-solid fa-table"></i>
                <span>Tabel</span>
            </button>
            <button type="button" id="layout-toggle-grid" onclick="toggleLayoutMode('grid')" 
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer text-slate-500 hover:text-slate-700">
                <i class="fa-solid fa-grip"></i>
                <span>Grid</span>
            </button>
        </div>
        <button type="button" onclick="openCategoryModal(false)"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition-all shadow-sm cursor-pointer w-full sm:w-auto">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Kategori Baru</span>
        </button>
    </div>
    
    <!-- Desktop Table Layout (Visible on desktop/tablet, hidden on mobile) -->
    <div id="category-table-view" class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-sm text-slate-600">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/75 font-bold text-[10px] uppercase tracking-wider text-slate-500 select-none">
                    <th class="py-3.5 px-4 w-16 text-slate-500">No</th>
                    <th class="py-3.5 px-4 text-slate-500">
                        <a href="{{ getSortLink('name', $sort, $direction) }}" class="flex items-center hover:text-indigo-600 transition-colors cursor-pointer">
                            <span>Nama Kategori</span>
                            {!! getSortIcon('name', $sort, $direction) !!}
                        </a>
                    </th>
                    <th class="py-3.5 px-4 text-slate-500">
                        <a href="{{ getSortLink('created_at', $sort, $direction) }}" class="flex items-center hover:text-indigo-600 transition-colors cursor-pointer">
                            <span>Tanggal Dibuat</span>
                            {!! getSortIcon('created_at', $sort, $direction) !!}
                        </a>
                    </th>
                    <th class="py-3.5 px-4 text-center text-slate-500">
                        <a href="{{ getSortLink('products_count', $sort, $direction) }}" class="flex items-center justify-center hover:text-indigo-600 transition-colors cursor-pointer">
                            <span>Jumlah Produk</span>
                            {!! getSortIcon('products_count', $sort, $direction) !!}
                        </a>
                    </th>
                    <th class="py-3.5 px-4 text-center text-slate-500">Link Toko</th>
                    <th class="py-3.5 px-4 text-right text-slate-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $index => $cat)
                    <tr class="hover:bg-slate-50/40 transition-colors cursor-pointer"
                        data-category="{{ json_encode($cat) }}"
                        data-update-url="{{ route('admin.categories.update', $cat->id) }}"
                        onclick="handleCategoryRowClick(event, this)">
                        <td class="py-4 px-4 font-semibold text-slate-450">{{ $categories->firstItem() + $index }}</td>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden border border-slate-100/80 bg-slate-50 shrink-0 relative">
                                    @if($cat->image_path)
                                        <img src="{{ asset('storage/' . $cat->image_path) }}" alt="" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                                        <div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400">
                                            <i class="fa-solid fa-tags text-sm"></i>
                                        </div>
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center bg-slate-100 text-slate-400">
                                            <i class="fa-solid fa-tags text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <span class="font-bold text-slate-800" id="cat-name-{{ $cat->id }}">{{ $cat->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-slate-500 text-xs">
                            {{ $cat->created_at ? $cat->created_at->translatedFormat('d M Y') : '-' }}
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="inline-block bg-indigo-50/50 text-indigo-755 border border-indigo-100/60 text-[10px] font-bold px-2.5 py-1 rounded-lg">
                                {{ $cat->products_count }} Produk
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 text-indigo-650 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100/60 px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer">
                                <i class="fa-solid fa-up-right-from-square text-[10px]"></i>
                                <span>Buka</span>
                            </a>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <button type="button" onclick="openCategoryModal(true, {{ $cat->id }}, '{{ $cat->name }}', '{{ route('admin.categories.update', $cat->id) }}', '{{ $cat->image_path }}')"
                                        title="Edit Kategori"
                                        class="relative group text-indigo-700 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 p-2 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Edit</span>
                                </button>
                                
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" title="Hapus Kategori" onclick="confirmDelete(this, 'Apakah Anda yakin ingin menghapus kategori ini?')" class="relative group text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 border border-rose-105 p-2 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">Belum ada data kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Stacked Card / Grid Layout (Default on mobile, fallback on desktop when toggled) -->
    <div id="category-grid-view" class="block md:hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($categories as $index => $cat)
            <div class="bg-slate-50/50 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between hover:border-indigo-200 hover:shadow-xs transition-all duration-300 cursor-pointer"
                 data-category="{{ json_encode($cat) }}"
                 data-update-url="{{ route('admin.categories.update', $cat->id) }}"
                 onclick="handleCategoryRowClick(event, this)">
                <!-- Info -->
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden border border-slate-100/80 bg-slate-50 shrink-0 relative">
                            @if($cat->image_path)
                                <img src="{{ asset('storage/' . $cat->image_path) }}" alt="" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                                <div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400">
                                    <i class="fa-solid fa-tags text-sm"></i>
                                </div>
                            @else
                                <div class="absolute inset-0 flex items-center justify-center bg-slate-100 text-slate-400">
                                    <i class="fa-solid fa-tags text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <span class="text-[9px] font-semibold text-slate-400">Kategori #{{ $categories->firstItem() + $index }}</span>
                            <h4 class="font-bold text-slate-800 text-sm leading-tight truncate" id="cat-name-mobile-{{ $cat->id }}" title="{{ $cat->name }}">{{ $cat->name }}</h4>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>Jumlah Produk:</span>
                        <span class="bg-indigo-50 text-indigo-700 font-bold text-[10px] px-2.5 py-0.5 rounded-full border border-indigo-100">
                            {{ $cat->products_count }} Produk
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-xs text-slate-500 pb-1">
                        <span>Tanggal Dibuat:</span>
                        <span class="font-medium text-slate-650 text-[11px]">
                            {{ $cat->created_at ? $cat->created_at->translatedFormat('d M Y') : '-' }}
                        </span>
                    </div>
                </div>

                <!-- Actions Button Row -->
                <div class="flex items-center gap-2 border-t border-slate-100 pt-3 mt-3">
                    <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}" target="_blank"
                       class="grow bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                        <i class="fa-solid fa-up-right-from-square text-[10px]"></i>
                        <span>Buka</span>
                    </a>
                    
                    <button type="button" onclick="openCategoryModal(true, {{ $cat->id }}, '{{ $cat->name }}', '{{ route('admin.categories.update', $cat->id) }}', '{{ $cat->image_path }}')"
                            class="grow bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit</span>
                    </button>
                    
                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="grow">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete(this, 'Apakah Anda yakin ingin menghapus kategori ini?')" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-slate-200 rounded-2xl py-12 text-center text-slate-400">
                <i class="fa-solid fa-tags text-3xl mb-3 block text-slate-300"></i>
                Belum ada data kategori.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $categories->links() }}
    </div>
</div>

<!-- Detail Kategori Modal (Scrollable Large Modal) -->
<div id="category-detail-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none opacity-0 transition-opacity duration-300" aria-labelledby="detail-modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop Overlay -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeCategoryDetailModal()"></div>

    <!-- Modal Panel -->
    <div id="category-detail-modal-panel" class="relative z-10 bg-white rounded-3xl text-left shadow-2xl w-full max-w-lg flex flex-col overflow-hidden transform scale-95 transition-all duration-300 ease-out" style="max-height: 85vh;">
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b border-slate-100 p-6 sm:p-8 pb-4 sm:pb-4 shrink-0">
            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-indigo-500"></i>
                <span>Detail Info Kategori</span>
            </h3>
            <button type="button" onclick="closeCategoryDetailModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        
        <!-- Modal Body (Scrollable info wrapper) -->
        <div class="flex-1 overflow-y-auto px-6 sm:px-8 pt-5 pb-2 no-scrollbar space-y-5">
            <!-- Category Summary Card -->
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                <!-- Image Showcase -->
                <div class="w-24 h-24 rounded-2xl border border-slate-100 bg-slate-50 relative overflow-hidden flex items-center justify-center shrink-0">
                    <img id="detail-cat-img" src="" alt="" class="w-full h-full object-cover hidden" onerror="showDetailCatFallback()">
                    <!-- Identical Gray Box Fallback -->
                    <div id="detail-cat-img-fallback" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50 text-slate-400">
                        <i class="fa-solid fa-tags text-2xl mb-1"></i>
                        <span class="text-[9px] font-semibold">Tidak ada foto</span>
                    </div>
                </div>

                <!-- Details -->
                <div class="flex-1 min-w-0 text-center sm:text-left space-y-2">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Nama Kategori</p>
                        <h4 id="detail-cat-name" class="text-lg font-bold text-slate-800 leading-snug"></h4>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 border-t border-slate-150 pt-2.5">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Jumlah Produk</p>
                            <span id="detail-cat-count" class="inline-block bg-indigo-50 text-indigo-755 border border-indigo-100/60 text-[10px] font-bold px-2.5 py-0.5 rounded-lg"></span>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Tanggal Dibuat</p>
                            <span id="detail-cat-created" class="text-slate-600 font-semibold text-xs"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Indicator -->
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl border" id="detail-cat-status-bar">
                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" id="detail-cat-status-icon-wrapper">
                    <i id="detail-cat-status-icon" class="text-sm"></i>
                </div>
                <div>
                    <p class="text-xs font-bold" id="detail-cat-status-label"></p>
                    <p class="text-[10px] text-slate-500" id="detail-cat-status-desc"></p>
                </div>
            </div>

            <!-- Quick Link -->
            <div class="flex items-center gap-2">
                <a id="detail-cat-link" href="#" target="_blank"
                   class="inline-flex items-center gap-1.5 text-indigo-650 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100/60 px-3.5 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer">
                    <i class="fa-solid fa-up-right-from-square text-[10px]"></i>
                    <span>Lihat di Katalog Toko</span>
                </a>
            </div>

            <!-- Product List Section -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-box-open text-[10px]"></i>
                        <span>Daftar Produk dalam Kategori Ini</span>
                    </p>
                    <span id="detail-cat-product-total" class="text-[10px] font-bold text-slate-400"></span>
                </div>
                
                <!-- Product list container -->
                <div id="detail-cat-products-list" class="space-y-2 max-h-56 overflow-y-auto rounded-xl border border-slate-100 bg-slate-50/50 p-2">
                    <!-- Populated dynamically by JS -->
                </div>

                <!-- Empty state -->
                <div id="detail-cat-products-empty" class="hidden text-center py-6 rounded-xl border border-dashed border-slate-200 bg-slate-50/50">
                    <i class="fa-solid fa-inbox text-2xl text-slate-300 mb-2"></i>
                    <p class="text-xs text-slate-400 font-semibold">Belum ada produk di kategori ini</p>
                    <p class="text-[10px] text-slate-350 mt-0.5">Tambahkan produk dari halaman Manajemen Produk</p>
                </div>
            </div>
        </div>
        
        <!-- Modal Actions Footer -->
        <div class="flex gap-3 p-6 sm:px-8 pt-4 border-t border-slate-100 justify-end shrink-0">
            <button type="button" onclick="closeCategoryDetailModal()" class="border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold py-2.5 px-6 rounded-xl transition-all text-xs cursor-pointer">
                Tutup
            </button>
            <button type="button" id="detail-cat-edit-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl transition-all shadow-sm text-xs cursor-pointer flex items-center gap-1.5">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Kategori</span>
            </button>
        </div>
    </div>
</div>

<!-- Category Add/Edit Modal -->
<div id="category-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none opacity-0 transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop Overlay -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeCategoryModal()"></div>

    <!-- Modal Panel -->
    <div id="category-modal-panel" class="relative z-10 bg-white rounded-3xl text-left overflow-hidden shadow-2xl w-full max-w-md p-6 sm:p-8 transform scale-95 transition-all duration-300 ease-out">
            <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4">
                <h3 id="modal-form-title" class="text-base font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-tags text-indigo-500"></i>
                    <span>Tambah Kategori Baru</span>
                </h3>
                <button type="button" onclick="closeCategoryModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            
            <form id="category-form" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div id="modal-method-field"></div>

                <!-- Category Name -->
                <div>
                    <label for="category-name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Kategori *</label>
                    <input type="text" name="name" id="category-name" required placeholder="Contoh: Jaket Anak, Pakaian Bayi"
                           class="w-full border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-sm transition-all"
                           autocomplete="off">
                </div>

                <!-- Category Image Upload -->
                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Foto Kategori (Opsional)</label>
                    
                    <!-- Existing Image Container for Edit Mode -->
                    <div id="existing-image-container" class="hidden bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-between gap-3 animate-fade-in">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-lg overflow-hidden border border-slate-100/80 bg-slate-100 shrink-0">
                                <img id="existing-image-preview" src="" alt="" class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-semibold text-slate-700">Foto saat ini</p>
                                <p class="text-[9px] text-slate-400">Centang untuk menghapus</p>
                            </div>
                        </div>
                        <label class="flex items-center gap-1.5 cursor-pointer select-none bg-rose-50 border border-rose-200 px-2.5 py-1.5 rounded-lg">
                            <input type="checkbox" name="delete_image" value="1" id="delete-image-checkbox" class="rounded text-rose-500 focus:ring-rose-400 w-3.5 h-3.5 border-slate-300">
                            <span class="text-[10px] font-bold text-rose-600">Hapus</span>
                        </label>
                    </div>

                    <!-- File Input (Custom Upload Area) -->
                    <div class="border border-slate-200 rounded-xl p-3 bg-slate-50 hover:bg-slate-100 transition-all relative group cursor-pointer">
                        <input type="file" name="image" id="category-image" accept="image/*"
                               onchange="previewCategoryImage(this)"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex flex-col items-center justify-center py-2 text-center">
                            <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform duration-200">
                                <i class="fa-solid fa-image text-indigo-600"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-600">Pilih foto kategori</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">JPG, PNG, WEBP (Max. 2MB)</span>
                        </div>
                    </div>
                    
                    <!-- New Upload Preview -->
                    <div id="image-preview-container" class="hidden bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center gap-3 animate-fade-in">
                        <div class="w-11 h-11 rounded-lg overflow-hidden border border-slate-100/80 bg-slate-100 shrink-0">
                            <img id="image-preview" src="" alt="" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-slate-700">Foto baru dipilih</p>
                            <p class="text-[9px] text-emerald-600 font-bold flex items-center gap-1"><i class="fa-solid fa-circle-check"></i> Siap diunggah</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3 pt-4 border-t border-slate-100">
                    <button type="button" onclick="closeCategoryModal()" class="grow border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold py-2.5 rounded-xl transition-all text-xs cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" id="modal-submit-btn" class="grow bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-sm text-xs cursor-pointer">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
</div>
@endsection

@section('scripts')
<script>
    const categoryModal = document.getElementById('category-modal');
    const categoryForm = document.getElementById('category-form');
    const modalTitle = document.getElementById('modal-form-title');
    const categoryInput = document.getElementById('category-name');
    const methodField = document.getElementById('modal-method-field');
    const submitBtn = document.getElementById('modal-submit-btn');

    // Open Modal (Modes: Add or Edit)
    function openCategoryModal(isEdit, id = null, name = '', updateUrl = '', imagePath = '') {
        categoryInput.value = name;
        document.getElementById('category-image').value = '';
        document.getElementById('image-preview-container').classList.add('hidden');
        document.getElementById('image-preview').src = '';
        
        const existingContainer = document.getElementById('existing-image-container');
        const existingPreview = document.getElementById('existing-image-preview');
        const deleteCheckbox = document.getElementById('delete-image-checkbox');
        
        if (isEdit) {
            categoryForm.action = updateUrl;
            modalTitle.innerHTML = `<i class="fa-solid fa-pen-to-square text-amber-500"></i><span>Ubah Kategori</span>`;
            methodField.innerHTML = `@method('PUT')`;
            submitBtn.innerText = "Perbarui Kategori";
            submitBtn.className = "grow bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-xl transition-all shadow-sm text-xs";
            
            if (imagePath) {
                existingPreview.src = `/storage/${imagePath}`;
                existingContainer.classList.remove('hidden');
                deleteCheckbox.checked = false;
            } else {
                existingContainer.classList.add('hidden');
                existingPreview.src = '';
            }
        } else {
            categoryForm.action = "{{ route('admin.categories.store') }}";
            modalTitle.innerHTML = `<i class="fa-solid fa-tags text-indigo-500"></i><span>Tambah Kategori Baru</span>`;
            methodField.innerHTML = "";
            submitBtn.innerText = "Simpan Kategori";
            submitBtn.className = "grow bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-sm text-xs";
            existingContainer.classList.add('hidden');
            existingPreview.src = '';
        }
        
        categoryModal.classList.remove('pointer-events-none');
        requestAnimationFrame(() => {
            categoryModal.classList.remove('opacity-0');
            categoryModal.classList.add('opacity-100');
            document.getElementById('category-modal-panel').classList.remove('scale-95');
            document.getElementById('category-modal-panel').classList.add('scale-100');
        });
        setTimeout(() => categoryInput.focus(), 300);
    }

    // Close Modal with animation
    function closeCategoryModal() {
        categoryModal.classList.remove('opacity-100');
        categoryModal.classList.add('opacity-0');
        document.getElementById('category-modal-panel').classList.remove('scale-100');
        document.getElementById('category-modal-panel').classList.add('scale-95');
        setTimeout(() => {
            categoryModal.classList.add('pointer-events-none');
        }, 300);
    }

    // Real-time Image Upload Preview Helper
    function previewCategoryImage(input) {
        const container = document.getElementById('image-preview-container');
        const img = document.getElementById('image-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            container.classList.add('hidden');
            img.src = '';
        }
    }

    // Toggle Layout Mode (Table vs Grid)
    const categoryTableView = document.getElementById('category-table-view');
    const categoryGridView = document.getElementById('category-grid-view');
    const toggleBtnTable = document.getElementById('layout-toggle-table');
    const toggleBtnGrid = document.getElementById('layout-toggle-grid');

    function applyLayoutMode(mode) {
        if (mode === 'table') {
            categoryTableView.style.display = 'block';
            categoryGridView.style.display = 'none';
            
            // Update active states for toggles
            toggleBtnTable.classList.add('bg-white', 'text-indigo-600', 'shadow-xs');
            toggleBtnTable.classList.remove('text-slate-500');
            toggleBtnGrid.classList.remove('bg-white', 'text-indigo-600', 'shadow-xs');
            toggleBtnGrid.classList.add('text-slate-500');
        } else {
            categoryTableView.style.display = 'none';
            categoryGridView.style.display = 'grid';
            
            // Update active states for toggles
            toggleBtnGrid.classList.add('bg-white', 'text-indigo-600', 'shadow-xs');
            toggleBtnGrid.classList.remove('text-slate-500');
            toggleBtnTable.classList.remove('bg-white', 'text-indigo-600', 'shadow-xs');
            toggleBtnTable.classList.add('text-slate-500');
        }
    }

    window.toggleLayoutMode = function(mode) {
        localStorage.setItem('category-layout-mode', mode);
        applyLayoutMode(mode);
    };

    // Initialize layout mode
    const isMobile = window.innerWidth < 768;
    const savedLayout = localStorage.getItem('category-layout-mode');
    if (savedLayout) {
        applyLayoutMode(savedLayout);
    } else {
        // Fallback to responsive defaults (desktop = table, mobile = grid)
        applyLayoutMode(isMobile ? 'grid' : 'table');
    }

    const categoryDetailModal = document.getElementById('category-detail-modal');

    window.showDetailCatFallback = function() {
        const catImg = document.getElementById('detail-cat-img');
        const fallbackImg = document.getElementById('detail-cat-img-fallback');
        if (catImg) catImg.classList.add('hidden');
        if (fallbackImg) fallbackImg.classList.remove('hidden');
    };

    function handleCategoryRowClick(event, element) {
        const interactiveSelector = 'button, input, a, form, label, select, textarea, [onclick]';
        const target = event.target;
        if (target.closest(interactiveSelector) && target.closest(interactiveSelector) !== element) {
            return;
        }
        
        const catData = element.getAttribute('data-category');
        const updateUrl = element.getAttribute('data-update-url');
        if (catData) {
            const cat = JSON.parse(catData);
            openCategoryDetailModal(cat, updateUrl);
        }
    }

    function openCategoryDetailModal(cat, updateUrl) {
        document.getElementById('detail-cat-name').innerText = cat.name;
        document.getElementById('detail-cat-count').innerText = (cat.products_count || 0) + ' Produk';

        let dateStr = '-';
        if (cat.created_at) {
            const d = new Date(cat.created_at);
            if (!isNaN(d.getTime())) {
                const options = { day: 'numeric', month: 'short', year: 'numeric' };
                dateStr = d.toLocaleDateString('id-ID', options);
            }
        }
        document.getElementById('detail-cat-created').innerText = dateStr;

        const catImg = document.getElementById('detail-cat-img');
        const fallbackImg = document.getElementById('detail-cat-img-fallback');

        if (cat.image_path) {
            catImg.src = '/storage/' + cat.image_path;
            catImg.classList.remove('hidden');
            fallbackImg.classList.add('hidden');
        } else {
            catImg.classList.add('hidden');
            fallbackImg.classList.remove('hidden');
        }

        // Status Indicator
        const statusBar = document.getElementById('detail-cat-status-bar');
        const statusIconWrapper = document.getElementById('detail-cat-status-icon-wrapper');
        const statusIcon = document.getElementById('detail-cat-status-icon');
        const statusLabel = document.getElementById('detail-cat-status-label');
        const statusDesc = document.getElementById('detail-cat-status-desc');
        const prodCount = cat.products_count || 0;

        if (prodCount > 0) {
            statusBar.className = 'flex items-center gap-3 px-4 py-3 rounded-xl border border-emerald-100 bg-emerald-50/50';
            statusIconWrapper.className = 'w-8 h-8 rounded-full flex items-center justify-center shrink-0 bg-emerald-100';
            statusIcon.className = 'fa-solid fa-check text-sm text-emerald-600';
            statusLabel.className = 'text-xs font-bold text-emerald-700';
            statusLabel.innerText = 'Kategori Aktif';
            statusDesc.innerText = 'Memiliki ' + prodCount + ' produk terdaftar';
        } else {
            statusBar.className = 'flex items-center gap-3 px-4 py-3 rounded-xl border border-amber-100 bg-amber-50/50';
            statusIconWrapper.className = 'w-8 h-8 rounded-full flex items-center justify-center shrink-0 bg-amber-100';
            statusIcon.className = 'fa-solid fa-triangle-exclamation text-sm text-amber-600';
            statusLabel.className = 'text-xs font-bold text-amber-700';
            statusLabel.innerText = 'Kategori Kosong';
            statusDesc.innerText = 'Belum ada produk yang terdaftar di kategori ini';
        }

        // Catalog Link
        const catLink = document.getElementById('detail-cat-link');
        catLink.href = '/katalog?category=' + (cat.slug || '');

        // Product List
        const listContainer = document.getElementById('detail-cat-products-list');
        const emptyState = document.getElementById('detail-cat-products-empty');
        const totalLabel = document.getElementById('detail-cat-product-total');
        const products = cat.products || [];

        totalLabel.innerText = products.length + ' produk';

        if (products.length === 0) {
            listContainer.classList.add('hidden');
            emptyState.classList.remove('hidden');
        } else {
            listContainer.classList.remove('hidden');
            emptyState.classList.add('hidden');
            listContainer.innerHTML = '';

            products.forEach(function(prod) {
                const imgSrc = (prod.images && prod.images.length > 0) ? '/storage/' + prod.images[0].image_path : '';
                const priceFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(prod.price || 0);
                const variantCount = prod.variants_count || 0;

                let statusLabelText, statusClass;
                switch (prod.status) {
                    case 'ready':
                        statusLabelText = 'Tersedia';
                        statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                        break;
                    case 'po':
                        statusLabelText = 'Pre-Order';
                        statusClass = 'bg-amber-50 text-amber-700 border-amber-100';
                        break;
                    case 'sold_out':
                        statusLabelText = 'Habis';
                        statusClass = 'bg-red-50 text-red-600 border-red-100';
                        break;
                    default:
                        statusLabelText = prod.status || '-';
                        statusClass = 'bg-slate-50 text-slate-600 border-slate-100';
                }

                const card = document.createElement('div');
                card.className = 'flex items-center gap-3 bg-white rounded-xl px-3 py-2.5 border border-slate-100 hover:border-indigo-200 hover:shadow-sm transition-all';

                let imgHtml;
                if (imgSrc) {
                    imgHtml = '<img src="' + imgSrc + '" alt="" class="w-full h-full object-cover" onerror="this.style.display=\'none\'; this.nextElementSibling.classList.replace(\'hidden\', \'flex\');">' +
                              '<div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400"><i class="fa-solid fa-image text-xs"></i></div>';
                } else {
                    imgHtml = '<div class="absolute inset-0 flex items-center justify-center bg-slate-100 text-slate-400"><i class="fa-solid fa-image text-xs"></i></div>';
                }

                card.innerHTML = '' +
                    '<div class="w-9 h-9 rounded-lg overflow-hidden border border-slate-100 bg-slate-50 shrink-0 relative">' + imgHtml + '</div>' +
                    '<div class="flex-1 min-w-0">' +
                        '<p class="text-xs font-bold text-slate-700 truncate">' + (prod.name || '-') + '</p>' +
                        '<div class="flex items-center gap-2 mt-0.5 flex-wrap">' +
                            '<span class="text-[10px] font-semibold text-indigo-600">' + priceFormatted + '</span>' +
                            (prod.sku ? '<span class="text-[10px] text-slate-400">SKU: ' + prod.sku + '</span>' : '') +
                        '</div>' +
                    '</div>' +
                    '<div class="flex flex-col items-end gap-1 shrink-0">' +
                        '<span class="text-[9px] font-bold px-2 py-0.5 rounded-md border ' + statusClass + '">' + statusLabelText + '</span>' +
                        (variantCount > 0 ? '<span class="text-[9px] text-slate-400 font-semibold">' + variantCount + ' varian</span>' : '') +
                    '</div>';

                listContainer.appendChild(card);
            });
        }

        // Edit button
        const editBtn = document.getElementById('detail-cat-edit-btn');
        editBtn.onclick = () => {
            closeCategoryDetailModal();
            setTimeout(() => {
                openCategoryModal(true, cat.id, cat.name, updateUrl, cat.image_path);
            }, 350);
        };

        categoryDetailModal.classList.remove('pointer-events-none');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            categoryDetailModal.classList.remove('opacity-0');
            categoryDetailModal.classList.add('opacity-100');
            document.getElementById('category-detail-modal-panel').classList.remove('scale-95');
            document.getElementById('category-detail-modal-panel').classList.add('scale-100');
        });
    }

    function closeCategoryDetailModal() {
        categoryDetailModal.classList.remove('opacity-100');
        categoryDetailModal.classList.add('opacity-0');
        document.getElementById('category-detail-modal-panel').classList.remove('scale-100');
        document.getElementById('category-detail-modal-panel').classList.add('scale-95');
        setTimeout(() => {
            categoryDetailModal.classList.add('pointer-events-none');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }
</script>
@endsection
