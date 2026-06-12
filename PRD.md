# PRD – Project Requirements Document
## Berkah Mulia – E-Catalog Toko Pakaian Bayi & Anak

**Versi:** 1.0  
**Tanggal:** 12 Juni 2026  
**Author:** Tim Pengembang Berkah Mulia  
**Status:** Active Development

---

## 1. Overview

### 1.1 Deskripsi Proyek

**Berkah Mulia** adalah aplikasi web e-catalog (katalog digital) untuk toko pakaian bayi dan anak yang berlokasi di Surakarta, Jawa Tengah. Aplikasi ini berfungsi sebagai etalase digital yang menampilkan koleksi produk pakaian bayi (bedong, baju, aksesoris, setelan, pakaian dalam, dll.) kepada pelanggan secara online, dengan kemampuan admin panel untuk mengelola seluruh konten katalog.

> **Catatan:** Aplikasi ini **bukan** e-commerce/toko online dengan fitur transaksi. Ini adalah **katalog digital** (showcase) dimana pelanggan dapat melihat produk, lalu menghubungi toko via WhatsApp untuk pembelian.

### 1.2 Tujuan Bisnis

- Menyediakan platform digital bagi toko offline Berkah Mulia untuk memamerkan produk secara online.
- Meningkatkan jangkauan pasar ke pelanggan di luar area toko fisik.
- Memfasilitasi pelanggan grosir/reseller untuk melihat katalog produk dan menghubungi toko langsung via WhatsApp.
- Memberikan informasi lengkap tentang produk (harga, ukuran, warna, stok) tanpa harus datang ke toko.
- Menyediakan panduan ukuran bayi sebagai referensi pelanggan sebelum memesan.

### 1.3 Target Pengguna

| Pengguna | Deskripsi |
|---|---|
| **Pelanggan (Public)** | Ibu/ayah/keluarga yang mencari pakaian bayi dan anak secara online, baik pembeli satuan maupun reseller/grosir. |
| **Admin Toko** | Pemilik atau karyawan toko Berkah Mulia yang mengelola katalog produk, pengaturan banner, informasi toko, dll. |

### 1.4 Lingkup Proyek

- **Dalam Lingkup:** Katalog produk publik, halaman detail produk, admin panel untuk CRUD produk/kategori, manajemen pengaturan toko (banner, lokasi, panduan ukuran, password admin).
- **Di Luar Lingkup:** Keranjang belanja, pembayaran online, tracking pengiriman, registrasi pelanggan, sistem chat bawaan, multi-tenant.

---

## 2. Requirements

### 2.1 Functional Requirements

| ID | Requirement | Prioritas |
|---|---|---|
| FR-01 | Sistem menampilkan halaman beranda dengan hero banner carousel, kategori produk, dan produk unggulan. | Tinggi |
| FR-02 | Sistem menyediakan halaman katalog dengan filter (kategori, harga, ukuran), pencarian, dan sorting. | Tinggi |
| FR-03 | Sistem menampilkan halaman detail produk beserta galeri gambar, varian (ukuran/warna/stok), dan produk terkait. | Tinggi |
| FR-04 | Admin dapat login/logout menggunakan email dan password. | Tinggi |
| FR-05 | Admin dapat melakukan CRUD (Create, Read, Update, Delete) produk dengan upload gambar multiple, varian, dan deskripsi. | Tinggi |
| FR-06 | Admin dapat melakukan CRUD kategori produk beserta gambar kategori. | Tinggi |
| FR-07 | Admin dapat mengelola pengaturan banner homepage (upload, hapus, reorder). | Sedang |
| FR-08 | Admin dapat mengedit teks hero section (badge, judul, deskripsi). | Sedang |
| FR-09 | Admin dapat mengelola informasi lokasi toko (alamat, jam operasional, nomor telepon, embed Google Maps, foto toko). | Sedang |
| FR-10 | Admin dapat mengelola panduan ukuran bayi (tabel ukuran dinamis dengan catatan). | Sedang |
| FR-11 | Admin dapat mengubah password akun. | Sedang |
| FR-12 | Admin dapat melakukan bulk update status produk dan bulk delete. | Sedang |
| FR-13 | Admin dapat export data produk ke CSV dan mencetak laporan PDF. | Sedang |
| FR-14 | Admin dapat import data produk dari file CSV. | Sedang |
| FR-15 | Sistem meng-generate sitemap.xml otomatis untuk SEO. | Rendah |
| FR-16 | Sistem menyediakan link WhatsApp untuk komunikasi dengan toko. | Tinggi |
| FR-17 | Dashboard admin menampilkan overview (total produk, kategori, stok rendah, stok habis, produk terbaru). | Sedang |

