@extends('layouts.admin')

@section('title', 'Pengaturan Toko')
@section('page_title', 'Pengaturan Toko')

@section('content')

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

<!-- Hero Banner + Hero Text (Side by Side) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
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

            <!-- Banner Aktif -->
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

            <!-- Upload -->
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

<!-- Location Text + Store Info (Side by Side) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Left: Location Text -->
    <form action="{{ route('admin.settings.updateLocationText') }}" method="POST" id="location-text-form">
        @csrf
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm h-full">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-secondary-50 flex items-center justify-center">
                    <i class="fa-solid fa-map-pin text-secondary-600"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Teks Lokasi Toko</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Ubah teks di bagian "Lokasi Toko Offline" beranda.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="location_badge" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Badge / Label Kecil</label>
                    <input type="text" name="location_badge" id="location_badge" value="{{ $locationText['badge'] }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-secondary-400 focus:border-secondary-400 transition-all"
                           placeholder="Contoh: Kunjungi Kami">
                </div>

                <div>
                    <label for="location_title" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Judul</label>
                    <input type="text" name="location_title" id="location_title" value="{{ $locationText['title'] }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-secondary-400 focus:border-secondary-400 transition-all"
                           placeholder="Contoh: Lokasi Toko Offline">
                </div>

                <div>
                    <label for="location_description" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Deskripsi</label>
                    <textarea name="location_description" id="location_description" rows="4"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-secondary-400 focus:border-secondary-400 transition-all resize-none"
                              placeholder="Contoh: Silakan datang langsung ke toko fisik kami...">{{ $locationText['description'] }}</textarea>
                </div>
            </div>

            <div class="mt-5 flex justify-end">
                <button type="submit" class="bg-secondary-600 hover:bg-secondary-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Teks Lokasi</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Right: Store Info -->
    <form action="{{ route('admin.settings.updateStoreInfo') }}" method="POST" enctype="multipart/form-data" id="store-info-form">
        @csrf
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm h-full">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <i class="fa-solid fa-store text-indigo-600"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Informasi Toko Offline</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Alamat, kontak, dan Google Maps toko.</p>
            </div>
        </div>

        <div class="space-y-4">
            <!-- Address -->
            <div>
                <label for="store_address" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Alamat Lengkap Toko</label>
                <textarea name="store_address" id="store_address" rows="2" required
                          class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all resize-none">{{ old('store_address', $storeInfo['address']) }}</textarea>
            </div>

            <!-- Hours -->
            <div>
                <label for="store_hours" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Jam Operasional</label>
                <input type="text" name="store_hours" id="store_hours" value="{{ old('store_hours', $storeInfo['hours']) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all">
            </div>

            <!-- Phone -->
            <div>
                <label for="store_phone" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">No. WhatsApp/Telepon</label>
                <input type="text" name="store_phone" id="store_phone" value="{{ old('store_phone', $storeInfo['phone']) }}" required
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all">
            </div>

            <!-- Maps Iframe Embed URL -->
            <div>
                <label for="store_map_iframe" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Google Maps Embed URL</label>
                <input type="text" name="store_map_iframe" id="store_map_iframe" value="{{ old('store_map_iframe', $storeInfo['map_iframe']) }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                       placeholder="https://www.google.com/maps/embed?pb=...">
                <span class="block text-[10px] text-slate-400 mt-1">Tempelkan URL 'src' dari embed iframe Google Maps.</span>
            </div>

            <!-- Maps Button Link -->
            <div>
                <label for="store_map_link" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Link Petunjuk Arah (Maps URL)</label>
                <input type="text" name="store_map_link" id="store_map_link" value="{{ old('store_map_link', $storeInfo['map_link']) }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                       placeholder="https://maps.google.com/?q=...">
            </div>

            <!-- Store Photo -->
            <div>
                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Foto Toko</label>
                <div class="space-y-3">
                    <!-- Current Photo -->
                    @if($storeInfo['image'])
                        <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50 p-2">
                            <div class="aspect-[16/9] w-full rounded-lg overflow-hidden bg-slate-200">
                                <img src="{{ asset('storage/' . $storeInfo['image']) }}" alt="Foto Toko" class="w-full h-full object-cover">
                            </div>
                            <div class="p-2 flex items-center gap-2">
                                <input type="checkbox" name="delete_store_image" value="1" id="delete_store_image"
                                       class="w-4 h-4 text-rose-600 border-slate-300 rounded focus:ring-rose-500 cursor-pointer">
                                <label for="delete_store_image" class="text-xs text-rose-600 font-semibold cursor-pointer select-none">Hapus foto ini</label>
                            </div>
                        </div>
                    @endif
                    <!-- Upload New -->
                    <div class="border border-slate-200 rounded-xl p-3 bg-slate-50 hover:bg-slate-100 transition-all relative group cursor-pointer">
                        <input type="file" name="store_image" accept="image/*" id="store-image-input"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewStoreImage(event)">
                        <div class="flex flex-col items-center justify-center py-2 text-center">
                            <div class="w-9 h-9 rounded-full bg-indigo-50 flex items-center justify-center text-sm mb-2 group-hover:scale-110 transition-transform duration-200">
                                <i class="fa-solid fa-camera text-indigo-600"></i>
                            </div>
                            <span class="text-xs font-semibold text-slate-600">Pilih foto toko</span>
                            <span class="text-[10px] text-slate-400 mt-0.5">JPEG, PNG, JPG, WEBP (Max. 10MB)</span>
                        </div>
                    </div>
                    <!-- Preview -->
                    <div id="store-image-preview-container" class="hidden border border-slate-200 rounded-xl p-2 bg-slate-50">
                        <div class="aspect-[16/9] w-full rounded-lg overflow-hidden bg-slate-200">
                            <img id="store-image-preview" src="#" alt="Pratinjau" class="w-full h-full object-cover">
                        </div>
                        <div class="flex justify-between items-center text-[10px] mt-1.5 px-1">
                            <span id="store-image-filename" class="text-slate-600 font-medium truncate max-w-[120px]"></span>
                            <span class="text-emerald-600 font-bold"><i class="fa-solid fa-circle-check"></i> Siap</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 flex justify-end">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Simpan Informasi Toko</span>
            </button>
        </div>
    </div>
