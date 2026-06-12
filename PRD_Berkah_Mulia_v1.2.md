# Dokumen Kebutuhan Produk (PRD) — Website Katalog Berkah Mulia

## 1. Informasi Dokumen
* **Nama Proyek:** Pembangunan Website Katalog Berkah Mulia
* **Jenis Produk:** Website Katalog Digital (Showcase Produk)
* **Niche:** Pakaian Bayi, Anak-anak, & Pakaian Dalam (Baby, Kids & Underwear)
* **Teknologi Utama:** Laravel Framework (Backend) & MySQL (Database)
* **Referensi Desain UI/UX:** Carters.com
* **Versi:** 1.2
* **Tanggal:** 9 Juni 2026
* **Status:** Siap Ditinjau (Ready for Review)

---

## 2. Ringkasan Eksekutif & Latar Belakang
**Berkah Mulia** adalah bisnis retail dan grosir yang berfokus pada penjualan pakaian bayi, anak-anak, serta pakaian dalam (underwear). Untuk memperluas jangkauan pasar dan mempermudah pelanggan melihat koleksi produk secara daring, Berkah Mulia memerlukan sebuah **website katalog digital**.

Website ini akan dibangun menggunakan **Laravel** dan **MySQL** untuk menjamin performa yang cepat, manajemen keamanan data yang kokoh, serta kemudahan jika ingin ditingkatkan menjadi e-commerce penuh di masa depan. Desain antarmuka (UI/UX) akan berkiblat pada **Carters.com**, pemimpin industri pakaian anak global, yang terkenal dengan navigasi menu yang rapi, pengelompokan kategori yang jelas, dan tampilan produk yang sangat memikat mata pembeli.

---

## 3. Objektif Proyek
1. **Digitalisasi Katalog Produk:** Menyediakan katalog digital terpusat yang menampilkan seluruh koleksi Berkah Mulia secara real-time.
2. **Implementasi Stack Modern:** Menggunakan Laravel dan MySQL untuk memastikan sistem mempunyai arsitektur data yang terstruktur dan query yang cepat.
3. **Adopsi UI/UX Kelas Dunia:** Mengimplementasikan pola tata letak, kejelasan visual, dan kemudahan navigasi yang terinspirasi dari Carters.com.
4. **Lead Generation via WhatsApp:** Mengarahkan minat pengunjung langsung menjadi konversi pesan ke admin penjualan WhatsApp dengan membawa informasi produk secara otomatis.

---

## 4. Sasaran Pengguna (User Personas)
1. **Orang Tua (Pembeli Retail):** Konsumen yang ingin mencari pakaian anak/bayi dengan cepat. Mereka menyukai navigasi visual yang rapi untuk menyaring produk berdasarkan kategori atau jenis pakaian tertentu.
2. **Pelanggan Grosir / Reseller:** Pembeli skala besar yang memerlukan kepastian informasi jenis produk, variasi stok ukuran, dan akses cepat untuk bertanya langsung via WhatsApp.
3. **Admin Berkah Mulia:** Pengelola toko yang mengelola data produk, memperbarui jumlah stok, dan mengatur kategori melalui panel admin berbasis Laravel.

---

## 5. Ruang Lingkup & Fitur Utama (Product Features)

### 5.1 Tampilan Publik (Front-End / Public View) - *Referensi Gaya: Carters.com*
* **Halaman Utama (Homepage):**
  * **Header & Navigasi Utama:** Menu navigasi atas yang bersih menampilkan ke-10 kategori utama secara jelas.
  * **Hero Banner Carousel:** Spanduk visual besar di bagian atas untuk menampilkan promo terbaru atau koleksi musiman.
  * **Grid Kategori Visual:** Tampilan lingkaran atau kotak gambar representatif untuk masing-masing kategori terpopuler mirip gaya visual Carter's.
* **Halaman Katalog / Daftar Produk (Product Listing Page):**
  * Tampilan grid produk yang responsif (2 kolom di mobile, 4 kolom di desktop).
  * **Sistem Filter Samping:** Pengguna dapat menyaring produk berdasarkan Kategori Utama, Ukuran, dan Rentang Harga.
  * **Bilah Pencarian (Search Bar):** Pencarian cepat berdasarkan nama produk atau kode produk (SKU).