### 2.2 Non-Functional Requirements

| ID | Requirement | Deskripsi |
|---|---|---|
| NFR-01 | **Performance** | Halaman publik harus load cepat (<3 detik). Menggunakan caching (database cache) untuk data kategori, produk unggulan, dan pengaturan toko. Gambar dikompresi dan di-resize otomatis saat upload. |
| NFR-02 | **Security** | Admin panel dilindungi oleh middleware autentikasi dan middleware admin. Rate limiting diterapkan pada login (5 percobaan/menit) dan API admin (30 request/menit). Password di-hash menggunakan bcrypt (12 rounds). |
| NFR-03 | **Responsiveness** | UI harus responsif dan berfungsi dengan baik di desktop, tablet, dan smartphone. Menggunakan TailwindCSS dengan pendekatan mobile-first. |
| NFR-04 | **SEO** | Setiap halaman memiliki meta title dan description yang sesuai. URL menggunakan slug yang human-readable. Sitemap XML tersedia di `/sitemap.xml`. |
| NFR-05 | **Accessibility** | Kontras warna memenuhi standar WCAG. Gambar memiliki atribut alt yang deskriptif. Navigasi mendukung keyboard. |
| NFR-06 | **Scalability** | Arsitektur mendukung penambahan fitur kedepannya seperti multi-admin, order management, dsb. |
| NFR-07 | **Data Integrity** | Produk yang dihapus menggunakan soft delete, sehingga data tidak hilang permanen. Gambar otomatis terhapus dari disk saat produk di-force delete. |
| NFR-08 | **Localization** | Seluruh interface menggunakan Bahasa Indonesia (locale: `id`). |

---

## 3. Core Features

### 3.1 Public Storefront (Halaman Publik)

#### 3.1.1 Homepage / Beranda
- **Hero Banner Carousel** – Slideshow gambar promosi yang dapat dikelola admin dengan kontrol prev/next dan auto-play.
- **Kategori Produk** – Grid visual kategori dengan gambar thumbnail, mengarahkan ke katalog terfilter.
- **Produk Unggulan** – Menampilkan 8 produk terbaru dengan status "Ready" beserta harga, gambar, dan badge kategori.
- **Lokasi Toko** – Section informasi toko dengan alamat, jam operasional, embed Google Maps, dan foto toko.
- **Panduan Ukuran Bayi** – Tabel referensi ukuran berdasarkan usia, tinggi badan, dan berat badan bayi.
- **CTA WhatsApp** – Tombol langsung menuju WhatsApp toko untuk inquiry grosir/reseller.

#### 3.1.2 Katalog Produk (`/katalog`)
- Listing produk dengan **pagination** (12 item/halaman).
- **Filter multi-kriteria:**
  - Kategori (multi-select via slug).
  - Rentang harga (min–max).
  - Ukuran (berdasarkan varian yang tersedia).
- **Pencarian** berdasarkan nama produk atau SKU.
- **Sorting:** Terbaru, Harga Termurah, Harga Termahal.
- Query string disimpan antar halaman pagination.

#### 3.1.3 Detail Produk (`/katalog/{slug}`)
- **Galeri gambar** produk dengan thumbnail navigasi dan preview zoom.
- Informasi: nama, kategori, SKU, harga, status ketersediaan, deskripsi.
- **Tabel varian** menampilkan ukuran, warna, dan stok per varian.
- **Produk terkait** – 4 produk dari kategori yang sama.
- **Tombol WhatsApp** – Pre-filled message untuk order/inquiry spesifik produk.
- **Breadcrumb navigation** untuk UX yang baik.

