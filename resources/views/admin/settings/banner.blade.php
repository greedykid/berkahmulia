@extends('layouts.admin')

@section('title', 'Kelola Banner')
@section('page_title', 'Kelola Banner')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Kelola Banner</h2>
    <p class="text-sm text-slate-500 mt-1">Atur gambar hero banner slider dan teks yang ditampilkan di halaman beranda.</p>
</div>

@if ($errors->any())
    <div class="mb-8 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-start gap-2 shadow-sm animate-fade-in">
        <i class="fa-solid fa-triangle-exclamation text-rose-500 mt-0.5"></i>
        <div>
            <p class="text-xs font-bold mb-1">Terjadi kesalahan pada input data:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Left: Hero Banner Settings -->
    <form action="{{ route('admin.settings.updateHeroBanners') }}" method="POST" enctype="multipart/form-data" id="settings-form">
        @csrf
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm h-full">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                    <i class="fa-solid fa-images text-indigo-600"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Kelola Hero Banner Slider</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Atur gambar banner carousel di halaman beranda.</p>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-3">Banner Aktif</label>
                @if(count($banners) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($banners as $index => $path)
                            <div class="relative group border border-slate-200 rounded-xl overflow-hidden bg-slate-50 p-1.5 hover:shadow-md transition-all duration-200">
                                <div class="aspect-21/9 w-full rounded-lg overflow-hidden bg-slate-200 relative">
                                    <img src="{{ asset('storage/' . $path) }}" alt="Banner {{ $index + 1 }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                        <span class="text-white text-[10px] font-semibold px-2 py-0.5 bg-slate-900/60 rounded-full">Banner {{ $index + 1 }}</span>
                                    </div>
                                </div>
                                <div class="p-2 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="delete_banners[]" value="{{ $path }}" id="delete_banner_{{ $index }}"
                                               class="w-4 h-4 text-rose-600 border-slate-300 rounded focus:ring-rose-500 cursor-pointer">
                                        <label for="delete_banner_{{ $index }}" class="text-[11px] text-slate-600 font-medium cursor-pointer select-none">Hapus</label>
                                    </div>
                                    <span class="text-[9px] text-slate-400 font-mono">{{ substr(basename($path), 0, 8) }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center p-6 border border-dashed border-slate-300 rounded-xl bg-slate-50/50">
                        <i class="fa-solid fa-images text-slate-300 text-xl mb-2"></i>
                        <p class="text-[11px] text-slate-400 text-center">Belum ada banner kustom. Menggunakan default.</p>
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Unggah Banner Baru</label>
                <div class="border border-slate-200 rounded-xl p-3 bg-slate-50 hover:bg-slate-100 transition-all relative group cursor-pointer">
                    <input type="file" name="banners[]" multiple accept="image/*" id="banner-input"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewFiles(event)">
                    <div class="flex flex-col items-center justify-center py-3 text-center">
                        <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform duration-200">
                            <i class="fa-solid fa-cloud-arrow-up text-indigo-600"></i>
                        </div>
                        <span class="text-xs font-semibold text-slate-600">Klik untuk pilih gambar</span>
                        <span class="text-[10px] text-slate-400 mt-0.5">JPEG, PNG, JPG, WEBP (Max. 10MB) — Rasio 21:9</span>
                    </div>
                </div>
                <div id="file-previews" class="space-y-3 max-h-[300px] overflow-y-auto thin-scrollbar hidden mt-3">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pratinjau</span>
                    <div id="preview-list" class="space-y-3"></div>
                </div>
            </div>

            <div class="mt-5 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Banner</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Right: Hero Text Settings -->
    <form action="{{ route('admin.settings.updateHeroText') }}" method="POST" id="hero-text-form">
        @csrf
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm h-full">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center">
                    <i class="fa-solid fa-heading text-primary-600"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Teks Hero Banner</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Ubah teks judul, badge, dan deskripsi di hero beranda.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="hero_badge" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Badge / Label Kecil</label>
                    <input type="text" name="hero_badge" id="hero_badge" value="{{ $heroText['badge'] }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-all"
                           placeholder="Contoh: Koleksi Baru 2026">
                </div>
                <div>
                    <label for="hero_title_line1" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Judul Baris 1</label>
                    <input type="text" name="hero_title_line1" id="hero_title_line1" value="{{ $heroText['title_line1'] }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-all"
                           placeholder="Contoh: Pakaian Lembut & Nyaman">
                </div>
                <div>
                    <label for="hero_title_line2" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Judul Baris 2 (Warna Aksen)</label>
                    <input type="text" name="hero_title_line2" id="hero_title_line2" value="{{ $heroText['title_line2'] }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-all"
                           placeholder="Contoh: Untuk Buah Hati Anda">
                </div>
                <div>
                    <label for="hero_description" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Deskripsi</label>
                    <textarea name="hero_description" id="hero_description" rows="4"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-primary-400 transition-all resize-none"
                              placeholder="Contoh: Dapatkan koleksi pakaian bayi...">{{ $heroText['description'] }}</textarea>
                </div>
            </div>

            <div class="mt-5 flex justify-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Teks Hero</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewFiles(event) {
        const files = event.target.files;
        const container = document.getElementById('file-previews');
        const list = document.getElementById('preview-list');
        list.innerHTML = '';
        if (files.length > 0) {
            container.classList.remove('hidden');
            Array.from(files).forEach((file) => {
                const item = document.createElement('div');
                item.className = 'border border-slate-200 rounded-xl overflow-hidden bg-slate-50';
                const sizeKB = (file.size / 1024).toFixed(0);
                const sizeText = sizeKB > 1024 ? (sizeKB / 1024).toFixed(1) + ' MB' : sizeKB + ' KB';
                const reader = new FileReader();
                reader.onload = function(e) {
                    item.innerHTML = `
                        <div class="aspect-21/9 w-full bg-slate-200 overflow-hidden">
                            <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex items-center justify-between p-2.5">
                            <div class="flex items-center gap-2 truncate flex-1 pr-2">
                                <i class="fa-solid fa-image text-slate-400 text-xs shrink-0"></i>
                                <div class="truncate flex flex-col">
                                    <span class="font-medium text-slate-700 truncate text-[11px]">${file.name}</span>
                                    <span class="text-[9px] text-slate-400">${sizeText}</span>
                                </div>
                            </div>
                            <i class="fa-solid fa-circle-check text-emerald-500 text-sm shrink-0"></i>
                        </div>`;
                };
                reader.readAsDataURL(file);
                list.appendChild(item);
            });
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endsection
