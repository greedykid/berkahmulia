@extends('layouts.admin')
@section('title', 'Panduan Admin')
@section('page_title', 'Panduan Admin')

@section('content')
<div class="max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                <i class="fa-solid fa-book-open text-indigo-600 text-lg"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Panduan Penggunaan Admin Panel</h2>
                <p class="text-sm text-slate-500">Penjelasan lengkap semua menu yang ada di panel admin ini</p>
            </div>
        </div>
    </div>

    {{-- Table of Contents --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 mb-6 shadow-xs">
        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">
            <i class="fa-solid fa-list-ul mr-2 text-indigo-500"></i>Daftar Isi
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <a href="#section-dashboard" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa-solid fa-chart-line text-indigo-500 w-4 text-center text-xs"></i>
                <span>Dashboard</span>
            </a>
            <a href="#section-kategori" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa-solid fa-tags text-indigo-500 w-4 text-center text-xs"></i>
                <span>Kelola Kategori</span>
            </a>
            <a href="#section-produk" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa-solid fa-boxes-stacked text-indigo-500 w-4 text-center text-xs"></i>
                <span>Kelola Produk</span>
            </a>
            <a href="#section-banner" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa-solid fa-images text-indigo-500 w-4 text-center text-xs"></i>
                <span>Kelola Banner</span>
            </a>
            <a href="#section-lokasi" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa-solid fa-map-pin text-indigo-500 w-4 text-center text-xs"></i>
                <span>Lokasi Toko</span>
            </a>
            <a href="#section-kontak" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa-brands fa-whatsapp text-indigo-500 w-4 text-center text-xs"></i>
                <span>Kontak Toko</span>
            </a>
            <a href="#section-panduan-ukuran" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa-solid fa-ruler text-indigo-500 w-4 text-center text-xs"></i>
                <span>Panduan Ukuran</span>
            </a>
            <a href="#section-password" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition-all">
                <i class="fa-solid fa-lock text-indigo-500 w-4 text-center text-xs"></i>
                <span>Ubah Password</span>
            </a>
        </div>
    </div>

    {{-- Guide Sections --}}
    <div class="space-y-5">

        {{-- 1. Dashboard --}}
        <div id="section-dashboard" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs scroll-mt-4">
            <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-5 py-3.5 flex items-center gap-3">
                <i class="fa-solid fa-chart-line text-white text-lg"></i>
                <h3 class="text-white font-bold text-sm">Dashboard</h3>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-slate-600 leading-relaxed">
                    <strong>Dashboard</strong> adalah halaman utama yang pertama kali muncul saat Anda masuk ke admin panel. 
                    Di sini Anda bisa melihat ringkasan toko secara keseluruhan dalam satu pandangan.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Yang bisa Anda lihat di sini:</p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Total Produk</strong> — Jumlah semua produk yang sudah ditambahkan ke toko.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Total Kategori</strong> — Jumlah kategori/jenis produk yang tersedia.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Peringatan Stok</strong> — Daftar produk yang stoknya hampir habis atau sudah habis, agar Anda bisa segera restok.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Produk Terbaru</strong> — Daftar produk yang baru saja ditambahkan ke toko.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 2. Kelola Kategori --}}
        <div id="section-kategori" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs scroll-mt-4">
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-3.5 flex items-center gap-3">
                <i class="fa-solid fa-tags text-white text-lg"></i>
                <h3 class="text-white font-bold text-sm">Kelola Kategori</h3>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-slate-600 leading-relaxed">
                    <strong>Kategori</strong> adalah grup atau jenis produk. Misalnya: "Baju Bayi", "Setelan Anak", "Celana Dalam", dsb.
                    Setiap produk yang Anda tambahkan harus masuk ke salah satu kategori.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Yang bisa Anda lakukan:</p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-plus text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Tambah Kategori Baru</strong> — Klik tombol "Tambah Kategori", isi nama dan gambar, lalu simpan.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-pen text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Edit Kategori</strong> — Klik ikon pensil untuk mengubah nama atau gambar kategori.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-trash text-rose-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Hapus Kategori</strong> — Klik ikon tempat sampah untuk menghapus kategori. <em>Pastikan tidak ada produk di dalamnya.</em></span>
                        </li>
                    </ul>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
                    <i class="fa-solid fa-lightbulb text-amber-500 mt-0.5 shrink-0"></i>
                    <p class="text-xs text-amber-800 leading-relaxed">
                        <strong>Tips:</strong> Buat nama kategori yang jelas dan mudah dipahami oleh pembeli. Contoh: "Baju Bayi 0-12 Bulan" lebih baik daripada hanya "Baju".
                    </p>
                </div>
            </div>
        </div>

        {{-- 3. Kelola Produk --}}
        <div id="section-produk" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs scroll-mt-4">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-5 py-3.5 flex items-center gap-3">
                <i class="fa-solid fa-boxes-stacked text-white text-lg"></i>
                <h3 class="text-white font-bold text-sm">Kelola Produk</h3>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-slate-600 leading-relaxed">
                    Ini adalah halaman utama untuk mengelola semua produk toko Anda. Di sini Anda bisa menambah, mengubah, menghapus, dan mengatur semua produk.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Fitur-fitur yang tersedia:</p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-plus text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Tambah Produk</strong> — Isi nama, kategori, harga, deskripsi, dan upload foto produk.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-layer-group text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Variasi Produk (Ukuran, Warna, Stok, Harga)</strong> — Setiap produk bisa punya beberapa variasi. Contoh: ukuran S warna Merah stok 10, ukuran M warna Biru stok 5, dsb. Masing-masing variasi bisa punya harga dan foto sendiri.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-pen text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Edit Produk</strong> — Klik ikon pensil atau nama produk untuk mengubah detail produk.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-copy text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Duplikat Produk</strong> — Salin produk yang sudah ada sebagai template untuk produk baru yang mirip.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-star text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Tandai Produk Populer</strong> — Produk yang ditandai populer akan tampil di bagian "Koleksi Terpopuler" di halaman depan toko.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-toggle-on text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Status Produk</strong> — Atur status ke "Ready" (siap jual), "Pre-Order" (pesan dulu), atau "Habis" (stok kosong).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-file-export text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Export & Import</strong> — Unduh data produk ke file CSV/PDF, atau upload file CSV untuk menambah produk secara massal.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-trash text-rose-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Hapus Produk</strong> — Hapus produk satu per satu atau sekaligus banyak (centang lalu pilih aksi massal).</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3">
                    <i class="fa-solid fa-lightbulb text-emerald-500 mt-0.5 shrink-0"></i>
                    <p class="text-xs text-emerald-800 leading-relaxed">
                        <strong>Tips:</strong> Gunakan foto produk yang jelas dan terang. Produk dengan foto yang bagus akan lebih menarik pembeli. Anda bisa upload hingga 10 foto per produk.
                    </p>
                </div>
            </div>
        </div>

        {{-- 4. Pengaturan Toko - Banner --}}
        <div id="section-banner" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs scroll-mt-4">
            <div class="bg-gradient-to-r from-purple-500 to-violet-500 px-5 py-3.5 flex items-center gap-3">
                <i class="fa-solid fa-images text-white text-lg"></i>
                <h3 class="text-white font-bold text-sm">Pengaturan Toko — Kelola Banner</h3>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-slate-600 leading-relaxed">
                    <strong>Banner</strong> adalah gambar besar yang tampil di bagian paling atas halaman depan toko (slider/carousel). 
                    Ini adalah hal pertama yang dilihat pengunjung saat membuka website Anda.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Yang bisa Anda atur:</p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-image text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Gambar Banner</strong> — Upload gambar promosi, diskon, atau koleksi terbaru. Gambar akan berganti otomatis secara bergantian.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-heading text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Teks Hero</strong> — Ubah judul utama dan deskripsi yang muncul di atas banner.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-bullhorn text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Teks Pengumuman</strong> — Teks berjalan di bagian atas website. Cocok untuk info promo, pengumuman, atau ucapan selamat datang.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 5. Lokasi Toko --}}
        <div id="section-lokasi" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs scroll-mt-4">
            <div class="bg-gradient-to-r from-sky-500 to-cyan-500 px-5 py-3.5 flex items-center gap-3">
                <i class="fa-solid fa-map-pin text-white text-lg"></i>
                <h3 class="text-white font-bold text-sm">Pengaturan Toko — Lokasi Toko</h3>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-slate-600 leading-relaxed">
                    Halaman ini untuk mengatur informasi lokasi toko fisik Anda yang akan ditampilkan di website.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Yang bisa Anda atur:</p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-location-dot text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Alamat Lengkap</strong> — Tulis alamat toko secara lengkap agar pembeli bisa menemukan lokasi Anda.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-clock text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Jam Operasional</strong> — Atur jam buka dan tutup toko.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-map text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Peta Google Maps</strong> — Tempelkan link embed Google Maps agar pengunjung bisa melihat peta lokasi toko.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-pen-to-square text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Teks Judul & Deskripsi Lokasi</strong> — Ubah judul dan deskripsi yang muncul di bagian lokasi di halaman depan.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 6. Kontak Toko --}}
        <div id="section-kontak" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs scroll-mt-4">
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-5 py-3.5 flex items-center gap-3">
                <i class="fa-brands fa-whatsapp text-white text-lg"></i>
                <h3 class="text-white font-bold text-sm">Pengaturan Toko — Kontak Toko</h3>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-slate-600 leading-relaxed">
                    Halaman ini untuk mengatur nomor kontak WhatsApp toko. Nomor ini akan digunakan di tombol-tombol "Hubungi via WhatsApp" dan "Pesan via WA" di seluruh website.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Yang bisa Anda atur:</p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-phone text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Nomor WhatsApp</strong> — Masukkan nomor WA aktif toko. Format: 628xxxxxxxxxx (tanpa + atau spasi). Contoh: 6281234567890.</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
                    <i class="fa-solid fa-lightbulb text-green-500 mt-0.5 shrink-0"></i>
                    <p class="text-xs text-green-800 leading-relaxed">
                        <strong>Tips:</strong> Pastikan nomor WA yang dimasukkan aktif dan bisa menerima pesan. Saat pembeli klik tombol WhatsApp, mereka akan langsung terhubung ke nomor ini.
                    </p>
                </div>
            </div>
        </div>

        {{-- 7. Panduan Ukuran --}}
        <div id="section-panduan-ukuran" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs scroll-mt-4">
            <div class="bg-gradient-to-r from-rose-500 to-pink-500 px-5 py-3.5 flex items-center gap-3">
                <i class="fa-solid fa-ruler text-white text-lg"></i>
                <h3 class="text-white font-bold text-sm">Pengaturan Toko — Panduan Ukuran</h3>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-slate-600 leading-relaxed">
                    <strong>Panduan Ukuran</strong> adalah tabel ukuran yang ditampilkan di halaman detail setiap produk. 
                    Tabel ini membantu pembeli memilih ukuran yang tepat berdasarkan usia, berat, atau tinggi badan anak.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Yang bisa Anda lakukan:</p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-table text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Atur Tabel Ukuran</strong> — Tambah atau hapus kolom dan baris pada tabel. Contoh kolom: "Ukuran", "Usia", "Berat Badan", "Tinggi".</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-plus text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Tambah Kolom</strong> — Klik "Tambah Kolom" untuk menambah jenis pengukuran baru.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-plus text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Tambah Baris</strong> — Klik "Tambah Baris" untuk menambah ukuran baru.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-note-sticky text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                            <span><strong>Catatan Ukuran</strong> — Tulis catatan tambahan di bawah tabel, misalnya: "Ukuran di atas adalah estimasi rata-rata standar".</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- 8. Ubah Password --}}
        <div id="section-password" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs scroll-mt-4">
            <div class="bg-gradient-to-r from-slate-600 to-slate-700 px-5 py-3.5 flex items-center gap-3">
                <i class="fa-solid fa-lock text-white text-lg"></i>
                <h3 class="text-white font-bold text-sm">Pengaturan Toko — Ubah Password</h3>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-slate-600 leading-relaxed">
                    Halaman ini untuk mengubah password login admin panel Anda. Sangat disarankan untuk mengganti password secara berkala agar akun tetap aman.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 space-y-2.5">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Cara mengubah password:</p>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">1</span>
                            <span>Masukkan password lama Anda yang sedang digunakan saat ini.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">2</span>
                            <span>Masukkan password baru yang ingin digunakan (minimal 8 karakter).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">3</span>
                            <span>Ketik ulang password baru untuk konfirmasi.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold shrink-0">4</span>
                            <span>Klik tombol "Simpan" untuk menyimpan password baru Anda.</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start gap-3">
                    <i class="fa-solid fa-shield-halved text-rose-500 mt-0.5 shrink-0"></i>
                    <p class="text-xs text-rose-800 leading-relaxed">
                        <strong>Penting:</strong> Gunakan password yang kuat — campuran huruf besar, huruf kecil, angka, dan simbol. Jangan gunakan password yang mudah ditebak seperti "12345678" atau "password".
                    </p>
                </div>
            </div>
        </div>

        {{-- Quick Tips Section --}}
        <div class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 rounded-2xl border border-indigo-200 p-5 shadow-xs">
            <h3 class="text-sm font-bold text-indigo-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-wand-magic-sparkles text-indigo-500"></i>
                Tips Umum Penggunaan
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="flex items-start gap-2.5 bg-white rounded-xl p-3 border border-indigo-100">
                    <i class="fa-solid fa-arrows-rotate text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Jika perubahan belum muncul di website, coba buka halaman website dalam <strong>mode Incognito</strong> atau tekan <strong>Ctrl+Shift+R</strong> untuk memuat ulang tanpa cache.
                    </p>
                </div>
                <div class="flex items-start gap-2.5 bg-white rounded-xl p-3 border border-indigo-100">
                    <i class="fa-solid fa-image text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Upload foto dalam format <strong>JPG</strong> atau <strong>WebP</strong> dengan ukuran file di bawah <strong>2MB</strong> agar website tetap cepat dimuat.
                    </p>
                </div>
                <div class="flex items-start gap-2.5 bg-white rounded-xl p-3 border border-indigo-100">
                    <i class="fa-solid fa-mobile-screen text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Admin panel ini bisa diakses dari <strong>HP/tablet</strong>. Gunakan browser Chrome untuk pengalaman terbaik.
                    </p>
                </div>
                <div class="flex items-start gap-2.5 bg-white rounded-xl p-3 border border-indigo-100">
                    <i class="fa-solid fa-globe text-indigo-500 mt-0.5 shrink-0 text-xs"></i>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Klik menu <strong>"Lihat Halaman Toko"</strong> di sidebar untuk langsung melihat tampilan website toko Anda seperti yang dilihat pembeli.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