#### 3.1.4 SEO & Sitemap
- Dynamic sitemap.xml dengan prioritas: Homepage (1.0) → Katalog (0.9) → Kategori (0.8) → Produk (0.7).
- Meta tags per halaman.
- URL berbasis slug yang SEO-friendly.

### 3.2 Admin Panel (`/admin`)

#### 3.2.1 Authentication
- Login form dengan email + password.
- Redirect otomatis ke dashboard jika sudah login.
- Session management (regenerate on login, invalidate on logout).
- Rate limiting: maksimal 5 percobaan login per menit.

#### 3.2.2 Dashboard (`/admin/dashboard`)
- **Statistik Overview:** Total produk, total kategori, jumlah stok rendah (≤5), jumlah stok habis (0).
- **Produk Terbaru:** 5 produk terakhir ditambahkan.
- **Alert Stok Rendah:** 5 varian dengan stok terendah.

#### 3.2.3 Manajemen Produk (`/admin/products`)
- **CRUD lengkap** dengan form modal (tambah/edit inline tanpa pindah halaman).
- **Upload gambar multiple** – Gambar otomatis dikompresi dan di-resize (max 1200px, 80% quality) menggunakan GD Library.
- **Manajemen varian** – Dinamis menambahkan baris varian (ukuran, warna, stok).
- **Status produk:** Ready, Pre-Order (PO), Sold Out.
- **Filter & Search** pada tabel admin (by kategori, status, nama/SKU).
- **Sortable columns:** Nama, Kategori, Harga, Status, Tanggal dibuat.
- **Bulk Operations:**
  - Bulk update status (pilih beberapa produk → ubah status sekaligus).
  - Bulk delete (pilih beberapa produk → hapus sekaligus).
  - Select All across pages.
- **Import CSV** – Upload file CSV untuk create/update produk massal. Kolom: nama, sku, kategori, harga, status, deskripsi, ukuran, warna, stok. Deteksi duplikat via SKU.
- **Export CSV** – Download data produk (dengan filter aktif) ke file CSV UTF-8 (Excel-compatible).
- **Export PDF** – Halaman print-ready untuk mencetak katalog produk berstatus "Ready".

#### 3.2.4 Manajemen Kategori (`/admin/categories`)
- CRUD kategori dengan nama, slug otomatis, dan upload gambar kategori.
- Gambar dikompresi otomatis saat upload.

#### 3.2.5 Pengaturan Toko (`/admin/settings`)
- **Banner:**
  - Upload banner baru (multi-file, maks 10MB per gambar).
  - Hapus banner yang ada.
  - Preview grid banner aktif.
  - Edit teks hero section (badge, judul baris 1 & 2, deskripsi).
- **Lokasi:**
  - Edit teks section lokasi (badge, judul, deskripsi).
  - Edit informasi toko (alamat, jam operasional, nomor telepon).
  - Input URL iframe Google Maps dan link Google Maps.
  - Upload/hapus foto toko.
- **Panduan Ukuran:**
  - Tabel ukuran dinamis (tambah/hapus baris).
  - Kolom: Ukuran/Usia, Tinggi Badan, Berat Badan.
  - Catatan tambahan di bawah tabel.
- **Password:**
  - Ubah password admin (validasi password lama, min 8 karakter, konfirmasi).

---

## 4. User Flow

### 4.1 Flow Pelanggan (Public User)

```
┌─────────────┐    ┌──────────────────┐    ┌─────────────────┐    ┌──────────────┐
│  Homepage   │───→│  Katalog Produk  │───→│  Detail Produk  │───→│  WhatsApp    │
│  (Beranda)  │    │  (/katalog)      │    │  (/katalog/slug)│    │  (Pesan Toko)│
└─────────────┘    └──────────────────┘    └─────────────────┘    └──────────────┘
      │                    ▲                       │
      │                    │                       │
      ▼                    │                       ▼
┌─────────────┐    ┌──────────────────┐    ┌─────────────────┐
│  Kategori   │───→│  Filter/Search   │    │  Produk Terkait │
│  (Klik)     │    │  Hasil           │    │  (4 item)       │
└─────────────┘    └──────────────────┘    └─────────────────┘
```

**Detail Flow:**