* **Halaman Detail Produk (Product Detail Page):**
  * Galeri foto produk dari berbagai sudut dengan fitur perbesaran (zoom).
  * Informasi detail (deskripsi bahan, tabel panduan ukuran/size chart, pilihan warna).
  * **Tombol Aksi Utama (CTA):** Tombol "Tanyakan Stok / Beli via WhatsApp" yang menghasilkan tautan teks otomatis ketika diklik: *(Contoh: "Halo Berkah Mulia, saya tertarik dengan produk [Nama Produk] - Kategori: [Nama Kategori] - Ukuran: [Ukuran]").*

### 5.2 Panel Admin (Back-End / Admin Panel - Native Laravel)
* **Manajemen Produk:** Fitur CRUD (Create, Read, Update, Delete) untuk mengelola nama produk, harga, foto, deskripsi, dan status ketersediaan stok.
* **Manajemen Kategori:** Mengatur susunan menu dan pengelompokan berdasarkan 10 kategori wajib.
* **Manajemen Variasi Data:** Mengelola relasi database MySQL untuk ukuran (0-3 bulan, S, M, L, XL, dll) dan variasi warna produk.

---

## 6. Spesifikasi Kategori Produk Wajib
Sistem database MySQL dan struktur menu Laravel wajib mendukung penuh 10 kategori produk utama berikut:
1. **Baju**
2. **Celana**
3. **Popok**
4. **Bedong**
5. **Aksesoris**
6. **Stelan**
7. **Rok**
8. **Gendongan**
9. **Underwear**
10. **Singlet**

---

## 7. Arsitektur & Kebutuhan Teknis (Technical Requirements)
* **Backend Framework:** Laravel 10+ / 11 (memanfaatkan keamanan Eloquent ORM, routing yang bersih, dan middleware bawaan).
* **Database System:** MySQL 8.0+ (menggunakan indexing pada kolom kunci di tabel `products` dan `categories` untuk pencarian berkecepatan tinggi).
* **Rancangan Struktur Database Minimum (Relasi MySQL):**
  * `categories` (`id`, `name`, `slug`)
  * `products` (`id`, `category_id`, `name`, `slug`, `description`, `price`, `status`)
  * `product_images` (`id`, `product_id`, `image_path`)
  * `product_variants` (`id`, `product_id`, `size`, `color`, `stock`)
* **Desain Responsif:** Menggunakan Tailwind CSS atau Bootstrap dengan pendekatan *Mobile-First* untuk meniru fluiditas tata letak Carters.com.
* **Optimasi Performa:** Menerapkan teknik Eager Loading (`with('category')`) pada query Laravel untuk mencegah masalah *N+1 Query* sehingga meminimalkan beban kerja database MySQL dan memastikan halaman termuat kurang dari 2.5 detik.

---

## 8. Prinsip UI/UX (UI/UX Principles - Carter's Inspired)
* **Visual Bersih & Dominan Putih:** Menggunakan latar belakang netral yang terang untuk memastikan warna-warna cerah dari pakaian bayi dan anak terlihat menonjol.
* **Tipografi Ramah Keluarga:** Menggunakan jenis huruf sans-serif yang modern, bulat, dan mudah dibaca (misalnya Poppins atau Inter).
* **Akses Menu Cepat:** Memastikan ke-10 kategori produk di atas dapat diakses maksimal dalam 2 kali klik dari halaman mana pun.

---

## 9. Garis Waktu Pelaksanaan (Timeline Estimates)
* **Minggu 1:** Perancangan Database MySQL, Pembuatan Struktur Migrasi & Arsitektur Routing Laravel, & Wireframing UI.
* **Minggu 2-3:** Pengembangan Backend Laravel (Panel Admin, Autentikasi, & CRUD Manajemen Produk).
* **Minggu 4-5:** Pengembangan Sisi Depan / Front-End (Halaman Utama, Katalog Grid, Filter Kategori Dinamis, & Responsivitas Mobile).
* **Minggu 6:** Pengujian Sistem (QA), Optimasi Indeks Query SQL, & Integrasi API Teks WhatsApp.
* **Minggu 7:** Deployment ke Production Server (Hosting/VPS) & Penyerahan Dokumen Teknis.
