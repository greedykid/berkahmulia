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
                        <p class="text-[11px] text-slate-500 mt-0.5">Ubah kontak global yang tersimpan di environment system (.env).</p>
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
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-2">1. Tombol WhatsApp Navbar</span>
                <div class="flex justify-center py-4 bg-white rounded-xl border border-slate-100 shadow-xs">
                    <a id="preview-whatsapp-nav" href="#" target="_blank"
                       class="flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-full text-xs font-semibold border border-emerald-200 pointer-events-none">
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

        const previewWhatsappNav = document.getElementById('preview-whatsapp-nav');
        const previewWhatsappText = document.getElementById('preview-whatsapp-text');
        const previewEmailText = document.getElementById('preview-email-text');

        function updateLivePreview() {
            let whatsapp = inputWhatsapp.value || '';
            let email = inputEmail.value || '';

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
            if (whatsapp) {
                const fullWhatsapp = '62' + whatsapp;
                if (previewWhatsappNav) {
                    previewWhatsappNav.href = `https://wa.me/${fullWhatsapp}`;
                }
                if (previewWhatsappText) {
                    previewWhatsappText.textContent = `+${fullWhatsapp} (Sales Admin)`;
                }
            } else {
                if (previewWhatsappNav) {
                    previewWhatsappNav.href = '#';
                }
                if (previewWhatsappText) {
                    previewWhatsappText.textContent = '+628123456789 (Sales Admin)';
                }
            }
        }

        // Add input listeners
        if (inputWhatsapp) inputWhatsapp.addEventListener('input', updateLivePreview);
        if (inputEmail) inputEmail.addEventListener('input', updateLivePreview);

        // Run once initially
        updateLivePreview();
    });
</script>
@endsection