1. **Landing → Homepage:** Pelanggan mengakses website dan melihat hero banner, kategori, serta produk unggulan.
2. **Browse Kategori:** Pelanggan mengklik salah satu kategori untuk melihat produk terfilter.
3. **Pencarian/Filter:** Pelanggan menggunakan search bar atau filter (kategori, harga, ukuran) untuk menemukan produk.
4. **Lihat Detail:** Pelanggan mengklik produk untuk melihat galeri gambar, varian, harga, dan stok.
5. **Hubungi Toko:** Pelanggan menekan tombol WhatsApp untuk mengirim pesan order/inquiry ke toko.
6. **Cek Lokasi:** Pelanggan melihat section lokasi toko, alamat, dan Google Maps jika ingin datang langsung.
7. **Referensi Ukuran:** Pelanggan mengecek panduan ukuran bayi sebelum menentukan ukuran produk.

### 4.2 Flow Admin

```
┌──────────┐    ┌───────────┐    ┌──────────────────────────────────────────┐
│  Login   │───→│ Dashboard │───→│  Manajemen                              │
│  (/admin │    │ (Overview)│    │  ┌──────────┐ ┌──────────┐ ┌──────────┐ │
│  /login) │    │           │    │  │ Produk   │ │ Kategori │ │ Settings │ │
└──────────┘    └───────────┘    │  │ (CRUD,   │ │ (CRUD)   │ │ (Banner, │ │
                                 │  │  Import, │ │          │ │  Lokasi, │ │
                                 │  │  Export) │ │          │ │  Ukuran, │ │
                                 │  └──────────┘ └──────────┘ │  Passwd) │ │
                                 │                             └──────────┘ │
                                 └──────────────────────────────────────────┘
```

**Detail Flow:**

1. **Login:** Admin mengakses `/admin/login`, memasukkan email dan password.
2. **Dashboard:** Setelah login, admin melihat statistik total produk, kategori, stok rendah, dan produk terbaru.
3. **Kelola Produk:** Admin membuat/edit/hapus produk, upload gambar, atur varian, atau lakukan bulk operations.
4. **Import/Export:** Admin import produk dari CSV atau export data ke CSV/PDF untuk kebutuhan operasional.
5. **Kelola Kategori:** Admin membuat/edit/hapus kategori dan upload gambar kategori.
6. **Pengaturan:** Admin memperbarui banner, teks hero, informasi lokasi, panduan ukuran, atau mengubah password.
7. **Logout:** Admin keluar dari sesi.

---

## 5. Architecture

### 5.1 Architecture Pattern

Aplikasi menggunakan arsitektur **MVC (Model-View-Controller)** bawaan framework Laravel.

