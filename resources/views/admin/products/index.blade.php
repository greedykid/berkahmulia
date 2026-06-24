@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page_title', 'Manajemen Produk')

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

    <!-- Ready Products -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Stok Ready</p>
            <h3 class="text-xl font-bold text-emerald-600">{{ $readyProducts }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
        </div>
    </div>

    <!-- Pre-Order Products -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pre-Order</p>
            <h3 class="text-xl font-bold text-amber-600">{{ $poProducts }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-clock text-amber-500"></i>
        </div>
    </div>

    <!-- Out of Stock Products -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Stok Habis</p>
            <h3 class="text-xl font-bold text-rose-600">{{ $soldOutProducts }}</h3>
        </div>
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-rose-50 flex items-center justify-center text-sm sm:text-base">
            <i class="fa-solid fa-circle-xmark text-rose-600"></i>
        </div>
    </div>
</div>

<!-- Filter & Action Bar -->
<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-5">
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-boxes-stacked text-indigo-600"></i>
            </div>
            <div class="min-w-0">
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Daftar Produk Katalog</h4>
                <p class="text-[11px] text-slate-500 mt-0.5 hidden sm:block">Kelola semua produk yang tersedia di etalase toko Anda.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-wrap w-full sm:w-auto">
            <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl shrink-0 mr-1">
                <button type="button" id="layout-toggle-table" onclick="toggleLayoutMode('table')" 
                        class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer text-slate-500 hover:text-slate-700">
                    <i class="fa-solid fa-table"></i>
                    <span>Tabel</span>
                </button>
                <button type="button" id="layout-toggle-grid" onclick="toggleLayoutMode('grid')" 
                        class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all cursor-pointer text-slate-500 hover:text-slate-700">
                    <i class="fa-solid fa-grip"></i>
                    <span>Grid</span>
                </button>
            </div>
            <a href="{{ route('admin.products.exportCsv', request()->query()) }}"
               class="border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 transition-all cursor-pointer flex-1 sm:flex-none justify-center">
                <i class="fa-solid fa-file-arrow-down text-emerald-500"></i>
                <span>Export</span>
            </a>
            <a href="{{ route('admin.products.exportPdf') }}" target="_blank"
               class="border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 transition-all cursor-pointer flex-1 sm:flex-none justify-center">
                <i class="fa-solid fa-file-pdf text-rose-500"></i>
                <span>PDF</span>
            </a>
            <button type="button" onclick="document.getElementById('import-csv-modal').classList.remove('hidden')"
               class="border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 transition-all cursor-pointer flex-1 sm:flex-none justify-center">
                <i class="fa-solid fa-file-arrow-up text-indigo-500"></i>
                <span>Import</span>
            </button>
            <button type="button" onclick="openProductModal()"
               class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs flex items-center gap-2 transition-all shadow-sm cursor-pointer flex-1 sm:flex-none justify-center">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Produk</span>
            </button>
        </div>
    </div>

    <!-- Search Form -->
    <form action="{{ route('admin.products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 pt-4 border-t border-slate-100">
        <!-- Search Keyword -->
        <div class="relative">
            <input type="text" name="search" placeholder="Cari nama atau SKU..." value="{{ request('search') }}"
                   class="w-full border border-slate-200 text-slate-700 pl-10 pr-4 py-2.5 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-xs transition-all">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
        </div>

        <!-- Category Dropdown -->
        <div>
            <div class="relative custom-dropdown">
                <button type="button" id="filter_category_dropdown_btn" onclick="toggleCustomDropdown('filter-category-dropdown-menu')"
                        class="w-full border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-left focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-xs transition-all flex justify-between items-center cursor-pointer select-none">
                    <span id="filter_selected-category-label">
                        @php
                            $selectedCat = $categories->firstWhere('id', request('category'));
                        @endphp
                        {{ $selectedCat ? $selectedCat->name : 'Semua Kategori' }}
                    </span>
                    <i class="fa-solid fa-chevron-down text-slate-400 text-[10px]"></i>
                </button>
                <input type="hidden" name="category" id="filter_category_hidden" value="{{ request('category') }}">
                
                <div id="filter-category-dropdown-menu" class="hidden absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-30 max-h-60 overflow-y-auto no-scrollbar py-1 text-xs text-slate-700 animate-fade-in">
                    <div class="px-4 py-2 hover:bg-slate-50 cursor-pointer font-semibold text-slate-400 border-b border-slate-100 transition-all text-left"
                         onclick="selectCustomDropdownOption('', 'Semua Kategori', 'filter_category_hidden', 'filter_selected-category-label', 'filter-category-dropdown-menu'); this.closest('form').submit();">
                        Semua Kategori
                    </div>
                    @foreach($categories as $cat)
                        <div class="px-4 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold flex items-center justify-between transition-all text-left"
                             onclick="selectCustomDropdownOption('{{ $cat->id }}', '{{ $cat->name }}', 'filter_category_hidden', 'filter_selected-category-label', 'filter-category-dropdown-menu'); this.closest('form').submit();">
                            <span>{{ $cat->name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Status Dropdown -->
        <div>
            <div class="relative custom-dropdown">
                <button type="button" id="filter_status_dropdown_btn" onclick="toggleCustomDropdown('filter-status-dropdown-menu')"
                        class="w-full border border-slate-200 text-slate-700 px-4 py-2.5 rounded-xl text-left focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 text-xs transition-all flex justify-between items-center cursor-pointer select-none">
                    <span id="filter_selected-status-label">
                        @php
                            $statusLabels = ['ready' => 'Ready', 'po' => 'Pre-Order', 'sold_out' => 'Habis'];
                        @endphp
                        {{ $statusLabels[request('status')] ?? 'Semua Status' }}
                    </span>
                    <i class="fa-solid fa-chevron-down text-slate-400 text-[10px]"></i>
                </button>
                <input type="hidden" name="status" id="filter_status_hidden" value="{{ request('status') }}">
                
                <div id="filter-status-dropdown-menu" class="hidden absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-30 py-1 text-xs text-slate-700 animate-fade-in">
                    <div class="px-4 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                         onclick="selectCustomDropdownOption('', 'Semua Status', 'filter_status_hidden', 'filter_selected-status-label', 'filter-status-dropdown-menu'); this.closest('form').submit();">
                        Semua Status
                    </div>
                    <div class="px-4 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                         onclick="selectCustomDropdownOption('ready', 'Ready', 'filter_status_hidden', 'filter_selected-status-label', 'filter-status-dropdown-menu'); this.closest('form').submit();">
                        Ready
                    </div>
                    <div class="px-4 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                         onclick="selectCustomDropdownOption('po', 'Pre-Order', 'filter_status_hidden', 'filter_selected-status-label', 'filter-status-dropdown-menu'); this.closest('form').submit();">
                        Pre-Order
                    </div>
                    <div class="px-4 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                         onclick="selectCustomDropdownOption('sold_out', 'Habis', 'filter_status_hidden', 'filter_selected-status-label', 'filter-status-dropdown-menu'); this.closest('form').submit();">
                        Habis
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Reset Buttons -->
        <div class="flex gap-2">
            <button type="submit" class="grow bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs transition-all shadow-sm cursor-pointer">
                Cari
            </button>
            @if(request('search') || request('category') || request('status'))
                <a href="{{ route('admin.products.index') }}" class="grow border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold py-2.5 rounded-xl text-xs transition-all text-center flex items-center justify-center">
                    Reset
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Global Form Errors (Specifically when adding a product, shows above list) -->
@if($errors->any())
    <div class="mb-8 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-start gap-2 shadow-sm animate-fade-in">
        <i class="fa-solid fa-triangle-exclamation text-rose-500 mt-0.5"></i>
        <div>
            <p class="text-xs font-bold mb-1">Gagal Menyimpan Produk. Silakan periksa kesalahan berikut:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if(session('import_errors'))
    <div class="mb-8 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-2xl flex items-start gap-2 shadow-sm animate-fade-in">
        <i class="fa-solid fa-circle-exclamation text-amber-500 mt-0.5"></i>
        <div>
            <p class="text-xs font-bold mb-1">Beberapa baris dilewati saat import:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach(session('import_errors') as $importError)
                    <li>{{ $importError }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<!-- Import CSV Modal -->
<div id="import-csv-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('import-csv-modal').classList.add('hidden')"></div>
    <div class="relative z-10 bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 sm:p-8">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-file-arrow-up text-indigo-500"></i>
                <span>Import Produk dari CSV</span>
            </h3>
            <button type="button" onclick="document.getElementById('import-csv-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form action="{{ route('admin.products.importCsv') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">
                <!-- Upload Area -->
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Pilih File CSV</label>
                    <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 hover:border-indigo-300 transition-all relative group cursor-pointer bg-slate-50/50">
                        <input type="file" name="csv_file" accept=".csv,.txt" required id="csv-import-input"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewCsvFile(event)">
                        <div class="flex flex-col items-center justify-center text-center">
                            <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-file-csv text-indigo-600 text-lg"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-600">Klik atau seret file CSV ke sini</span>
                            <span class="text-[10px] text-slate-400 mt-1">Format: .csv (Max. 5MB)</span>
                        </div>
                    </div>
                    <div id="csv-file-info" class="hidden mt-3 flex items-center gap-2 bg-emerald-50 border border-emerald-200 px-3 py-2 rounded-xl">
                        <i class="fa-solid fa-circle-check text-emerald-500"></i>
                        <span id="csv-file-name" class="text-xs font-medium text-emerald-700 truncate"></span>
                    </div>
                </div>

                <!-- Format Guide -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <h5 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info text-indigo-500"></i> Format CSV yang Didukung
                    </h5>
                    <p class="text-[11px] text-slate-500 leading-relaxed mb-2">
                        Baris pertama harus berisi header kolom. Kolom wajib: <strong>nama, kategori, harga, status</strong>.
                    </p>
                    <div class="bg-white rounded-lg p-2.5 border border-slate-200 overflow-x-auto">
                        <code class="text-[10px] text-slate-600 whitespace-nowrap block">nama,sku,kategori,harga,status,deskripsi,ukuran,warna,stok</code>
                        <code class="text-[10px] text-slate-400 whitespace-nowrap block mt-0.5">"Kaos Anak",KA-001,Baju,45000,ready,"Deskripsi",S|M|L,Putih|Biru,15|20|10</code>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">
                        <i class="fa-solid fa-lightbulb text-amber-400 mr-1"></i>
                        Gunakan <strong>|</strong> sebagai pemisah varian. Jika SKU sudah ada, produk akan di-update.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('import-csv-modal').classList.add('hidden')"
                            class="flex-1 border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold py-2.5 rounded-xl text-xs transition-all cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-file-import"></i>
                        <span>Import Sekarang</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Products Table Card -->
<div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
    <!-- Bulk Actions Bar (hidden by default) -->
    <div id="bulk-actions-bar" class="hidden mb-4 bg-indigo-50 border border-indigo-200 rounded-xl px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 animate-fade-in">
        <div>
            <span class="text-xs font-semibold text-indigo-700"><span id="bulk-count">0</span> produk dipilih di halaman ini</span>
            <span id="bulk-select-all-msg" class="hidden text-xs ml-2">
                — <button type="button" onclick="selectAllProducts()" class="text-indigo-600 font-bold underline cursor-pointer">Pilih semua {{ $products->total() }} produk</button>
            </span>
            <span id="bulk-all-selected-msg" class="hidden text-xs font-bold text-indigo-700 ml-2">
                — Semua {{ $products->total() }} produk dipilih
            </span>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative" id="bulk-status-wrapper">
                <button type="button" onclick="toggleBulkStatusDropdown()" class="flex items-center gap-2 bg-white border border-indigo-200 hover:border-indigo-300 text-slate-700 pl-3 pr-2.5 py-1.5 rounded-lg text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-400/30 cursor-pointer transition-all min-w-[140px] justify-between">
                    <span><i class="fa-solid fa-arrows-rotate text-indigo-500 mr-1.5 text-[10px]"></i>Ubah Status</span>
                    <i class="fa-solid fa-chevron-down text-[9px] text-slate-400 transition-transform duration-200" id="bulk-status-chevron"></i>
                </button>
                <div id="bulk-status-menu" class="absolute right-0 top-full mt-1.5 w-44 bg-white border border-slate-100 rounded-xl shadow-lg z-50 py-1.5 transition-all duration-200 ease-out origin-top scale-95 opacity-0 pointer-events-none">
                    <button type="button" data-status="ready" onclick="applyBulkStatus('ready')" class="w-full text-left px-4 py-2 text-xs font-medium hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 transition-colors flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Ready</span>
                    </button>
                    <button type="button" data-status="po" onclick="applyBulkStatus('po')" class="w-full text-left px-4 py-2 text-xs font-medium hover:bg-amber-50 text-slate-600 hover:text-amber-700 transition-colors flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Pre-Order</span>
                    </button>
                    <button type="button" data-status="sold_out" onclick="applyBulkStatus('sold_out')" class="w-full text-left px-4 py-2 text-xs font-medium hover:bg-rose-50 text-slate-600 hover:text-rose-700 transition-colors flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span>Habis</span>
                    </button>
                </div>
            </div>
            <button type="button" onclick="bulkDelete()" class="bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold px-3 py-1.5 rounded-lg text-xs border border-rose-200 transition-all cursor-pointer">
                <i class="fa-solid fa-trash-can mr-1"></i>Hapus
            </button>
        </div>
    </div>

    <!-- Desktop Table Layout (Visible on desktop/tablet, hidden on mobile) -->
    <div id="product-table-view" class="hidden md:block overflow-x-auto">
        <table class="min-w-full text-sm text-slate-650">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50/75 font-bold text-[10px] uppercase tracking-wider text-slate-500 select-none">
                    <th class="py-3.5 px-3 w-10 text-center">
                        <input type="checkbox" id="select-all-products" onchange="toggleAllProducts(this)" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-400 cursor-pointer">
                    </th>
                    <th class="py-3.5 px-4 text-left">
                        <a href="{{ getSortLink('name', $sort, $direction) }}" class="flex items-center hover:text-indigo-600 transition-colors cursor-pointer">
                            <span>Info Produk</span>
                            {!! getSortIcon('name', $sort, $direction) !!}
                        </a>
                    </th>
                    <th class="py-3.5 px-4 text-left">
                        <a href="{{ getSortLink('category', $sort, $direction) }}" class="flex items-center hover:text-indigo-600 transition-colors cursor-pointer">
                            <span>Kategori</span>
                            {!! getSortIcon('category', $sort, $direction) !!}
                        </a>
                    </th>
                    <th class="py-3.5 px-4 text-left">
                        <a href="{{ getSortLink('price', $sort, $direction) }}" class="flex items-center hover:text-indigo-600 transition-colors cursor-pointer">
                            <span>Harga</span>
                            {!! getSortIcon('price', $sort, $direction) !!}
                        </a>
                    </th>
                    <th class="py-3.5 px-4 text-left normal-case tracking-normal text-xs font-semibold">Varian (Ukuran / Warna / Stok)</th>
                    <th class="py-3.5 px-4 text-left">
                        <a href="{{ getSortLink('status', $sort, $direction) }}" class="flex items-center hover:text-indigo-600 transition-colors cursor-pointer">
                            <span>Status</span>
                            {!! getSortIcon('status', $sort, $direction) !!}
                        </a>
                    </th>
                    <th class="py-3.5 px-4 text-center">Populer</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($products as $prod)
                    <tr class="hover:bg-slate-50/40 transition-colors cursor-pointer" data-product-detail="{{ json_encode($prod) }}" onclick="handleProductRowClick(event, this)">
                        <td class="py-4 px-3 text-center">
                            <input type="checkbox" name="bulk_ids[]" value="{{ $prod->id }}" class="product-checkbox w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-400 cursor-pointer" onchange="updateBulkBar()">
                        </td>
                        <!-- Image & Name -->
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-100/80 bg-slate-50 shrink-0 relative">
                                    @if($prod->images->isNotEmpty())
                                        <img src="{{ asset('storage/' . $prod->images->first()->image_path) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                                        <div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400">
                                            <i class="fa-regular fa-image text-base"></i>
                                        </div>
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center bg-slate-100 text-slate-400">
                                            <i class="fa-regular fa-image text-base"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 leading-snug line-clamp-2 max-w-[240px]" title="{{ $prod->name }}">{{ $prod->name }}</p>
                                    <span class="inline-block bg-slate-100 text-slate-500 font-mono text-[9px] px-1.5 py-0.5 rounded-md mt-1.5">SKU: {{ $prod->sku ?: '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <!-- Category -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="inline-block bg-indigo-50/50 text-indigo-755 border border-indigo-100/60 text-[10px] font-bold px-2.5 py-1 rounded-lg">
                                {{ $prod->category->name }}
                            </span>
                        </td>
                        <!-- Price -->
                        <td class="py-4 px-4 font-bold text-slate-800 whitespace-nowrap text-sm">
                            Rp {{ number_format($prod->price, 0, ',', '.') }}
                        </td>
                        <!-- Variants -->
                        <td class="py-4 px-4 text-xs">
                            @if($prod->variants->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5 max-w-[280px]">
                                    @foreach($prod->variants as $variant)
                                        <span class="inline-flex items-center gap-1 bg-slate-50 border border-slate-200 text-slate-600 px-2 py-0.5 rounded-lg font-bold text-[9px] whitespace-nowrap"
                                              title="Warna: {{ $variant->color ?: '-' }}, Stok: {{ $variant->stock }}">
                                            <span>{{ $variant->size ?: '-' }}</span>
                                            <span class="w-1.5 h-1.5 rounded-full {{ $variant->stock > 0 ? 'bg-emerald-550 shadow-xs' : 'bg-rose-500' }}" style="background-color: {{ $variant->stock > 0 ? '#10b981' : '#ef4444' }}"></span>
                                            <span class="text-slate-400 font-medium">{{ $variant->stock }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-450 text-[10px] italic">Tidak ada varian</span>
                            @endif
                        </td>
                        <!-- Status -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            @if($prod->status === 'ready')
                                <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-3 py-1 rounded-full border border-emerald-100 whitespace-nowrap">Ready</span>
                            @elseif($prod->status === 'po')
                                <span class="bg-amber-50 text-amber-700 text-[10px] font-bold px-3 py-1 rounded-full border border-amber-100 whitespace-nowrap">Pre-Order</span>
                            @else
                                <span class="bg-rose-50 text-rose-700 text-[10px] font-bold px-3 py-1 rounded-full border border-rose-100 whitespace-nowrap">Habis</span>
                            @endif
                        </td>
                        <!-- Populer -->
                        <td class="py-4 px-4 text-center whitespace-nowrap">
                            <form action="{{ route('admin.products.togglePopular', $prod->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" title="{{ $prod->is_popular ? 'Nonaktifkan Populer' : 'Aktifkan Populer' }}" 
                                        class="transition-all p-1.5 rounded-lg hover:bg-slate-100 cursor-pointer inline-flex items-center justify-center">
                                    @if($prod->is_popular)
                                        <i class="fa-solid fa-star text-amber-500 text-base animate-pulse"></i>
                                    @else
                                        <i class="fa-regular fa-star text-slate-350 text-base hover:text-amber-400"></i>
                                    @endif
                                </button>
                            </form>
                        </td>
                        <!-- Actions -->
                        <td class="py-4 px-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <form action="{{ route('admin.products.store') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="category_id" value="{{ $prod->category_id }}">
                                    <input type="hidden" name="name" value="{{ $prod->name }} (Copy)">
                                    <input type="hidden" name="price" value="{{ $prod->price }}">
                                    <input type="hidden" name="status" value="{{ $prod->status }}">
                                    <input type="hidden" name="description" value="{{ $prod->description }}">
                                    @foreach($prod->variants as $vi => $v)
                                        <input type="hidden" name="variants[{{ $vi }}][size]" value="{{ $v->size }}">
                                        <input type="hidden" name="variants[{{ $vi }}][color]" value="{{ $v->color }}">
                                        <input type="hidden" name="variants[{{ $vi }}][stock]" value="{{ $v->stock }}">
                                    @endforeach
                                    <button type="button" onclick="confirmDuplicate(this)" title="Duplikat" class="relative group text-slate-600 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 border border-slate-200 p-2 rounded-xl text-xs transition-all cursor-pointer">
                                        <i class="fa-solid fa-copy"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Duplikat</span>
                                    </button>
                                </form>

                                <button type="button"
                                        onclick="openEditProductModal(this)"
                                        data-product="{{ json_encode($prod) }}"
                                        data-update-url="{{ route('admin.products.update', $prod->id) }}"
                                        title="Edit Produk"
                                        class="relative group text-indigo-700 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 p-2 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Edit</span>
                                </button>
                                
                                <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="redirect_url" value="{{ request()->fullUrl() }}">
                                    <button type="button" title="Hapus Produk" onclick="confirmDelete(this, 'Apakah Anda yakin ingin menghapus produk ini beserta seluruh gambarnya?')" class="relative group text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 border border-rose-105 p-2 rounded-xl text-xs font-semibold transition-all cursor-pointer">
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-16 text-center text-slate-400">
                            <i class="fa-solid fa-boxes-open text-3xl mb-3 block text-slate-300"></i>
                            Belum ada data produk atau produk tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Stacked Card / Grid Layout (Default on mobile, fallback on desktop when toggled) -->
    <div id="product-grid-view" class="block md:hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($products as $prod)
            <div class="bg-slate-50/50 border border-slate-200 rounded-2xl p-4 flex flex-col justify-between hover:border-indigo-200 hover:shadow-xs transition-all duration-300 cursor-pointer" data-product-detail="{{ json_encode($prod) }}" onclick="handleProductRowClick(event, this)">
                <div class="space-y-3 text-left">
                    <!-- Header: Info & Thumbnail -->
                    <div class="flex items-start gap-3">
                        <div class="w-14 h-14 rounded-xl overflow-hidden border border-slate-100/80 bg-slate-50 shrink-0 relative">
                            @if($prod->is_popular)
                                <div class="absolute top-0.5 right-0.5 bg-amber-500 text-white rounded-full w-4 h-4 flex items-center justify-center text-[8px] z-10 shadow-sm animate-pulse">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            @endif
                            @if($prod->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $prod->images->first()->image_path) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                                <div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400">
                                    <i class="fa-regular fa-image text-base"></i>
                                </div>
                            @else
                                <div class="absolute inset-0 flex items-center justify-center bg-slate-100 text-slate-400">
                                    <i class="fa-regular fa-image text-base"></i>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="inline-block bg-slate-100 text-slate-500 font-semibold text-[9px] uppercase px-2 py-0.5 rounded-md mb-1.5">
                                {{ $prod->category->name }}
                            </span>
                            <h4 class="font-bold text-slate-800 text-sm leading-snug line-clamp-2" title="{{ $prod->name }}">{{ $prod->name }}</h4>
                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">SKU: {{ $prod->sku ?: '-' }}</p>
                        </div>
                    </div>

                    <!-- Price & Status Info -->
                    <div class="flex items-center justify-between border-t border-slate-100 pt-2.5">
                        <div>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Harga</p>
                            <p class="font-bold text-slate-800 text-sm">Rp {{ number_format($prod->price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            @if($prod->status === 'ready')
                                <span class="inline-block bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-100">Ready</span>
                            @elseif($prod->status === 'po')
                                <span class="inline-block bg-amber-50 text-amber-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-amber-100">Pre-Order</span>
                            @else
                                <span class="inline-block bg-rose-50 text-rose-700 text-[10px] font-bold px-2.5 py-0.5 rounded-full border border-rose-100">Habis</span>
                            @endif
                        </div>
                    </div>

                    <!-- Variants Info -->
                    <div class="border-t border-slate-100 pt-2.5 space-y-1">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Varian & Stok</p>
                        @if($prod->variants->isNotEmpty())
                            <div class="flex flex-wrap gap-1">
                                @foreach($prod->variants as $variant)
                                    <span class="border px-2 py-0.5 rounded-lg font-bold text-[10px] inline-flex items-center gap-1.5 {{ $variant->stock > 0 ? 'bg-emerald-50/50 border-emerald-100 text-emerald-700' : 'bg-rose-50/50 border-rose-100 text-rose-700' }}"
                                          title="Warna: {{ $variant->color ?: '-' }}, Stok: {{ $variant->stock }}">
                                        <span>{{ $variant->size ?: '-' }}</span>
                                        <span class="w-1 h-1 rounded-full {{ $variant->stock > 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        <span class="font-semibold text-[9px] opacity-90">{{ $variant->stock > 0 ? 'Tersedia' : 'Habis' }} ({{ $variant->stock }})</span>
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-slate-400 text-[10px] italic">Tidak ada varian</span>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons Row (Finger-friendly targets) -->
                <div class="flex items-center gap-2 border-t border-slate-100 pt-3 mt-3">
                    <form action="{{ route('admin.products.togglePopular', $prod->id) }}" method="POST" class="grow">
                        @csrf
                        <button type="submit" class="w-full font-bold py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer {{ $prod->is_popular ? 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200' : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200' }}">
                            <i class="fa-{{ $prod->is_popular ? 'solid' : 'regular' }} fa-star text-amber-500"></i>
                            <span>{{ $prod->is_popular ? 'Populer' : 'Jadikan Populer' }}</span>
                        </button>
                    </form>
                    
                    <button type="button"
                            onclick="openEditProductModal(this)"
                            data-product="{{ json_encode($prod) }}"
                            data-update-url="{{ route('admin.products.update', $prod->id) }}"
                            class="grow bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span>Edit</span>
                    </button>
                    
                    <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" class="grow">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="redirect_url" value="{{ request()->fullUrl() }}">
                        <button type="button" onclick="confirmDelete(this, 'Apakah Anda yakin ingin menghapus produk ini beserta seluruh gambarnya?')" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold py-2 rounded-xl text-xs flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Hapus</span>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white border border-slate-200 rounded-2xl py-12 text-center text-slate-400">
                <i class="fa-solid fa-boxes-open text-3xl mb-3 block text-slate-300"></i>
                Belum ada data produk atau produk tidak ditemukan.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="pt-6">
        {{ $products->links() }}
    </div>
</div>

<!-- Detail Produk Modal (Scrollable Large Modal) -->
<div id="product-detail-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none opacity-0 transition-opacity duration-300" aria-labelledby="detail-modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop Overlay -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeProductDetailModal()"></div>

    <!-- Modal Panel -->
    <div id="product-detail-modal-panel" class="relative z-10 bg-white rounded-3xl text-left shadow-2xl w-full max-w-3xl max-h-[85vh] flex flex-col overflow-hidden p-6 sm:p-8 transform scale-95 transition-all duration-300 ease-out">
        <!-- Modal Header -->
        <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4 shrink-0">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-indigo-500"></i>
                <span>Detail Info Produk</span>
            </h3>
            <button type="button" onclick="closeProductDetailModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none cursor-pointer">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <!-- Modal Body (Scrollable info wrapper) -->
        <div class="flex-1 overflow-y-auto px-2.5 pb-4 no-scrollbar space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                
                <!-- Left Column: Image Showcase -->
                <div class="md:col-span-5 flex flex-col gap-3">
                    <!-- Main Image Preview Container -->
                    <div class="w-full aspect-square rounded-2xl border border-slate-100 bg-slate-50 relative overflow-hidden flex items-center justify-center">
                        <img id="detail-main-img" src="" alt="" class="w-full h-full object-cover hidden" onerror="showDetailMainFallback()">
                        <!-- Identical Gray Box Fallback -->
                        <div id="detail-img-fallback" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-50 text-slate-400">
                            <i class="fa-regular fa-image text-3xl mb-2"></i>
                            <span class="text-xs font-semibold">Gambar tidak tersedia</span>
                        </div>
                    </div>
                    <!-- Thumbnail List -->
                    <div id="detail-thumbnails" class="grid grid-cols-4 gap-2">
                        <!-- Dynamic Thumbnails inserted via JavaScript -->
                    </div>
                </div>

                <!-- Right Column: Details -->
                <div class="md:col-span-7 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <!-- Category & Popular Badge -->
                        <div class="flex flex-wrap items-center gap-2">
                            <span id="detail-category" class="inline-block bg-indigo-50 text-indigo-700 border border-indigo-100 text-[10px] font-bold px-2.5 py-1 rounded-lg"></span>
                            <span id="detail-status" class="text-[10px] font-bold px-2.5 py-1 rounded-full border"></span>
                            <span id="detail-popular-badge" class="hidden bg-amber-50 text-amber-700 text-[10px] font-bold px-2.5 py-1 rounded-full border border-amber-100 items-center gap-1">
                                <i class="fa-solid fa-star text-amber-500 text-[9px]"></i> Populer
                            </span>
                        </div>

                        <!-- Product Name -->
                        <h4 id="detail-name" class="text-xl font-bold text-slate-800 leading-snug"></h4>
                        
                        <!-- SKU -->
                        <p class="text-xs text-slate-400 font-mono">SKU: <span id="detail-sku" class="text-slate-600 font-semibold"></span></p>

                        <!-- Price -->
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Harga Retail</p>
                            <p id="detail-price" class="text-2xl font-black text-indigo-600"></p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="space-y-1.5 flex-1 min-h-0">
                        <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Deskripsi</h5>
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-3 max-h-[150px] overflow-y-auto">
                            <p id="detail-description" class="text-xs text-slate-650 leading-relaxed whitespace-pre-line text-left"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Variants & Stock Section -->
            <div class="space-y-3 pt-2">
                <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider border-b border-slate-100 pb-1">Varian & Ketersediaan Stok</h4>
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full text-xs text-slate-650">
                        <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] border-b border-slate-200">
                            <tr>
                                <th class="py-2 px-3 text-left">Ukuran</th>
                                <th class="py-2 px-3 text-left">Warna</th>
                                <th class="py-2 px-3 text-left w-32">Stok</th>
                            </tr>
                        </thead>
                        <tbody id="detail-variants-body" class="divide-y divide-slate-100">
                            <!-- Dynamic rows -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Modal Actions Footer -->
        <div class="flex gap-3 pt-4 border-t border-slate-100 justify-end mt-4 shrink-0">
            <button type="button" onclick="closeProductDetailModal()" class="border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold py-2.5 px-6 rounded-xl transition-all text-xs cursor-pointer">
                Tutup
            </button>
            <button type="button" id="detail-edit-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl transition-all shadow-sm text-xs cursor-pointer flex items-center gap-1.5">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Produk</span>
            </button>
        </div>
    </div>
</div>

<!-- Tambah Produk Modal (Scrollable Large Modal) -->
<div id="product-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none opacity-0 transition-opacity duration-300" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop Overlay -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeProductModal()"></div>

    <!-- Modal Panel -->
    <div id="product-modal-panel" class="relative z-10 bg-white rounded-3xl text-left shadow-2xl w-full max-w-3xl max-h-[85vh] flex flex-col overflow-hidden p-6 sm:p-8 transform scale-95 transition-all duration-300 ease-out">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4 shrink-0">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-indigo-500"></i>
                    <span>Tambah Produk Baru</span>
                </h3>
                <button type="button" onclick="closeProductModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0">
                @csrf
                <!-- Modal Body (Scrollable form body wrapper) -->
                <div class="flex-1 overflow-y-auto px-2.5 pb-4 no-scrollbar space-y-6">

                    <!-- Informasi Dasar -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider border-b border-slate-100 pb-1">Informasi Dasar</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Produk *</label>
                                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Contoh: Kaos Anak Kancing Polos"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all">
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category_id_hidden" class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori *</label>
                                <div class="relative custom-dropdown">
                                    <button type="button" id="category_dropdown_btn" onclick="toggleCustomDropdown('category-dropdown-menu')"
                                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl text-left focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all flex justify-between items-center cursor-pointer select-none">
                                        <span id="selected-category-label">
                                            @php
                                                $selectedCat = $categories->firstWhere('id', old('category_id'));
                                            @endphp
                                            {{ $selectedCat ? $selectedCat->name : 'Pilih Kategori' }}
                                        </span>
                                        <i class="fa-solid fa-chevron-down text-slate-400 text-[10px]"></i>
                                    </button>
                                    <input type="hidden" name="category_id" id="category_id_hidden" value="{{ old('category_id') }}" required>
                                    
                                    <div id="category-dropdown-menu" class="hidden absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-30 max-h-60 overflow-y-auto no-scrollbar py-1 text-xs text-slate-700 animate-fade-in">
                                        <div class="px-3 py-2 hover:bg-slate-50 cursor-pointer font-semibold text-slate-400 border-b border-slate-100 transition-all text-left"
                                             onclick="selectCustomDropdownOption('', 'Pilih Kategori', 'category_id_hidden', 'selected-category-label', 'category-dropdown-menu')">
                                            Pilih Kategori
                                        </div>
                                        @foreach($categories as $cat)
                                            <div class="px-3 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold flex items-center justify-between transition-all"
                                                 onclick="selectCustomDropdownOption('{{ $cat->id }}', '{{ $cat->name }}', 'category_id_hidden', 'selected-category-label', 'category-dropdown-menu')">
                                                <span>{{ $cat->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- SKU -->
                            <div>
                                <label for="sku" class="block text-xs font-semibold text-slate-700 mb-1.5">Kode SKU (Opsional)</label>
                                <input type="text" name="sku" id="sku" value="{{ old('sku') }}" placeholder="Contoh: BJ-002"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all">
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-xs font-semibold text-slate-700 mb-1.5">Harga (Rp) *</label>
                                <input type="number" name="price" id="price" required value="{{ old('price') }}" min="0" placeholder="Contoh: 35000"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status_hidden" class="block text-xs font-semibold text-slate-700 mb-1.5">Status Stok *</label>
                                <div class="relative custom-dropdown">
                                    <button type="button" id="status_dropdown_btn" onclick="toggleCustomDropdown('status-dropdown-menu')"
                                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl text-left focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all flex justify-between items-center cursor-pointer select-none">
                                        <span id="selected-status-label">
                                            @php
                                                $statusLabels = ['ready' => 'Ready', 'po' => 'Pre-Order', 'sold_out' => 'Habis'];
                                            @endphp
                                            {{ $statusLabels[old('status', 'ready')] }}
                                        </span>
                                        <i class="fa-solid fa-chevron-down text-slate-400 text-[10px]"></i>
                                    </button>
                                    <input type="hidden" name="status" id="status_hidden" value="{{ old('status', 'ready') }}" required>
                                    
                                    <div id="status-dropdown-menu" class="hidden absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-30 py-1 text-xs text-slate-700 animate-fade-in">
                                        <div class="px-3 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                                             onclick="selectCustomDropdownOption('ready', 'Ready', 'status_hidden', 'selected-status-label', 'status-dropdown-menu')">
                                            Ready
                                        </div>
                                        <div class="px-3 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                                             onclick="selectCustomDropdownOption('po', 'Pre-Order', 'status_hidden', 'selected-status-label', 'status-dropdown-menu')">
                                            Pre-Order
                                        </div>
                                        <div class="px-3 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                                             onclick="selectCustomDropdownOption('sold_out', 'Habis', 'status_hidden', 'selected-status-label', 'status-dropdown-menu')">
                                            Habis
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Produk Populer -->
                            <div class="md:col-span-2 flex items-center bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 hover:bg-slate-100/50 transition-all">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="is_popular" id="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }}
                                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-400 cursor-pointer">
                                </div>
                                <div class="ms-3 text-xs select-none">
                                    <label for="is_popular" class="font-bold text-slate-700 cursor-pointer flex items-center gap-1.5">
                                        <i class="fa-solid fa-star text-amber-500 text-[10px]"></i>
                                        Produk Populer (Unggulan)
                                    </label>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Tampilkan produk ini di bagian "Koleksi Terpopuler" di homepage.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Lengkap</label>
                            <textarea name="description" id="description" rows="3" placeholder="Tuliskan detail bahan, kenyamanan, atau petunjuk ukuran..."
                                      class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Foto Produk -->
                    <div class="space-y-4 pt-2">
                        <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider border-b border-slate-100 pb-1">Foto Produk</h4>

                        <div class="border border-slate-200 rounded-xl p-3 bg-slate-50 hover:bg-slate-100 transition-all relative group cursor-pointer">
                            <input type="file" name="images[]" id="add_product_images" multiple accept="image/*"
                                   onchange="handleFileSelect(this, false)"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="flex flex-col items-center justify-center py-3 text-center">
                                <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform duration-200">
                                    <i class="fa-solid fa-camera text-indigo-600"></i>
                                </div>
                                <span class="text-xs font-semibold text-slate-600">Pilih foto produk</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">Format: JPG, JPEG, PNG, WEBP, GIF, SVG, BMP, TIFF, HEIC, HEIF (Max. 10MB per file)</span>
                            </div>
                        </div>
                        
                        <!-- Pratinjau Foto Baru -->
                        <div id="add_images_preview_section" class="hidden">
                            <label class="block text-[10px] font-bold text-indigo-500 uppercase tracking-wider mb-2">Pratinjau:</label>
                            <div id="add_images_preview_list" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                            </div>
                        </div>
                    </div>

                    <!-- Variasi & Stok -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1">
                            <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Variasi & Stok</h4>
                            <button type="button" onclick="addVariantRow()"
                                    class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold px-2.5 py-1 rounded-lg text-[10px] transition-all flex items-center gap-1">
                                <i class="fa-solid fa-plus text-[8px]"></i>
                                <span>Tambah Varian</span>
                            </button>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="min-w-full text-xs text-slate-650" id="variants-table">
                                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] border-b border-slate-200">
                                    <tr>
                                        <th class="py-2 px-2 sm:px-3 text-left">Ukuran</th>
                                        <th class="py-2 px-2 sm:px-3 text-left">Warna</th>
                                        <th class="py-2 px-2 sm:px-3 text-left w-20">Stok *</th>
                                        <th class="py-2 px-2 text-center w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100" id="variants-body">
                                    <tr class="variant-row">
                                        <td class="py-2 px-2 sm:px-3">
                                            <input type="text" name="variants[0][size]" placeholder="S" required
                                                   class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-2 py-1.5 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400">
                                        </td>
                                        <td class="py-2 px-2 sm:px-3">
                                            <input type="text" name="variants[0][color]" placeholder="Kuning"
                                                   class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-2 py-1.5 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400">
                                        </td>
                                        <td class="py-2 px-2 sm:px-3">
                                            <input type="number" name="variants[0][stock]" value="10" min="0" required
                                                   class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-2 py-1.5 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400">
                                        </td>
                                        <td class="py-2 px-2 text-center">
                                            <button type="button" onclick="removeVariantRow(this)"
                                                    class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-1 rounded-lg transition-all text-[10px]">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Modal Actions Footer outside scrollable container -->
                <div class="flex gap-3 pt-4 border-t border-slate-100 justify-end mt-4 shrink-0">
                    <button type="button" onclick="closeProductModal()" class="border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold py-2.5 px-6 rounded-xl transition-all text-xs">
                        Batal
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl transition-all shadow-sm text-xs">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
</div>

<!-- Edit Produk Modal (Scrollable Large Modal) -->
<div id="edit-product-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 pointer-events-none opacity-0 transition-opacity duration-300" aria-labelledby="edit-modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop Overlay -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" onclick="closeEditProductModal()"></div>

    <!-- Modal Panel -->
    <div id="edit-product-modal-panel" class="relative z-10 bg-white rounded-3xl text-left shadow-2xl w-full max-w-3xl max-h-[85vh] flex flex-col overflow-hidden p-6 sm:p-8 transform scale-95 transition-all duration-300 ease-out">
            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b border-slate-100 pb-4 mb-4 shrink-0">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square text-indigo-500"></i>
                    <span>Edit Produk</span>
                </h3>
                <button type="button" onclick="closeEditProductModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <form id="edit-product-form" action="" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 min-h-0">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect_url" value="{{ request()->fullUrl() }}">
                <!-- Modal Body (Scrollable form body wrapper) -->
                <div class="flex-1 overflow-y-auto px-2.5 pb-4 no-scrollbar space-y-6">

                    <!-- Informasi Dasar -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider border-b border-slate-100 pb-1">Informasi Dasar</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div class="md:col-span-2">
                                <label for="edit_name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Produk *</label>
                                <input type="text" name="name" id="edit_name" required value="" placeholder="Contoh: Kaos Anak Kancing Polos"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all">
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="edit_category_id_hidden" class="block text-xs font-semibold text-slate-700 mb-1.5">Kategori *</label>
                                <div class="relative custom-dropdown">
                                    <button type="button" id="edit_category_dropdown_btn" onclick="toggleCustomDropdown('edit-category-dropdown-menu')"
                                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl text-left focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all flex justify-between items-center cursor-pointer select-none">
                                        <span id="edit_selected-category-label">Pilih Kategori</span>
                                        <i class="fa-solid fa-chevron-down text-slate-400 text-[10px]"></i>
                                    </button>
                                    <input type="hidden" name="category_id" id="edit_category_id_hidden" value="" required>
                                    
                                    <div id="edit-category-dropdown-menu" class="hidden absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-30 max-h-60 overflow-y-auto no-scrollbar py-1 text-xs text-slate-700 animate-fade-in">
                                        <div class="px-3 py-2 hover:bg-slate-50 cursor-pointer font-semibold text-slate-400 border-b border-slate-100 transition-all text-left"
                                             onclick="selectCustomDropdownOption('', 'Pilih Kategori', 'edit_category_id_hidden', 'edit_selected-category-label', 'edit-category-dropdown-menu')">
                                            Pilih Kategori
                                        </div>
                                        @foreach($categories as $cat)
                                            <div class="px-3 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold flex items-center justify-between transition-all"
                                                 onclick="selectCustomDropdownOption('{{ $cat->id }}', '{{ $cat->name }}', 'edit_category_id_hidden', 'edit_selected-category-label', 'edit-category-dropdown-menu')">
                                                <span>{{ $cat->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- SKU -->
                            <div>
                                <label for="edit_sku" class="block text-xs font-semibold text-slate-700 mb-1.5">Kode SKU (Opsional)</label>
                                <input type="text" name="sku" id="edit_sku" value="" placeholder="Contoh: BJ-002"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all">
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="edit_price" class="block text-xs font-semibold text-slate-700 mb-1.5">Harga (Rp) *</label>
                                <input type="number" name="price" id="edit_price" required value="" min="0" placeholder="Contoh: 35000"
                                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="edit_status_hidden" class="block text-xs font-semibold text-slate-700 mb-1.5">Status Stok *</label>
                                <div class="relative custom-dropdown">
                                    <button type="button" id="edit_status_dropdown_btn" onclick="toggleCustomDropdown('edit-status-dropdown-menu')"
                                            class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl text-left focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all flex justify-between items-center cursor-pointer select-none">
                                        <span id="edit_selected-status-label">Ready</span>
                                        <i class="fa-solid fa-chevron-down text-slate-400 text-[10px]"></i>
                                    </button>
                                    <input type="hidden" name="status" id="edit_status_hidden" value="" required>
                                    
                                    <div id="edit-status-dropdown-menu" class="hidden absolute left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-30 py-1 text-xs text-slate-700 animate-fade-in">
                                        <div class="px-3 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                                             onclick="selectCustomDropdownOption('ready', 'Ready', 'edit_status_hidden', 'edit_selected-status-label', 'edit-status-dropdown-menu')">
                                            Ready
                                        </div>
                                        <div class="px-3 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                                             onclick="selectCustomDropdownOption('po', 'Pre-Order', 'edit_status_hidden', 'edit_selected-status-label', 'edit-status-dropdown-menu')">
                                            Pre-Order
                                        </div>
                                        <div class="px-3 py-2 hover:bg-indigo-50 hover:text-indigo-650 cursor-pointer font-semibold transition-all text-left"
                                             onclick="selectCustomDropdownOption('sold_out', 'Habis', 'edit_status_hidden', 'edit_selected-status-label', 'edit-status-dropdown-menu')">
                                            Habis
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Produk Populer -->
                            <div class="md:col-span-2 flex items-center bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-3 hover:bg-slate-100/50 transition-all">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" name="is_popular" id="edit_is_popular" value="1"
                                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-400 cursor-pointer">
                                </div>
                                <div class="ms-3 text-xs select-none">
                                    <label for="edit_is_popular" class="font-bold text-slate-700 cursor-pointer flex items-center gap-1.5">
                                        <i class="fa-solid fa-star text-amber-500 text-[10px]"></i>
                                        Produk Populer (Unggulan)
                                    </label>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Tampilkan produk ini di bagian "Koleksi Terpopuler" di homepage.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="edit_description" class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi Lengkap</label>
                            <textarea name="description" id="edit_description" rows="3" placeholder="Tuliskan detail bahan, kenyamanan, atau petunjuk ukuran..."
                                      class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-3 py-2 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:bg-white text-xs transition-all"></textarea>
                        </div>
                    </div>

                    <!-- 2. Galeri Foto Produk -->
                    <div class="space-y-4 pt-2">
                        <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider border-b border-slate-100 pb-1">Foto Produk</h4>
                        
                        <!-- Existing Images container -->
                        <div id="edit_existing_images_section" class="hidden">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Saat Ini (Centang untuk menghapus):</label>
                            <div id="edit_existing_images_list" class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                                <!-- Dynamic rendering of image items with checkboxes -->
                            </div>
                        </div>

                        <!-- Upload new -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tambah Foto Baru</label>
                            <input type="file" name="images[]" id="edit_product_images" multiple accept="image/*"
                                   onchange="handleFileSelect(this, true)"
                                   class="w-full border border-dashed border-slate-250 text-slate-500 bg-slate-50 hover:bg-slate-100 px-4 py-6 rounded-2xl cursor-pointer text-xs text-center">
                            <p class="text-[9px] text-slate-400 mt-1.5">Format: JPG, JPEG, PNG, WEBP, GIF, SVG, BMP, TIFF, HEIC, HEIF. Maksimal ukuran file: 10MB.</p>
                        </div>
                        
                        <!-- Pratinjau Foto Baru -->
                        <div id="edit_images_preview_section" class="hidden mt-3">
                            <label class="block text-[10px] font-bold text-indigo-500 uppercase tracking-wider mb-2">Pratinjau Foto Baru:</label>
                            <div id="edit_images_preview_list" class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                <!-- Dynamic Previews -->
                            </div>
                        </div>
                    </div>

                    <!-- Variasi & Stok -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-1">
                            <h4 class="font-bold text-slate-700 text-xs uppercase tracking-wider">Variasi & Stok</h4>
                            <button type="button" onclick="addEditVariantRow()"
                                    class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold px-2.5 py-1 rounded-lg text-[10px] transition-all flex items-center gap-1">
                                <i class="fa-solid fa-plus text-[8px]"></i>
                                <span>Tambah Varian</span>
                            </button>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-slate-200">
                            <table class="min-w-full text-xs text-slate-650" id="edit-variants-table">
                                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold text-[10px] border-b border-slate-200">
                                    <tr>
                                        <th class="py-2 px-2 sm:px-3 text-left">Ukuran</th>
                                        <th class="py-2 px-2 sm:px-3 text-left">Warna</th>
                                        <th class="py-2 px-2 sm:px-3 text-left w-20">Stok *</th>
                                        <th class="py-2 px-2 text-center w-10"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100" id="edit-variants-body">
                                    <!-- Dynamic rows -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Modal Actions Footer outside scrollable container -->
                <div class="flex gap-3 pt-4 border-t border-slate-100 justify-end mt-4 shrink-0">
                    <button type="button" onclick="closeEditProductModal()" class="border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold py-2.5 px-6 rounded-xl transition-all text-xs">
                        Batal
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl transition-all shadow-sm text-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
</div>
@endsection

@section('scripts')
<script>
    // CSV File Preview
    function previewCsvFile(event) {
        const input = event.target;
        const info = document.getElementById('csv-file-info');
        const nameLabel = document.getElementById('csv-file-name');
        if (input.files && input.files[0]) {
            nameLabel.textContent = input.files[0].name + ' (' + (input.files[0].size / 1024).toFixed(1) + ' KB)';
            info.classList.remove('hidden');
        } else {
            info.classList.add('hidden');
        }
    }

    const categoriesList = {!! json_encode($categories) !!};
    const statusLabels = {
        'ready': 'Ready',
        'po': 'Pre-Order',
        'sold_out': 'Habis'
    };

    // Toggle Custom Dropdown Visibility
    function toggleCustomDropdown(menuId) {
        // Close other custom dropdown menus first
        const allMenus = document.querySelectorAll('[id$="-dropdown-menu"]');
        allMenus.forEach(menu => {
            if (menu.id !== menuId) {
                menu.classList.add('hidden');
            }
        });
        
        const menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }

    // Select Custom Dropdown Option
    function selectCustomDropdownOption(value, label, hiddenInputId, labelId, menuId) {
        document.getElementById(hiddenInputId).value = value;
        document.getElementById(labelId).innerText = label;
        document.getElementById(menuId).classList.add('hidden');
    }

    // Close custom dropdowns if clicked outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.custom-dropdown')) {
            const allMenus = document.querySelectorAll('[id$="-dropdown-menu"]');
            allMenus.forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    const productModal = document.getElementById('product-modal');
    const editProductModal = document.getElementById('edit-product-modal');
    const editProductForm = document.getElementById('edit-product-form');
    let editVariantIndex = 0;

    // Open Add Modal with animation
    function openProductModal() {
        addProductFiles = [];
        productModal.classList.remove('pointer-events-none');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            productModal.classList.remove('opacity-0');
            productModal.classList.add('opacity-100');
            document.getElementById('product-modal-panel').classList.remove('scale-95');
            document.getElementById('product-modal-panel').classList.add('scale-100');
        });
    }

    // Close Add Modal with animation
    function closeProductModal() {
        productModal.classList.remove('opacity-100');
        productModal.classList.add('opacity-0');
        document.getElementById('product-modal-panel').classList.remove('scale-100');
        document.getElementById('product-modal-panel').classList.add('scale-95');
        setTimeout(() => {
            productModal.classList.add('pointer-events-none');
            document.body.classList.remove('overflow-hidden');
        }, 300);
        document.getElementById('add_product_images').value = '';
        document.getElementById('add_images_preview_section').classList.add('hidden');
        document.getElementById('add_images_preview_list').innerHTML = '';
        addProductFiles = [];
    }

    // Open Edit Modal
    function openEditProductModal(button) {
        editProductFiles = [];
        const product = JSON.parse(button.getAttribute('data-product'));
        const updateUrl = button.getAttribute('data-update-url');

        // Set form action
        editProductForm.action = updateUrl;

        // Set inputs
        document.getElementById('edit_name').value = product.name;
        
        // Populate Custom Category Dropdown in Edit Modal
        document.getElementById('edit_category_id_hidden').value = product.category_id;
        const selectedCat = categoriesList.find(c => c.id == product.category_id);
        document.getElementById('edit_selected-category-label').innerText = selectedCat ? selectedCat.name : 'Pilih Kategori';

        document.getElementById('edit_sku').value = product.sku || '';
        document.getElementById('edit_price').value = product.price;

        // Populate Custom Status Dropdown in Edit Modal
        document.getElementById('edit_status_hidden').value = product.status;
        document.getElementById('edit_selected-status-label').innerText = statusLabels[product.status] || 'Ready';

        document.getElementById('edit_is_popular').checked = !!product.is_popular;

        document.getElementById('edit_description').value = product.description || '';

        // Handle images
        const existingImagesSection = document.getElementById('edit_existing_images_section');
        const existingImagesList = document.getElementById('edit_existing_images_list');
        existingImagesList.innerHTML = '';

        if (product.images && product.images.length > 0) {
            existingImagesSection.classList.remove('hidden');
            product.images.forEach(img => {
                const imgDiv = document.createElement('div');
                imgDiv.className = 'relative bg-slate-50 border border-slate-100 rounded-2xl overflow-hidden aspect-square flex flex-col justify-between p-2';
                imgDiv.innerHTML = `
                    <div class="relative w-full h-16 rounded-xl overflow-hidden bg-slate-50 shrink-0">
                        <img src="/storage/${img.image_path}" alt="" class="w-full h-full object-cover rounded-xl" 
                             onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                        <div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400">
                            <i class="fa-regular fa-image text-lg"></i>
                        </div>
                    </div>
                    <label class="flex items-center justify-center gap-1 mt-2 cursor-pointer select-none">
                        <input type="checkbox" name="delete_images[]" value="${img.id}" class="rounded text-rose-500 focus:ring-rose-400 w-3 h-3 border-slate-350">
                        <span class="text-[9px] font-bold text-rose-600 uppercase">Hapus</span>
                    </label>
                `;
                existingImagesList.appendChild(imgDiv);
            });
        } else {
            existingImagesSection.classList.add('hidden');
        }

        // Handle variants
        const variantsBody = document.getElementById('edit-variants-body');
        variantsBody.innerHTML = '';
        editVariantIndex = 0;

        if (product.variants && product.variants.length > 0) {
            product.variants.forEach(variant => {
                addEditVariantRowWithValue(variant.size, variant.color, variant.stock);
            });
        } else {
            addEditVariantRowWithValue('', '', 10);
        }

        editProductModal.classList.remove('pointer-events-none');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            editProductModal.classList.remove('opacity-0');
            editProductModal.classList.add('opacity-100');
            document.getElementById('edit-product-modal-panel').classList.remove('scale-95');
            document.getElementById('edit-product-modal-panel').classList.add('scale-100');
        });
    }

    // Close Edit Modal with animation
    function closeEditProductModal() {
        editProductModal.classList.remove('opacity-100');
        editProductModal.classList.add('opacity-0');
        document.getElementById('edit-product-modal-panel').classList.remove('scale-100');
        document.getElementById('edit-product-modal-panel').classList.add('scale-95');
        setTimeout(() => {
            editProductModal.classList.add('pointer-events-none');
            document.body.classList.remove('overflow-hidden');
        }, 300);
        document.getElementById('edit_product_images').value = '';
        document.getElementById('edit_images_preview_section').classList.add('hidden');
        document.getElementById('edit_images_preview_list').innerHTML = '';
        editProductFiles = [];
    }

    function addEditVariantRowWithValue(size, color, stock) {
        const tbody = document.getElementById('edit-variants-body');
        const tr = document.createElement('tr');
        tr.className = 'edit-variant-row';
        tr.innerHTML = `
            <td class="py-2.5 px-3">
                <input type="text" name="variants[${editVariantIndex}][size]" value="${size || ''}" placeholder="S" required
                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-2.5 py-1 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400">
            </td>
            <td class="py-2.5 px-3">
                <input type="text" name="variants[${editVariantIndex}][color]" value="${color || ''}" placeholder="Kuning"
                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-2.5 py-1 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400">
            </td>
            <td class="py-2.5 px-3">
                <input type="number" name="variants[${editVariantIndex}][stock]" value="${stock !== undefined ? stock : 10}" min="0" required
                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-2.5 py-1 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400">
            </td>
            <td class="py-2.5 px-3 text-center">
                <button type="button" onclick="removeEditVariantRow(this)"
                        class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-1 rounded-lg transition-all text-[10px]">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        editVariantIndex++;
    }

    function addEditVariantRow() {
        addEditVariantRowWithValue('', '', 10);
    }

    function removeEditVariantRow(button) {
        const row = button.closest('tr');
        const rowsCount = document.querySelectorAll('.edit-variant-row').length;
        if (rowsCount > 1) {
            row.remove();
        } else {
            alert('Minimal harus menyisakan 1 baris variasi.');
        }
    }

    // Image Upload Queue Management
    let addProductFiles = [];
    let editProductFiles = [];

    function handleFileSelect(input, isEdit) {
        if (input.files && input.files.length > 0) {
            const queue = isEdit ? editProductFiles : addProductFiles;
            Array.from(input.files).forEach(file => {
                queue.push(file);
            });
            updateFileInputAndPreview(isEdit);
        }
    }

    function updateFileInputAndPreview(isEdit) {
        const queue = isEdit ? editProductFiles : addProductFiles;
        const inputId = isEdit ? 'edit_product_images' : 'add_product_images';
        const sectionId = isEdit ? 'edit_images_preview_section' : 'add_images_preview_section';
        const listId = isEdit ? 'edit_images_preview_list' : 'add_images_preview_list';

        const input = document.getElementById(inputId);
        const section = document.getElementById(sectionId);
        const list = document.getElementById(listId);

        list.innerHTML = '';

        // Synchronize our queue array to the actual input files using DataTransfer
        const dt = new DataTransfer();
        queue.forEach(file => dt.items.add(file));
        input.files = dt.files;

        if (queue.length > 0) {
            section.classList.remove('hidden');
            queue.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgDiv = document.createElement('div');
                    imgDiv.className = 'relative bg-slate-50 border border-slate-100 rounded-2xl overflow-hidden aspect-square flex flex-col justify-between p-2';
                    imgDiv.innerHTML = `
                        <div class="relative w-full h-16 rounded-xl overflow-hidden bg-slate-50 shrink-0">
                            <img src="${e.target.result}" alt="" class="w-full h-full object-cover rounded-xl" />
                        </div>
                        <button type="button" onclick="removeQueuedFile(${index}, ${isEdit})" class="flex items-center justify-center gap-1 mt-2 cursor-pointer select-none text-[9px] font-bold text-rose-600 uppercase hover:text-rose-800">
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Batal</span>
                        </button>
                    `;
                    list.appendChild(imgDiv);
                }
                reader.readAsDataURL(file);
            });
        } else {
            section.classList.add('hidden');
        }
    }

    function removeQueuedFile(index, isEdit) {
        const queue = isEdit ? editProductFiles : addProductFiles;
        queue.splice(index, 1);
        updateFileInputAndPreview(isEdit);
    }

    // Auto-open modal on page load if validation errors exist or add/edit parameter is present
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('edit_product_id') || request('edit'))
            @php
                $editId = session('edit_product_id') ?: request('edit');
                $editProduct = \App\Models\Product::with(['images', 'variants'])->find($editId);
            @endphp
            @if($editProduct)
                // Find edit button for this product if present in table
                const editBtn = document.querySelector('button[data-update-url*="/admin/products/{{ $editProduct->id }}"]');
                if (editBtn) {
                    openEditProductModal(editBtn);
                } else {
                    // Fallback if not in the current paginated view, manually reconstruct dataset
                    const fallbackBtn = document.createElement('button');
                    fallbackBtn.setAttribute('data-product', '{!! json_encode($editProduct) !!}');
                    fallbackBtn.setAttribute('data-update-url', '{{ route('admin.products.update', $editProduct->id) }}');
                    openEditProductModal(fallbackBtn);
                }

                @if(session('edit_product_id') && old())
                    // Override loaded fields with old inputs on validation failure
                    document.getElementById('edit_sku').value = @json(old('sku'));
                    document.getElementById('edit_price').value = @json(old('price'));
                    
                    const oldCatId = @json(old('category_id'));
                    document.getElementById('edit_category_id_hidden').value = oldCatId;
                    const oldCat = categoriesList.find(c => c.id == oldCatId);
                    document.getElementById('edit_selected-category-label').innerText = oldCat ? oldCat.name : 'Pilih Kategori';

                    const oldStatus = @json(old('status'));
                    document.getElementById('edit_status_hidden').value = oldStatus;
                    document.getElementById('edit_selected-status-label').innerText = statusLabels[oldStatus] || 'Ready';

                    document.getElementById('edit_is_popular').checked = {{ old('is_popular') ? 'true' : 'false' }};

                    document.getElementById('edit_description').value = @json(old('description'));

                    // Override variants
                    const oldVariants = @json(old('variants'));
                    if (oldVariants) {
                        const variantsBody = document.getElementById('edit-variants-body');
                        variantsBody.innerHTML = '';
                        editVariantIndex = 0;
                        Object.values(oldVariants).forEach(variant => {
                            addEditVariantRowWithValue(variant.size, variant.color, variant.stock);
                        });
                    }
                @endif
            @endif
        @elseif($errors->any() || request('add'))
            openProductModal();
        @endif
    });

    let variantIndex = 1;

    // Add a new row to variants table (Add modal)
    function addVariantRow() {
        const tbody = document.getElementById('variants-body');
        const tr = document.createElement('tr');
        tr.className = 'variant-row';
        tr.innerHTML = `
            <td class="py-2.5 px-3">
                <input type="text" name="variants[${variantIndex}][size]" placeholder="M" required
                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-2.5 py-1 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400">
            </td>
            <td class="py-2.5 px-3">
                <input type="text" name="variants[${variantIndex}][color]" placeholder="Biru"
                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-2.5 py-1 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400">
            </td>
            <td class="py-2.5 px-3">
                <input type="number" name="variants[${variantIndex}][stock]" value="10" min="0" required
                       class="w-full bg-slate-50 border border-slate-200 text-slate-700 px-2.5 py-1 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400">
            </td>
            <td class="py-2.5 px-3 text-center">
                <button type="button" onclick="removeVariantRow(this)"
                        class="text-rose-600 hover:text-rose-900 bg-rose-50 hover:bg-rose-100 p-1 rounded-lg transition-all text-[10px]">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        variantIndex++;
    }

    // Remove variant row (Add modal)
    function removeVariantRow(button) {
        const row = button.closest('tr');
        const rowsCount = document.querySelectorAll('.variant-row').length;
        if (rowsCount > 1) {
            row.remove();
        } else {
            alert('Minimal harus menyisakan 1 baris variasi.');
        }
    }

    // Bulk Actions
    let bulkSelectAll = false;

    function toggleAllProducts(el) {
        document.querySelectorAll('.product-checkbox').forEach(cb => { cb.checked = el.checked; });
        bulkSelectAll = false;
        updateBulkBar();
    }

    function updateBulkBar() {
        const checked = document.querySelectorAll('.product-checkbox:checked');
        const bar = document.getElementById('bulk-actions-bar');
        const count = document.getElementById('bulk-count');
        const selectAllMsg = document.getElementById('bulk-select-all-msg');
        const allSelectedMsg = document.getElementById('bulk-all-selected-msg');
        const totalProducts = {{ $products->total() }};
        const pageCount = document.querySelectorAll('.product-checkbox').length;

        if (checked.length > 0) {
            bar.classList.remove('hidden');
            count.textContent = bulkSelectAll ? totalProducts : checked.length;
            
            if (checked.length === pageCount && !bulkSelectAll && totalProducts > pageCount) {
                selectAllMsg.classList.remove('hidden');
            } else {
                selectAllMsg.classList.add('hidden');
            }

            if (bulkSelectAll) {
                allSelectedMsg.classList.remove('hidden');
                selectAllMsg.classList.add('hidden');
            } else {
                allSelectedMsg.classList.add('hidden');
            }
        } else {
            bar.classList.add('hidden');
            bulkSelectAll = false;
        }
    }

    function selectAllProducts() {
        bulkSelectAll = true;
        updateBulkBar();
    }

    function getSelectedIds() {
        return Array.from(document.querySelectorAll('.product-checkbox:checked')).map(cb => cb.value);
    }

    // Confirm Duplicate
    function confirmDuplicate(btn) {
        const form = btn.closest('form');
        showConfirmModal(
            'Duplikat Produk',
            'Buat salinan produk ini beserta variannya? (tanpa gambar)',
            'Ya, Duplikat',
            'bg-indigo-600 hover:bg-indigo-700 text-white',
            function() { form.submit(); }
        );
    }

    function toggleBulkStatusDropdown() {
        const menu = document.getElementById('bulk-status-menu');
        const chevron = document.getElementById('bulk-status-chevron');
        const isOpen = menu.classList.contains('scale-100');
        if (isOpen) {
            menu.classList.remove('scale-100','opacity-100','pointer-events-auto');
            menu.classList.add('scale-95','opacity-0','pointer-events-none');
            chevron.classList.remove('rotate-180');
        } else {
            menu.classList.remove('scale-95','opacity-0','pointer-events-none');
            menu.classList.add('scale-100','opacity-100','pointer-events-auto');
            chevron.classList.add('rotate-180');
        }
    }

    // Close dropdown on outside click
    document.addEventListener('click', (e) => {
        const wrapper = document.getElementById('bulk-status-wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const menu = document.getElementById('bulk-status-menu');
            const chevron = document.getElementById('bulk-status-chevron');
            if (menu) { menu.classList.remove('scale-100','opacity-100','pointer-events-auto'); menu.classList.add('scale-95','opacity-0','pointer-events-none'); }
            if (chevron) chevron.classList.remove('rotate-180');
        }
    });

    function applyBulkStatus(status) {
        const ids = getSelectedIds();
        if (ids.length === 0 && !bulkSelectAll) return;
        const label = bulkSelectAll ? '{{ $products->total() }}' : ids.length;
        const statusLabels = {ready:'Ready', po:'Pre-Order', sold_out:'Habis'};
        
        showConfirmModal(
            'Ubah Status Produk',
            `Ubah status ${label} produk menjadi "${statusLabels[status]}"?`,
            'Ya, Ubah Status',
            'bg-indigo-600 hover:bg-indigo-700 text-white',
            function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.products.bulkStatus") }}';
                let html = `@csrf<input type="hidden" name="status" value="${status}">`;
                if (bulkSelectAll) {
                    html += `<input type="hidden" name="select_all" value="1">`;
                } else {
                    ids.forEach(id => { html += `<input type="hidden" name="ids[]" value="${id}">`; });
                }
                form.innerHTML = html;
                document.body.appendChild(form);
                form.submit();
            }
        );

        // Close status dropdown
        const menu = document.getElementById('bulk-status-menu');
        const chevron = document.getElementById('bulk-status-chevron');
        menu.classList.remove('scale-100','opacity-100','pointer-events-auto');
        menu.classList.add('scale-95','opacity-0','pointer-events-none');
        chevron.classList.remove('rotate-180');
    }

    function bulkDelete() {
        const ids = getSelectedIds();
        if (ids.length === 0 && !bulkSelectAll) return;
        const label = bulkSelectAll ? '{{ $products->total() }}' : ids.length;
        
        showConfirmModal(
            'Hapus Produk',
            `Hapus ${label} produk yang dipilih? Aksi ini tidak bisa dibatalkan.`,
            'Ya, Hapus',
            'bg-rose-600 hover:bg-rose-700 text-white',
            function() {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("admin.products.bulkDelete") }}';
                let html = `@csrf`;
                if (bulkSelectAll) {
                    html += `<input type="hidden" name="select_all" value="1">`;
                } else {
                    ids.forEach(id => { html += `<input type="hidden" name="ids[]" value="${id}">`; });
                }
                form.innerHTML = html;
                document.body.appendChild(form);
                form.submit();
            }
        );
    }

    // Toggle Layout Mode (Table vs Grid)
    const productTableView = document.getElementById('product-table-view');
    const productGridView = document.getElementById('product-grid-view');
    const toggleBtnTable = document.getElementById('layout-toggle-table');
    const toggleBtnGrid = document.getElementById('layout-toggle-grid');

    function applyLayoutMode(mode) {
        if (mode === 'table') {
            productTableView.style.display = 'block';
            productGridView.style.display = 'none';
            
            // Update active states for toggles
            toggleBtnTable.classList.add('bg-white', 'text-indigo-600', 'shadow-xs');
            toggleBtnTable.classList.remove('text-slate-500');
            toggleBtnGrid.classList.remove('bg-white', 'text-indigo-600', 'shadow-xs');
            toggleBtnGrid.classList.add('text-slate-500');
        } else {
            productTableView.style.display = 'none';
            productGridView.style.display = 'grid';
            
            // Update active states for toggles
            toggleBtnGrid.classList.add('bg-white', 'text-indigo-600', 'shadow-xs');
            toggleBtnGrid.classList.remove('text-slate-500');
            toggleBtnTable.classList.remove('bg-white', 'text-indigo-600', 'shadow-xs');
            toggleBtnTable.classList.add('text-slate-500');
        }
    }

    window.toggleLayoutMode = function(mode) {
        localStorage.setItem('product-layout-mode', mode);
        applyLayoutMode(mode);
    };

    // Initialize layout mode
    const isMobile = window.innerWidth < 768;
    const savedLayout = localStorage.getItem('product-layout-mode');
    if (savedLayout) {
        applyLayoutMode(savedLayout);
    } else {
        // Fallback to responsive defaults (desktop = table, mobile = grid)
        applyLayoutMode(isMobile ? 'grid' : 'table');
    }

    // Product Detail Modal functions
    const productDetailModal = document.getElementById('product-detail-modal');

    window.showDetailMainFallback = function() {
        const mainImg = document.getElementById('detail-main-img');
        const fallbackImg = document.getElementById('detail-img-fallback');
        if (mainImg) mainImg.classList.add('hidden');
        if (fallbackImg) fallbackImg.classList.remove('hidden');
    };

    function handleProductRowClick(event, element) {
        const interactiveSelector = 'button, input, a, form, label, select, textarea, .custom-dropdown, [onclick]';
        const target = event.target;
        if (target.closest(interactiveSelector) && target.closest(interactiveSelector) !== element) {
            return;
        }
        const productData = element.getAttribute('data-product-detail');
        if (productData) {
            const product = JSON.parse(productData);
            openProductDetailModal(product);
        }
    }

    function openProductDetailModal(product) {
        // Set Name, Category, SKU, Price, Description
        document.getElementById('detail-name').innerText = product.name;
        document.getElementById('detail-category').innerText = product.category ? product.category.name : '';
        document.getElementById('detail-sku').innerText = product.sku || '-';
        
        const formatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        });
        document.getElementById('detail-price').innerText = formatter.format(product.price);
        document.getElementById('detail-description').innerText = product.description || 'Tidak ada deskripsi produk.';

        // Status Badge
        const statusBadge = document.getElementById('detail-status');
        statusBadge.innerText = statusLabels[product.status] || 'Ready';
        statusBadge.className = 'text-[10px] font-bold px-2.5 py-1 rounded-full border ';
        if (product.status === 'ready') {
            statusBadge.className += 'bg-emerald-50 text-emerald-700 border-emerald-100';
        } else if (product.status === 'po') {
            statusBadge.className += 'bg-amber-50 text-amber-700 border-amber-100';
        } else {
            statusBadge.className += 'bg-rose-50 text-rose-700 border-rose-100';
        }

        // Popular badge
        const popularBadge = document.getElementById('detail-popular-badge');
        if (product.is_popular) {
            popularBadge.classList.remove('hidden');
            popularBadge.classList.add('inline-flex');
        } else {
            popularBadge.classList.remove('inline-flex');
            popularBadge.classList.add('hidden');
        }

        // Image Gallery
        const mainImg = document.getElementById('detail-main-img');
        const fallbackImg = document.getElementById('detail-img-fallback');
        const thumbsContainer = document.getElementById('detail-thumbnails');
        thumbsContainer.innerHTML = '';

        if (product.images && product.images.length > 0) {
            mainImg.src = '/storage/' + product.images[0].image_path;
            mainImg.classList.remove('hidden');
            fallbackImg.classList.add('hidden');

            product.images.forEach((img, index) => {
                const thumb = document.createElement('button');
                thumb.type = 'button';
                thumb.className = `aspect-square rounded-xl border overflow-hidden bg-slate-50 relative cursor-pointer focus:outline-none transition-all ${index === 0 ? 'border-indigo-500 ring-2 ring-indigo-400/20' : 'border-slate-200 hover:border-slate-400'}`;
                thumb.innerHTML = `
                    <img src="/storage/${img.image_path}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.replace('hidden', 'flex');">
                    <div class="hidden absolute inset-0 items-center justify-center bg-slate-100 text-slate-400">
                        <i class="fa-regular fa-image text-xs"></i>
                    </div>
                `;
                thumb.onclick = () => {
                    // Update main image src
                    mainImg.src = '/storage/' + img.image_path;
                    mainImg.classList.remove('hidden');
                    fallbackImg.classList.add('hidden');
                    // Update active thumbnail borders
                    Array.from(thumbsContainer.children).forEach(child => {
                        child.classList.remove('border-indigo-500', 'ring-2', 'ring-indigo-400/20');
                        child.classList.add('border-slate-200');
                    });
                    thumb.classList.remove('border-slate-200');
                    thumb.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-400/20');
                };
                thumbsContainer.appendChild(thumb);
            });
        } else {
            mainImg.classList.add('hidden');
            fallbackImg.classList.remove('hidden');
        }

        // Variants Table
        const variantsBody = document.getElementById('detail-variants-body');
        variantsBody.innerHTML = '';
        if (product.variants && product.variants.length > 0) {
            product.variants.forEach(variant => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50/50 transition-colors';
                
                const stockDot = variant.stock > 0 
                    ? `<span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block mr-1.5"></span>` 
                    : `<span class="w-1.5 h-1.5 rounded-full bg-rose-500 inline-block mr-1.5"></span>`;
                
                tr.innerHTML = `
                    <td class="py-2.5 px-3 font-semibold text-slate-700">${variant.size || '-'}</td>
                    <td class="py-2.5 px-3 text-slate-650">${variant.color || '-'}</td>
                    <td class="py-2.5 px-3 font-bold text-slate-700 flex items-center">
                        ${stockDot}
                        <span>${variant.stock}</span>
                    </td>
                `;
                variantsBody.appendChild(tr);
            });
        } else {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td colspan="3" class="py-4 text-center text-slate-400 italic">Tidak ada varian</td>`;
            variantsBody.appendChild(tr);
        }

        // Set Edit Button click handler
        const editBtn = document.getElementById('detail-edit-btn');
        editBtn.onclick = () => {
            closeProductDetailModal();
            setTimeout(() => {
                const editButtons = Array.from(document.querySelectorAll('[onclick="openEditProductModal(this)"]'));
                const matchBtn = editButtons.find(btn => {
                    try {
                        const p = JSON.parse(btn.getAttribute('data-product'));
                        return p.id === product.id;
                    } catch(e) {
                        return false;
                    }
                });
                if (matchBtn) {
                    openEditProductModal(matchBtn);
                }
            }, 350);
        };

        // Open modal animations
        productDetailModal.classList.remove('pointer-events-none');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            productDetailModal.classList.remove('opacity-0');
            productDetailModal.classList.add('opacity-100');
            document.getElementById('product-detail-modal-panel').classList.remove('scale-95');
            document.getElementById('product-detail-modal-panel').classList.add('scale-100');
        });
    }

    function closeProductDetailModal() {
        productDetailModal.classList.remove('opacity-100');
        productDetailModal.classList.add('opacity-0');
        document.getElementById('product-detail-modal-panel').classList.remove('scale-100');
        document.getElementById('product-detail-modal-panel').classList.add('scale-95');
        setTimeout(() => {
            productDetailModal.classList.add('pointer-events-none');
            document.body.classList.remove('overflow-hidden');
        }, 300);
    }
</script>
@endsection
