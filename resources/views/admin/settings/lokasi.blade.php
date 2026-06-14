@extends('layouts.admin')

@section('title', 'Lokasi Toko')
@section('page_title', 'Lokasi Toko')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Lokasi Toko</h2>
    <p class="text-sm text-slate-500 mt-1">Atur teks dan informasi lokasi toko offline yang ditampilkan di halaman beranda.</p>
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
                <div>
                    <label for="store_address" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Alamat Lengkap Toko</label>
                    <textarea name="store_address" id="store_address" rows="2" required
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all resize-none">{{ old('store_address', $storeInfo['address']) }}</textarea>
                </div>
                <div>
                    <label for="store_hours" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Jam Operasional</label>
                    <input type="text" name="store_hours" id="store_hours" value="{{ old('store_hours', $storeInfo['hours']) }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all">
                </div>
                <div>
                    <label for="store_phone" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">No. WhatsApp/Telepon</label>
                    <input type="text" name="store_phone" id="store_phone" value="{{ old('store_phone', $storeInfo['phone']) }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all">
                </div>
                <div>
                    <label for="store_map_iframe" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Google Maps Embed URL</label>
                    <input type="text" name="store_map_iframe" id="store_map_iframe" value="{{ old('store_map_iframe', $storeInfo['map_iframe']) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                           placeholder="https://www.google.com/maps/embed?pb=...">
                    <span class="block text-[10px] text-slate-400 mt-1">Tempelkan URL 'src' dari embed iframe Google Maps.</span>
                </div>
                <div>
                    <label for="store_map_link" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Link Petunjuk Arah</label>
                    <input type="text" name="store_map_link" id="store_map_link" value="{{ old('store_map_link', $storeInfo['map_link']) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                           placeholder="https://maps.google.com/?q=...">
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
</div>