```
┌─────────────────────────────────────────────────────┐
│                    CLIENT (Browser)                  │
│  ┌────────────┐  ┌──────────────┐  ┌──────────────┐ │
│  │ Homepage   │  │ Katalog      │  │ Admin Panel  │ │
│  │ (Blade)    │  │ (Blade)      │  │ (Blade)      │ │
│  └────────────┘  └──────────────┘  └──────────────┘ │
└──────────────────────┬──────────────────────────────┘
                       │ HTTP Request
                       ▼
┌──────────────────────────────────────────────────────┐
│                   MIDDLEWARE LAYER                    │
│  ┌───────────┐  ┌───────────┐  ┌──────────────────┐ │
│  │ Auth      │  │ Admin     │  │ Rate Limiting    │ │
│  │ Middleware│  │ Middleware│  │ (throttle)       │ │
│  └───────────┘  └───────────┘  └──────────────────┘ │
└──────────────────────┬──────────────────────────────┘
                       │
                       ▼
┌──────────────────────────────────────────────────────┐
│                  CONTROLLER LAYER                    │
│  ┌────────────────┐  ┌───────────────────────────┐   │
│  │ Public         │  │ Admin                     │   │
│  │ CatalogCtrl    │  │ ProductCtrl, CategoryCtrl │   │
│  │ SitemapCtrl    │  │ SettingCtrl, DashboardCtrl│   │
│  │                │  │ AuthCtrl                  │   │
│  └────────────────┘  └───────────────────────────┘   │
└──────────────────────┬──────────────────────────────┘
                       │
                       ▼
┌──────────────────────────────────────────────────────┐
│                    MODEL LAYER                       │
│  ┌──────────┐ ┌─────────┐ ┌──────────┐ ┌─────────┐ │
│  │ Product  │ │Category │ │ Setting  │ │ User    │ │
│  │ (Soft    │ │         │ │ (KV      │ │ (Admin) │ │
│  │  Delete) │ │         │ │  Store)  │ │         │ │
│  ├──────────┤ └─────────┘ └──────────┘ └─────────┘ │
│  │ProductImg│                                       │
│  │ProductVar│                                       │
│  └──────────┘                                       │
└──────────────────────┬──────────────────────────────┘
                       │
                       ▼
┌──────────────────────────────────────────────────────┐
│                  SERVICE LAYER                       │
│  ┌──────────────┐  ┌────────────────┐               │
│  │ ImageHelper  │  │ Cache (DB)     │               │
│  │ (Compress,   │  │ (Categories,   │               │
│  │  Resize, GD) │  │  Products,     │               │
│  └──────────────┘  │  Settings)     │               │
│                     └────────────────┘               │
└──────────────────────┬──────────────────────────────┘
                       │
                       ▼
┌──────────────────────────────────────────────────────┐
│                  STORAGE LAYER                       │
│  ┌──────────────┐  ┌────────────────┐               │
│  │ MySQL        │  │ Local File     │               │
│  │ (Database)   │  │ Storage        │               │
│  │              │  │ (public disk)  │               │
│  └──────────────┘  └────────────────┘               │
└──────────────────────────────────────────────────────┘
```

### 5.2 Key Architectural Decisions

| Keputusan | Alasan |
|---|---|
| **Server-Side Rendering (Blade)** | Optimal untuk SEO, cocok untuk katalog produk statis. Tidak memerlukan SPA karena tidak ada interaksi real-time. |
| **Key-Value Settings (DB)** | Fleksibel untuk menyimpan berbagai pengaturan toko tanpa perlu tabel terpisah per jenis setting. Nilai disimpan dalam format JSON. |
| **Database Cache** | Caching menggunakan database agar deployment sederhana tanpa perlu Redis/Memcached di lingkungan hosting shared. |
| **Image Compression (GD)** | Otomatis resize & compress saat upload menggunakan GD Library bawaan PHP. Mendukung JPEG, PNG, WebP. |
| **Soft Delete pada Produk** | Produk yang dihapus tidak langsung hilang dari database, memungkinkan recovery. File gambar dihapus hanya saat force delete. |
| **Modal-based CRUD** | Form tambah/edit produk menggunakan modal di halaman yang sama (bukan halaman terpisah) untuk efisiensi navigasi admin. |

### 5.3 Directory Structure

```
berkah-mulia/
├── app/
│   ├── Helpers/
│   │   └── ImageHelper.php          # Utilitas kompresi & resize gambar
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── ProductController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   └── SettingController.php
│   │   │   ├── CatalogController.php
│   │   │   └── SitemapController.php
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php   # Cek akses admin (is_admin flag)
│   │   └── Requests/
│   │       ├── StoreProductRequest.php
│   │       └── UpdateProductRequest.php
│   └── Models/
│       ├── Category.php
│       ├── Product.php               # Soft Deletes, cache invalidation
│       ├── ProductImage.php
│       ├── ProductVariant.php
│       ├── Setting.php               # Key-Value store + caching
│       └── User.php
├── database/migrations/              # 12 migration files
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php             # Layout publik (navbar, footer)
│   │   └── admin.blade.php           # Layout admin (sidebar, topbar)
│   ├── home.blade.php                # Homepage
│   ├── catalog/
│   │   ├── index.blade.php           # Katalog listing
│   │   └── show.blade.php            # Detail produk
│   └── admin/
│       ├── auth/login.blade.php
│       ├── dashboard.blade.php
│       ├── products/
│       ├── categories/
│       └── settings/
│           ├── banner.blade.php
│           ├── lokasi.blade.php
│           ├── panduan-ukuran.blade.php
│           └── password.blade.php
├── public/                           # Static assets & symlink storage
├── routes/web.php                    # Route definitions
├── storage/app/public/               # Uploaded images (products, banners, etc.)
└── .env                              # Environment configuration
```