</form>
</div> <!-- end grid Location + Store Info -->

<script>
    function previewFiles(event) {
        const files = event.target.files;
        const container = document.getElementById('file-previews');
        const list = document.getElementById('preview-list');
        
        list.innerHTML = '';
        
        if (files.length > 0) {
            container.classList.remove('hidden');
            Array.from(files).forEach((file, index) => {
                const item = document.createElement('div');
                item.className = 'border border-slate-200 rounded-xl overflow-hidden bg-slate-50';
                
                // Format file size
                const sizeKB = (file.size / 1024).toFixed(0);
                const sizeText = sizeKB > 1024 ? (sizeKB / 1024).toFixed(1) + ' MB' : sizeKB + ' KB';

                // Create image preview
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
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
                
                list.appendChild(item);
            });
        } else {
            container.classList.add('hidden');
        }
    }

    function previewStoreImage(event) {
        const input = event.target;
        const container = document.getElementById('store-image-preview-container');
        const img = document.getElementById('store-image-preview');
        const filenameLabel = document.getElementById('store-image-filename');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                filenameLabel.textContent = input.files[0].name;
                container.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            container.classList.add('hidden');
        }
    }

    // Attach submit validation warning for deletions
    const settingsForm = document.getElementById('settings-form');
    if (settingsForm) {
        settingsForm.addEventListener('submit', function(event) {
            const deleteCheckboxes = document.querySelectorAll('.delete-banner-checkbox:checked');
            if (deleteCheckboxes.length > 0) {
                const confirmMessage = `Apakah Anda yakin ingin menghapus ${deleteCheckboxes.length} banner terpilih?`;
                if (!confirm(confirmMessage)) {
                    event.preventDefault();
                }
            }
        });
    }
</script>
@endsection