<!-- Live Preview Section -->
<div class="mt-10 mb-6">
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center">
                <i class="fa-solid fa-eye text-primary-600"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Pratinjau Halaman Utama (Real-time)</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Tampilan nyata dari bagian lokasi toko offline di beranda toko saat ini.</p>
            </div>
        </div>

        <!-- Real-time dynamic preview block matching the homepage layout -->
        <div class="border border-slate-100 rounded-2xl overflow-hidden p-1 sm:p-3 bg-slate-50 select-none">
            <section class="bg-gradient-to-br from-slate-50 via-primary-50/10 to-slate-50 py-12 px-4 rounded-2xl border border-slate-100/50">
                <div class="max-w-6xl mx-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
                        
                        <!-- Left Side: Google Maps Location -->
                        <div class="relative group bg-white rounded-3xl p-3 shadow-md border border-slate-100 transition-all duration-500 flex flex-col h-[320px]">
                            <div class="grow rounded-2xl overflow-hidden relative">
                                <iframe 
                                    id="preview-map-iframe"
                                    src="{{ $storeInfo['map_iframe'] ?: '' }}" 
                                    class="w-full h-full rounded-2xl border-0 {{ $storeInfo['map_iframe'] ? '' : 'hidden' }}" 
                                    title="Peta Lokasi Toko Berkah Mulia"
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                                <div id="preview-map-placeholder" class="w-full h-full rounded-2xl bg-slate-50 flex flex-col items-center justify-center text-slate-400 {{ $storeInfo['map_iframe'] ? 'hidden' : 'flex' }}">
                                    <i class="fa-solid fa-map-marked-alt text-4xl mb-2 text-slate-300"></i>
                                    <span class="text-xs font-semibold">Peta Lokasi Belum Ditentukan</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Location Details -->
                        <div class="space-y-5 select-none text-left">
                            <div>
                                <span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-widest font-extrabold text-primary-600 bg-primary-50 border border-primary-100/50 px-3 py-1 rounded-full mb-3">
                                    <i class="fa-solid fa-store text-[9px] text-primary-500"></i>
                                    <span id="preview-location-badge">{{ $locationText['badge'] }}</span>
                                </span>

                                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight font-sans" id="preview-location-title">
                                    {{ $locationText['title'] }}
                                </h2>
                                <p class="mt-2 text-slate-500 text-xs sm:text-sm leading-relaxed" id="preview-location-description">
                                    {{ str_replace('datang langsung to toko', 'datang langsung ke toko', $locationText['description']) }}
                                </p>
                            </div>

                            <div class="space-y-3">
                                <!-- Address Card -->
                                <div class="flex gap-3 items-start bg-white p-3.5 rounded-2xl border border-slate-100 shadow-xs hover:border-primary-200 hover:shadow-md transition-all duration-300 group/tile">
                                    <div class="w-9 h-9 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center shrink-0 text-sm group-hover/tile:scale-105 group-hover/tile:bg-primary-500 group-hover/tile:text-white transition-all duration-300">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <h3 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Alamat Lengkap</h3>
                                        <p class="text-xs text-slate-700 font-semibold leading-relaxed" id="preview-store-address">
                                            {{ $storeInfo['address'] }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Hours Card -->
                                <div class="flex gap-3 items-start bg-white p-3.5 rounded-2xl border border-slate-100 shadow-xs hover:border-primary-200 hover:shadow-md transition-all duration-300 group/tile">
                                    <div class="w-9 h-9 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center shrink-0 text-sm group-hover/tile:scale-105 group-hover/tile:bg-primary-500 group-hover/tile:text-white transition-all duration-300">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <h3 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Jam Operasional</h3>
                                        <p class="text-xs text-slate-700 font-semibold leading-relaxed" id="preview-store-hours">
                                            {{ $storeInfo['hours'] }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Contact Card -->
                                <div class="flex gap-3 items-start bg-white p-3.5 rounded-2xl border border-slate-100 shadow-xs hover:border-primary-200 hover:shadow-md transition-all duration-300 group/tile">
                                    <div class="w-9 h-9 rounded-xl bg-primary-50 text-primary-500 flex items-center justify-center shrink-0 text-sm group-hover/tile:scale-105 group-hover/tile:bg-primary-500 group-hover/tile:text-white transition-all duration-300">
                                        <i class="fa-brands fa-whatsapp text-base"></i>
                                    </div>
                                    <div class="space-y-0.5">
                                        <h3 class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Hubungi Kami</h3>
                                        <p class="text-xs text-slate-700 font-semibold leading-relaxed" id="preview-store-phone">
                                            WhatsApp: <span class="font-mono" id="preview-phone-span">+{{ $storeInfo['phone'] }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-1">
                                <a id="preview-store-map-link" href="{{ $storeInfo['map_link'] ?: '#' }}" target="_blank"
                                   class="inline-flex items-center gap-2 bg-primary-500 hover:bg-primary-600 active:scale-95 text-white font-bold px-5 py-3 rounded-xl shadow-md shadow-primary-500/10 hover:shadow-lg hover:shadow-primary-500/20 transition-all text-xs cursor-pointer group">
                                    <i class="fa-solid fa-map-location-dot text-sm group-hover:rotate-6 group-hover:scale-110 transition-transform"></i>
                                    <span>Petunjuk Arah Google Maps</span>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    document.addEventListener('DOMContentLoaded', function() {
        const inputBadge = document.getElementById('location_badge');
        const inputTitle = document.getElementById('location_title');
        const inputDesc = document.getElementById('location_description');
        const inputAddress = document.getElementById('store_address');
        const inputHours = document.getElementById('store_hours');
        const inputPhone = document.getElementById('store_phone');
        const inputIframe = document.getElementById('store_map_iframe');
        const inputLink = document.getElementById('store_map_link');

        const previewBadge = document.getElementById('preview-location-badge');
        const previewTitle = document.getElementById('preview-location-title');
        const previewDesc = document.getElementById('preview-location-description');
        const previewAddress = document.getElementById('preview-store-address');
        const previewHours = document.getElementById('preview-store-hours');
        const previewPhoneSpan = document.getElementById('preview-phone-span');
        const previewIframe = document.getElementById('preview-map-iframe');
        const previewLink = document.getElementById('preview-store-map-link');
        const mapPlaceholder = document.getElementById('preview-map-placeholder');

        function updatePreview() {
            if (inputBadge && previewBadge) previewBadge.textContent = inputBadge.value || 'Kunjungi Kami';
            if (inputTitle && previewTitle) previewTitle.textContent = inputTitle.value || 'Lokasi Toko Offline';
            
            if (inputDesc && previewDesc) {
                let desc = inputDesc.value || '';
                // Automatically fix "datang langsung to toko" typo in the preview
                desc = desc.replace(/datang langsung to toko/gi, 'datang langsung ke toko');
                previewDesc.textContent = desc || 'Silakan datang langsung ke toko fisik kami...';
            }

            if (inputAddress && previewAddress) previewAddress.textContent = inputAddress.value || 'Alamat toko belum ditentukan.';
            if (inputHours && previewHours) previewHours.textContent = inputHours.value || 'Jam operasional belum ditentukan.';
            if (inputPhone && previewPhoneSpan) previewPhoneSpan.textContent = `+${inputPhone.value || '628123456789'}`;
            
            if (inputLink && previewLink) {
                previewLink.href = inputLink.value || '#';
            }

            if (inputIframe && previewIframe && mapPlaceholder) {
                if (inputIframe.value) {
                    previewIframe.src = inputIframe.value;
                    previewIframe.classList.remove('hidden');
                    mapPlaceholder.classList.add('hidden');
                } else {
                    previewIframe.classList.add('hidden');
                    mapPlaceholder.classList.remove('hidden');
                    mapPlaceholder.classList.replace('hidden', 'flex');
                }
            }
        }

        // Add input event listeners for real-time updates
        const inputs = [inputBadge, inputTitle, inputDesc, inputAddress, inputHours, inputPhone, inputIframe, inputLink];
        inputs.forEach(input => {
            if (input) {
                input.addEventListener('input', updatePreview);
            }
        });

        // Initialize state
        updatePreview();
    });
</script>
@endsection