---

## 6. Database Schema

### 6.1 Entity Relationship Diagram (ERD)

```
┌───────────────┐       ┌────────────────┐       ┌──────────────────┐
│    users      │       │   categories   │       │    settings      │
├───────────────┤       ├────────────────┤       ├──────────────────┤
│ id (PK)       │       │ id (PK)        │       │ id (PK)          │
│ name          │       │ name           │       │ key (UNIQUE)     │
│ email (UNIQ)  │       │ slug (UNIQUE)  │       │ value (LONGTEXT) │
│ email_verified│       │ image_path     │       │ created_at       │
│ password      │       │ created_at     │       │ updated_at       │
│ is_admin      │       │ updated_at     │       └──────────────────┘
│ remember_token│       └───────┬────────┘
│ created_at    │               │ 1
│ updated_at    │               │
└───────────────┘               │ ∞
                        ┌───────┴────────┐
                        │    products    │
                        ├────────────────┤
                        │ id (PK)        │
                        │ category_id(FK)│───→ categories.id (CASCADE DELETE)
                        │ name           │
                        │ slug (UNIQUE)  │
                        │ sku (UNIQUE)   │
                        │ description    │
                        │ price (12,2)   │
                        │ status         │     ← enum: 'ready','sold_out','po'
                        │ deleted_at     │     ← Soft Deletes
                        │ created_at     │
                        │ updated_at     │
                        └───────┬────────┘
                           1    │    1
                                │
                     ┌──────────┼──────────┐
                     │ ∞                   │ ∞
             ┌───────┴────────┐   ┌────────┴─────────┐
             │ product_images │   │ product_variants  │
             ├────────────────┤   ├──────────────────┤
             │ id (PK)        │   │ id (PK)          │
             │ product_id(FK) │   │ product_id (FK)  │
             │ image_path     │   │ size             │
             │ created_at     │   │ color            │
             │ updated_at     │   │ stock (INT, def 0│
             └────────────────┘   │ created_at       │
                                  │ updated_at       │
                                  └──────────────────┘
```

### 6.2 Tabel Infrastruktur (Laravel Default)

| Tabel | Deskripsi |
|---|---|
| `sessions` | Menyimpan data sesi pengguna (session driver: database). Fields: id, user_id, ip_address, user_agent, payload, last_activity. |
| `cache` | Menyimpan data cache aplikasi (cache driver: database). |
| `cache_locks` | Lock mekanisme untuk cache concurrency. |
| `jobs` | Antrian job (queue driver: database). |
| `job_batches` | Batch processing untuk job queue. |
| `failed_jobs` | Log job yang gagal dijalankan. |
| `password_reset_tokens` | Token reset password. |

### 6.3 Detail Tabel Utama

#### `users`
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
name            VARCHAR(255) NOT NULL
email           VARCHAR(255) UNIQUE NOT NULL
email_verified  TIMESTAMP NULL
password        VARCHAR(255) NOT NULL       -- Bcrypt (12 rounds)
is_admin        BOOLEAN DEFAULT FALSE       -- Flag akses admin
remember_token  VARCHAR(100) NULL
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

#### `categories`
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
name            VARCHAR(255) NOT NULL
slug            VARCHAR(255) UNIQUE NOT NULL
image_path      VARCHAR(255) NULL           -- Path gambar di storage
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

#### `products`
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
category_id     BIGINT UNSIGNED NOT NULL    -- FK → categories.id (CASCADE)
name            VARCHAR(255) NOT NULL
slug            VARCHAR(255) UNIQUE NOT NULL
sku             VARCHAR(255) UNIQUE NULL
description     TEXT NULL
price           DECIMAL(12,2) NOT NULL
status          VARCHAR(255) DEFAULT 'ready' -- INDEX (ready|sold_out|po)
deleted_at      TIMESTAMP NULL              -- Soft Delete
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

#### `product_images`
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
product_id      BIGINT UNSIGNED NOT NULL    -- FK → products.id (CASCADE)
image_path      VARCHAR(255) NOT NULL       -- Path di storage/app/public
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

