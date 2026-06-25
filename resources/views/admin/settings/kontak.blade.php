@extends('layouts.admin')

@section('title', 'Kontak Toko')
@section('page_title', 'Kontak Toko')

@section('content')

<!-- Page Header -->
<div class="mb-6">
    <h2 class="text-xl font-bold text-slate-800">Kontak Toko</h2>
    <p class="text-sm text-slate-500 mt-1">Atur nomor WhatsApp dan email administrator global yang digunakan di seluruh bagian aplikasi.</p>
</div>

@if ($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-start gap-2 shadow-sm animate-fade-in">
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
    
    <!-- Left: Settings Form -->
    <form action="{{ route('admin.settings.updateKontak') }}" method="POST" id="contact-settings-form">
        @csrf
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm h-full flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <i class="fa-solid fa-address-book text-indigo-600"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Form Pengaturan Kontak</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Atur kontak toko, nomor WhatsApp, email, dan link media sosial yang ditampilkan di website.</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <!-- WhatsApp Field -->
                    <div>
                        <label for="whatsapp_number" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-semibold font-mono">+62</span>
                            <input type="text" name="whatsapp_number" id="whatsapp_number" 
                                   value="{{ old('whatsapp_number', preg_replace('/^62/', '', $contact['whatsapp_number'])) }}" required
                                   class="w-full border border-slate-200 rounded-xl pl-14 pr-4 py-2.5 text-sm text-slate-700 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                                   placeholder="Contoh: 82112619691">
                        </div>
                        <span class="block text-[10px] text-slate-400 mt-1.5 leading-relaxed">
                            *Cukup masukkan nomor setelah kode negara +62 (diawali dengan <strong>8...</strong>). Input akan dinormalisasi secara otomatis.
                        </span>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="admin_email" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Email Admin / Toko</label>
                        <input type="email" name="admin_email" id="admin_email" 
                               value="{{ old('admin_email', $contact['admin_email']) }}" required
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                               placeholder="Contoh: admin@bmberkahmulia.com">
                        <span class="block text-[10px] text-slate-400 mt-1.5">
                            *Alamat email resmi untuk notifikasi dan tautan kontak pelanggan.
                        </span>
                    </div>

                    <!-- WhatsApp Message Template Field -->
                    <div>
                        <label for="whatsapp_message_template" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Pesan Template WhatsApp</label>
                        <textarea name="whatsapp_message_template" id="whatsapp_message_template" rows="2"
                                  class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                                  placeholder="Contoh: Halo Admin Berkah Mulia, saya ingin bertanya mengenai produk...">{{ old('whatsapp_message_template', $contact['whatsapp_message_template']) }}</textarea>
                        <span class="block text-[10px] text-slate-400 mt-1.5 leading-relaxed">
                            *Pesan otomatis yang akan langsung terisi saat pelanggan mengklik tombol "Hubungi Admin" di WhatsApp.
                        </span>
                    </div>

                    <!-- Toggle Show WhatsApp in Navbar -->
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 hover:bg-slate-100/50 transition-all">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="show_whatsapp_nav" id="show_whatsapp_nav" value="1"
                                   {{ old('show_whatsapp_nav', $contact['show_whatsapp_nav'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-650 focus:ring-indigo-400 cursor-pointer">
                        </div>
                        <div class="ms-3 text-xs select-none">
                            <label for="show_whatsapp_nav" class="font-bold text-slate-700 cursor-pointer flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp text-emerald-500 text-[10px]"></i>
                                Tampilkan Hubungi Admin di Navbar
                            </label>
                            <p class="text-[10px] text-slate-500 mt-0.5">Aktifkan untuk menampilkan tombol WhatsApp "Hubungi Admin" di bagian atas navigasi (navbar) utama.</p>
                        </div>
                    </div>

                    <!-- Toggle Show WhatsApp Floating Button -->
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 hover:bg-slate-100/50 transition-all">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="show_whatsapp_floating" id="show_whatsapp_floating" value="1"
                                   {{ old('show_whatsapp_floating', $contact['show_whatsapp_floating'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-650 focus:ring-indigo-400 cursor-pointer">
                        </div>
                        <div class="ms-3 text-xs select-none">
                            <label for="show_whatsapp_floating" class="font-bold text-slate-700 cursor-pointer flex items-center gap-1.5">
                                <i class="fa-brands fa-whatsapp text-emerald-500 text-[10px]"></i>
                                Tampilkan Tombol Melayang WhatsApp
                            </label>
                            <p class="text-[10px] text-slate-500 mt-0.5">Aktifkan untuk menampilkan tombol ikon WhatsApp melayang di sudut kanan bawah website.</p>
                        </div>
                    </div>

                    <!-- Instagram Link Field -->
                    <div>
                        <label for="instagram_url" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Link Instagram</label>
                        <input type="url" name="instagram_url" id="instagram_url" 
                               value="{{ old('instagram_url', $contact['instagram_url']) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                               placeholder="Contoh: https://instagram.com/username">
                        <span class="block text-[10px] text-slate-400 mt-1.5">
                            *Masukkan link lengkap ke profil Instagram toko Anda (kosongkan untuk menyembunyikan).
                        </span>
                    </div>

                    <!-- Toggle Show Instagram in Navbar -->
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 hover:bg-slate-100/50 transition-all">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="show_instagram_nav" id="show_instagram_nav" value="1"
                                   {{ old('show_instagram_nav', $contact['show_instagram_nav'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-650 focus:ring-indigo-400 cursor-pointer">
                        </div>
                        <div class="ms-3 text-xs select-none">
                            <label for="show_instagram_nav" class="font-bold text-slate-700 cursor-pointer flex items-center gap-1.5">
                                <i class="fa-brands fa-instagram text-pink-500 text-[10px]"></i>
                                Tampilkan Instagram di Navbar
                            </label>
                            <p class="text-[10px] text-slate-500 mt-0.5">Aktifkan untuk menampilkan ikon Instagram di bagian atas navigasi (navbar) utama.</p>
                        </div>
                    </div>

                    <!-- TikTok Link Field -->
                    <div>
                        <label for="tiktok_url" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Link TikTok</label>
                        <input type="url" name="tiktok_url" id="tiktok_url" 
                               value="{{ old('tiktok_url', $contact['tiktok_url'] ?? '') }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                               placeholder="Contoh: https://tiktok.com/@username">
                        <span class="block text-[10px] text-slate-400 mt-1.5">
                            *Masukkan link lengkap ke profil TikTok toko Anda (kosongkan untuk menyembunyikan).
                        </span>
                    </div>

                    <!-- TikTok Name Field -->
                    <div>
                        <label for="tiktok_name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Akun TikTok (Opsional)</label>
                        <input type="text" name="tiktok_name" id="tiktok_name" 
                               value="{{ old('tiktok_name', $contact['tiktok_name'] ?? '') }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                               placeholder="Contoh: Berkah Mulia">
                        <span class="block text-[10px] text-slate-400 mt-1.5">
                            *Nama tampilan akun di footer (kosongkan untuk otomatis menampilkan username dari link).
                        </span>
                    </div>

                    <!-- Toggle Show TikTok in Navbar -->
                    <div class="flex items-center bg-slate-50 border border-slate-200 rounded-2xl px-4 py-3 hover:bg-slate-100/50 transition-all">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="show_tiktok_nav" id="show_tiktok_nav" value="1"
                                   {{ old('show_tiktok_nav', $contact['show_tiktok_nav'] ?? true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-slate-300 text-indigo-650 focus:ring-indigo-400 cursor-pointer">
                        </div>
                        <div class="ms-3 text-xs select-none">
                            <label for="show_tiktok_nav" class="font-bold text-slate-700 cursor-pointer flex items-center gap-1.5">
                                <i class="fa-brands fa-tiktok text-slate-800 text-[10px]"></i>
                                Tampilkan TikTok di Navbar
                            </label>
                            <p class="text-[10px] text-slate-500 mt-0.5">Aktifkan untuk menampilkan ikon TikTok di bagian atas navigasi (navbar) utama.</p>
                        </div>
                    </div>

                    <!-- Shopee Link Field -->
                    <div>
                        <label for="shopee_url" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Link Shopee</label>
                        <input type="url" name="shopee_url" id="shopee_url" 
                               value="{{ old('shopee_url', $contact['shopee_url']) }}"
                               class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                               placeholder="Contoh: https://shopee.co.id/username">
                        <span class="block text-[10px] text-slate-400 mt-1.5">
                            *Masukkan link lengkap ke toko Shopee Anda (kosongkan untuk menyembunyikan).
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl text-xs transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Kontak Toko</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Right: Real-time Live Preview -->
    <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fa-solid fa-eye text-emerald-600"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Pratinjau Penggunaan Global</h4>
                <p class="text-[11px] text-slate-500 mt-0.5">Tampilan langsung data kontak di bagian beranda dan footer.</p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Header WhatsApp Button Preview -->
            <div class="border border-slate-100 rounded-2xl p-4 bg-slate-50">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-2">1. Tombol Kontak Navbar</span>
                <div class="flex items-center justify-center gap-2 py-4 bg-white rounded-xl border border-slate-100 shadow-xs">
                    <a id="preview-instagram-nav" href="#" target="_blank"
                       class="hidden flex items-center justify-center w-9 h-9 bg-pink-50 rounded-full border border-pink-200 pointer-events-none">
                        <i class="fa-brands fa-instagram text-base" style="color: #e1306c;"></i>
                    </a>
                    <a id="preview-tiktok-nav" href="#" target="_blank"
                       class="hidden flex items-center justify-center w-9 h-9 bg-slate-50 text-slate-800 rounded-full border border-slate-200 pointer-events-none">
                        <i class="fa-brands fa-tiktok text-base text-black"></i>
                    </a>
                    <a id="preview-shopee-nav" href="#" target="_blank"
                       class="hidden flex items-center justify-center w-9 h-9 bg-orange-50 text-orange-700 rounded-full border border-orange-200 pointer-events-none">
                        <svg viewBox="0 0 109.59 122.88" class="w-4.5 h-4.5 fill-[#EE4D2D]">
                            <path d="M74.98,91.98C76.15,82.36,69.96,76.22,53.6,71c-7.92-2.7-11.66-6.24-11.57-11.12 c0.33-5.4,5.36-9.34,12.04-9.47c4.63,0.09,9.77,1.22,14.76,4.56c0.59,0.37,1.01,0.32,1.35-0.2c0.46-0.74,1.61-2.53,2-3.17 c0.26-0.42,0.31-0.96-0.35-1.44c-0.95-0.7-3.6-2.13-5.03-2.72c-3.88-1.62-8.23-2.64-12.86-2.63c-9.77,0.04-17.47,6.22-18.12,14.47 c-0.42,5.95,2.53,10.79,8.86,14.47c1.34,0.78,8.6,3.67,11.49,4.57c9.08,2.83,13.8,7.9,12.69,13.81c-1.01,5.36-6.65,8.83-14.43,8.93 c-6.17-0.24-11.71-2.75-16.02-6.1c-0.11-0.08-0.65-0.5-0.72-0.56c-0.53-0.42-1.11-0.39-1.47,0.15c-0.26,0.4-1.92,2.8-2.34,3.43 c-0.39,0.55-0.18,0.86,0.23,1.2c1.8,1.5,4.18,3.14,5.81,3.97c4.47,2.28,9.32,3.53,14.48,3.72c3.32,0.22,7.5-0.49,10.63-1.81 C70.63,102.67,74.25,97.92,74.98,91.98L74.98,91.98z M54.79,7.18c-10.59,0-19.22,9.98-19.62,22.47h39.25 C74.01,17.16,65.38,7.18,54.79,7.18L54.79,7.18z M94.99,122.88l-0.41,0l-80.82-0.01h0c-5.5-0.21-9.54-4.66-10.09-10.19l-0.05-1 l-3.61-79.5v0C0,32.12,0,32.06,0,32c0-1.28,1.03-2.33,2.3-2.35l0,0h25.48C28.41,13.15,40.26,0,54.79,0s26.39,13.15,27.01,29.65 h25.4h0.04c1.3,0,2.35,1.05,2.35,2.35c0,0.04,0,0.08,0,0.12v0l-3.96,79.81l-0.04,0.68C105.12,118.21,100.59,122.73,94.99,122.88 L94.99,122.88z"/>
                        </svg>
                    </a>
                    <a id="preview-whatsapp-nav" href="#" target="_blank"
                       class="flex items-center justify-center gap-1.5 bg-emerald-50 text-emerald-700 px-4 h-9 rounded-full text-xs font-semibold border border-emerald-200 pointer-events-none">
                        <i class="fa-brands fa-whatsapp text-lg text-emerald-500"></i>
                        <span>Hubungi Admin</span>
                    </a>
                </div>
            </div>

            <!-- Footer Contact Preview -->
            <div class="border border-slate-100 rounded-2xl p-4 bg-slate-50">
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-2">2. Informasi Kontak di Footer</span>
                <div class="p-4 bg-slate-900 text-slate-300 rounded-xl border border-slate-800 text-left select-none">
                    <h3 class="text-white font-semibold text-xs uppercase tracking-wider mb-3">Hubungi Kami</h3>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center gap-2">
                            <i class="fa-solid fa-envelope text-primary-400"></i>
                            <span id="preview-email-text" class="hover:text-primary-400 transition-colors">info@bmberkahmulia.com</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-brands fa-whatsapp text-emerald-400 text-sm"></i>
                            <span id="preview-whatsapp-text" class="hover:text-primary-400 transition-colors">
                                +628123456789 (Sales Admin)
                            </span>
                        </li>
                        <li id="preview-instagram-footer-item" class="hidden flex items-center gap-2">
                            <i class="fa-brands fa-instagram text-sm" style="color: #e1306c;"></i>
                            <span id="preview-instagram-footer-text" class="hover:text-primary-400 transition-colors">
                                Instagram
                            </span>
                        </li>
                        <li id="preview-tiktok-footer-item" class="hidden flex items-center gap-2">
                            <i class="fa-brands fa-tiktok text-sm text-white"></i>
                            <span id="preview-tiktok-footer-text" class="hover:text-primary-400 transition-colors">
                                TikTok
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputWhatsapp = document.getElementById('whatsapp_number');
        const inputEmail = document.getElementById('admin_email');
        const inputTemplate = document.getElementById('whatsapp_message_template');
        const inputInstagram = document.getElementById('instagram_url');
        const inputTiktok = document.getElementById('tiktok_url');
        const inputTiktokName = document.getElementById('tiktok_name');
        const inputShopee = document.getElementById('shopee_url');

        const previewWhatsappNav = document.getElementById('preview-whatsapp-nav');
        const previewInstagramNav = document.getElementById('preview-instagram-nav');
        const previewShopeeNav = document.getElementById('preview-shopee-nav');
        const previewWhatsappText = document.getElementById('preview-whatsapp-text');
        const previewEmailText = document.getElementById('preview-email-text');
        const previewInstagramFooterItem = document.getElementById('preview-instagram-footer-item');
        const previewInstagramFooterText = document.getElementById('preview-instagram-footer-text');
        const previewTiktokFooterItem = document.getElementById('preview-tiktok-footer-item');
        const previewTiktokFooterText = document.getElementById('preview-tiktok-footer-text');
        const previewTiktokNav = document.getElementById('preview-tiktok-nav');
        const inputShowInstagramNav = document.getElementById('show_instagram_nav');
        const inputShowTiktokNav = document.getElementById('show_tiktok_nav');
        const inputShowWhatsappNav = document.getElementById('show_whatsapp_nav');
        const inputShowWhatsappFloating = document.getElementById('show_whatsapp_floating');

        function updateLivePreview() {
            let whatsapp = inputWhatsapp.value || '';
            let email = inputEmail.value || '';
            let template = inputTemplate.value || '';
            let instagram = inputInstagram.value || '';
            let shopee = inputShopee.value || '';

            // Clean input formatting for direct display
            whatsapp = whatsapp.replace(/[^0-9]/g, '');
            
            // Normalize: If the user types '08...', remove '0'. If '628...', remove '62'.
            if (whatsapp.startsWith('628')) {
                whatsapp = whatsapp.substring(2);
            } else if (whatsapp.startsWith('08')) {
                whatsapp = whatsapp.substring(1);
            }

            // Sync email
            if (previewEmailText) {
                previewEmailText.textContent = email || 'info@bmberkahmulia.com';
            }

            // Sync whatsapp link & text
            const showWhatsappNavVal = inputShowWhatsappNav ? inputShowWhatsappNav.checked : true;
            if (whatsapp && showWhatsappNavVal) {
                const fullWhatsapp = '62' + whatsapp;
                let waHref = `https://wa.me/${fullWhatsapp}`;
                if (template) {
                    waHref += `?text=${encodeURIComponent(template)}`;
                }
                if (previewWhatsappNav) {
                    previewWhatsappNav.classList.remove('hidden');
                    previewWhatsappNav.href = waHref;
                }
                if (previewWhatsappText) {
                    previewWhatsappText.textContent = `+${fullWhatsapp} (Sales Admin)`;
                }
            } else {
                if (previewWhatsappNav) {
                    previewWhatsappNav.classList.add('hidden');
                }
                if (previewWhatsappText) {
                    if (whatsapp) {
                        previewWhatsappText.textContent = `+62${whatsapp} (Sales Admin)`;
                    } else {
                        previewWhatsappText.textContent = '+628123456789 (Sales Admin)';
                    }
                }
            }

            // Sync Instagram Preview
            const showInstagramNavVal = inputShowInstagramNav ? inputShowInstagramNav.checked : true;
            if (instagram && showInstagramNavVal) {
                if (previewInstagramNav) {
                    previewInstagramNav.classList.remove('hidden');
                    previewInstagramNav.href = instagram;
                }
            } else {
                if (previewInstagramNav) {
                    previewInstagramNav.classList.add('hidden');
                }
            }

            if (instagram) {
                if (previewInstagramFooterItem) {
                    previewInstagramFooterItem.classList.remove('hidden');
                    let instagramUsername = 'Instagram';
                    try {
                        const parsedUrl = new URL(instagram);
                        let path = parsedUrl.pathname.replace(/^\/|\/$/g, '');
                        let segments = path.split('/');
                        let firstSegment = segments[0] || '';
                        if (firstSegment && !['explore', 'p', 'reels', 'stories'].includes(firstSegment.toLowerCase())) {
                            instagramUsername = '@' + firstSegment;
                        }
                    } catch (e) {
                        if (instagram.includes('instagram.com/')) {
                            let parts = instagram.split('instagram.com/');
                            if (parts[1]) {
                                  let path = parts[1].replace(/^\/|\/$/g, '');
                                  let segments = path.split('/');
                                  let firstSegment = segments[0] || '';
                                  if (firstSegment && !['explore', 'p', 'reels', 'stories'].includes(firstSegment.toLowerCase())) {
                                      instagramUsername = '@' + firstSegment;
                                  }
                            }
                        }
                    }
                    if (previewInstagramFooterText) {
                        previewInstagramFooterText.textContent = instagramUsername;
                    }
                }
            } else {
                if (previewInstagramFooterItem) {
                    previewInstagramFooterItem.classList.add('hidden');
                }
            }

            // Sync TikTok Preview
            let tiktok = inputTiktok ? inputTiktok.value : '';
            let tiktokName = inputTiktokName ? inputTiktokName.value : '';
            if (tiktok) {
                if (previewTiktokFooterItem) {
                    previewTiktokFooterItem.classList.remove('hidden');
                    let tiktokUsername = tiktokName;
                    if (!tiktokUsername) {
                        tiktokUsername = 'TikTok';
                        try {
                            const parsedUrl = new URL(tiktok);
                            let path = parsedUrl.pathname.replace(/^\/|\/$/g, '');
                            let segments = path.split('/');
                            let firstSegment = segments[0] || '';
                            if (firstSegment && !['explore', 'p', 'reels', 'stories', 'share', 't'].includes(firstSegment.toLowerCase())) {
                                if (firstSegment.startsWith('@')) {
                                    tiktokUsername = firstSegment;
                                } else {
                                    tiktokUsername = '@' + firstSegment;
                                }
                            }
                        } catch (e) {
                            if (tiktok.includes('tiktok.com/')) {
                                let parts = tiktok.split('tiktok.com/');
                                if (parts[1]) {
                                      let path = parts[1].replace(/^\/|\/$/g, '');
                                      let segments = path.split('/');
                                      let firstSegment = segments[0] || '';
                                      if (firstSegment && !['explore', 'p', 'reels', 'stories', 'share', 't'].includes(firstSegment.toLowerCase())) {
                                          if (firstSegment.startsWith('@')) {
                                              tiktokUsername = firstSegment;
                                          } else {
                                              tiktokUsername = '@' + firstSegment;
                                          }
                                      }
                                }
                            }
                        }
                    }
                    if (previewTiktokFooterText) {
                        previewTiktokFooterText.textContent = tiktokUsername;
                    }
                }
            } else {
                if (previewTiktokFooterItem) {
                    previewTiktokFooterItem.classList.add('hidden');
                }
            }

            // Sync TikTok Nav Preview
            const showTiktokNavVal = inputShowTiktokNav ? inputShowTiktokNav.checked : true;
            if (tiktok && showTiktokNavVal) {
                if (previewTiktokNav) {
                    previewTiktokNav.classList.remove('hidden');
                    previewTiktokNav.href = tiktok;
                }
            } else {
                if (previewTiktokNav) {
                    previewTiktokNav.classList.add('hidden');
                }
            }

            // Sync Shopee Preview
            if (shopee) {
                if (previewShopeeNav) {
                    previewShopeeNav.classList.remove('hidden');
                    previewShopeeNav.href = shopee;
                }
            } else {
                if (previewShopeeNav) {
                    previewShopeeNav.classList.add('hidden');
                }
            }
        }

        // Add input listeners
        if (inputWhatsapp) inputWhatsapp.addEventListener('input', updateLivePreview);
        if (inputEmail) inputEmail.addEventListener('input', updateLivePreview);
        if (inputTemplate) inputTemplate.addEventListener('input', updateLivePreview);
        if (inputInstagram) inputInstagram.addEventListener('input', updateLivePreview);
        if (inputTiktok) inputTiktok.addEventListener('input', updateLivePreview);
        if (inputTiktokName) inputTiktokName.addEventListener('input', updateLivePreview);
        if (inputShopee) inputShopee.addEventListener('input', updateLivePreview);
        if (inputShowInstagramNav) inputShowInstagramNav.addEventListener('change', updateLivePreview);
        if (inputShowTiktokNav) inputShowTiktokNav.addEventListener('change', updateLivePreview);
        if (inputShowWhatsappNav) inputShowWhatsappNav.addEventListener('change', updateLivePreview);
        if (inputShowWhatsappFloating) inputShowWhatsappFloating.addEventListener('change', updateLivePreview);

        // Run once initially
        updateLivePreview();
    });
</script>
@endsection