#### `product_variants`
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
product_id      BIGINT UNSIGNED NOT NULL    -- FK → products.id (CASCADE)
size            VARCHAR(255) NULL           -- e.g., 'S', 'M', 'L', 'Newborn'
color           VARCHAR(255) NULL           -- e.g., 'Merah', 'Biru'
stock           INTEGER DEFAULT 0
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

#### `settings`
```sql
id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
key             VARCHAR(255) UNIQUE NOT NULL
value           LONGTEXT NULL               -- JSON-encoded values
created_at      TIMESTAMP NULL
updated_at      TIMESTAMP NULL
```

**Setting Keys yang Digunakan:**

| Key | Tipe Value | Deskripsi |
|---|---|---|
| `hero_banners` | Array\<string\> | List path file banner |
| `hero_badge` | string | Badge text di hero section |
| `hero_title_line1` | string | Judul baris pertama |
| `hero_title_line2` | string | Judul baris kedua |
| `hero_description` | string | Deskripsi hero section |
| `location_badge` | string | Badge section lokasi |
| `location_title` | string | Judul section lokasi |
| `location_description` | string | Deskripsi section lokasi |
| `store_address` | string | Alamat lengkap toko |
| `store_hours` | string | Jam operasional |
| `store_phone` | string | Nomor telepon/WA |
| `store_map_iframe` | string | URL embed Google Maps |
| `store_map_link` | string | URL link Google Maps |
| `store_image` | string | Path foto toko |
| `size_guide` | Array\<object\> | Data tabel panduan ukuran |
| `size_guide_note` | string | Catatan panduan ukuran |

---

## 7. Tech Stack

### 7.1 Backend

| Teknologi | Versi | Fungsi |
|---|---|---|
| **PHP** | ^8.3 | Runtime bahasa pemrograman server-side |
| **Laravel** | ^13.8 | Framework PHP utama (MVC, routing, ORM, middleware, caching) |
| **Laravel Tinker** | ^3.0 | REPL interaktif untuk debugging |
| **MySQL** | 8.x | Database relasional utama |
| **GD Library** | (PHP ext) | Kompresi dan resize gambar saat upload |
| **Composer** | 2.x | Package manager PHP |

### 7.2 Frontend

| Teknologi | Versi | Fungsi |
|---|---|---|
| **Blade Templates** | (Laravel) | Template engine server-side rendering |
| **TailwindCSS** | ^4.0 | Utility-first CSS framework untuk styling responsif |
| **Vite** | ^8.0 | Build tool dan dev server untuk asset bundling |
| **Font Awesome** | 6.x (CDN) | Ikon library |
| **Google Fonts** | – | Font: Instrument Sans (400, 500, 600) |
| **Vanilla JavaScript** | – | Interaktivitas client-side (carousel, modals, filters) |

### 7.3 Development Tools

| Teknologi | Versi | Fungsi |
|---|---|---|
| **Laravel Pail** | ^1.2.5 | Real-time log viewer di terminal |
| **Laravel Pao** | ^1.0.6 | Development utility |
| **Laravel Pint** | ^1.27 | PHP code style fixer (PSR-12) |
| **PHPUnit** | ^12.5 | Unit & feature testing framework |
| **Mockery** | ^1.6 | Mock objects untuk testing |
| **Faker** | ^1.23 | Generate data palsu untuk testing/seeding |
| **Concurrently** | ^9.0 | Menjalankan multiple processes paralel (dev server) |

### 7.4 Infrastructure & Deployment

| Komponen | Detail |
|---|---|
| **Web Server** | PHP Built-in Server (dev) / Apache/Nginx (prod) |
| **Session Driver** | Database |
| **Cache Driver** | Database |
| **Queue Driver** | Database |
| **File Storage** | Local disk (`storage/app/public` → symlink ke `public/storage`) |
| **Environment** | `.env` file-based configuration |

### 7.5 Dev Server Command

```bash
# Start all services concurrently:
composer dev
# Runs: php artisan serve + queue:listen + pail (logs) + npm run dev (vite)
```

---

*Dokumen ini merupakan referensi teknis lengkap proyek Berkah Mulia dan akan diperbarui seiring perkembangan fitur.*
