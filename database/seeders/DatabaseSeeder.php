<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure public storage link is created automatically
        if (!file_exists(public_path('storage'))) {
            try {
                \Illuminate\Support\Facades\Artisan::call('storage:link');
            } catch (\Exception $e) {
                // Ignore link creation errors in case of permissions or environment constraints
            }
        }

        // 1. Create Default Admin User
        User::create([
            'name' => 'Admin Berkah Mulia',
            'email' => 'admin@bmberkahmulia.com',
            'password' => Hash::make('password123'),
            'is_admin' => true,
        ]);

        // 2. The 10 Mandatory Categories
        $categoriesData = [
            'Baju',
            'Celana',
            'Popok',
            'Bedong',
            'Aksesoris',
            'Stelan',
            'Rok',
            'Gendongan',
            'Underwear',
            'Singlet',
            'Gurita'
        ];

        $categories = [];
        foreach ($categoriesData as $catName) {
            $categories[$catName] = Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
            ]);
        }

        // 3. Mock Products Data
        $productsMock = [
            [
                'category' => 'Baju',
                'name' => 'Kaos Anak Cotton Combed Premium',
                'sku' => 'BJ-001',
                'description' => 'Kaos anak berbahan 100% Cotton Combed 30s premium. Sangat lembut, dingin, menyerap keringat, dan tidak menimbulkan iritasi pada kulit bayi/anak. Cocok untuk pakaian kasual sehari-hari.',
                'price' => 45000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S (0-1 Tahun)', 'color' => 'Kuning', 'stock' => 15],
                    ['size' => 'M (1-2 Tahun)', 'color' => 'Kuning', 'stock' => 20],
                    ['size' => 'L (2-3 Tahun)', 'color' => 'Biru', 'stock' => 12],
                    ['size' => 'XL (3-4 Tahun)', 'color' => 'Biru', 'stock' => 5],
                ]
            ],
            [
                'category' => 'Celana',
                'name' => 'Celana Pendek Harian Anak Soft Cotton',
                'sku' => 'CL-001',
                'description' => 'Celana pendek anak yang dirancang untuk kenyamanan bermain seharian. Menggunakan pinggang karet elastis lembut dan bahan katun berkualitas tinggi.',
                'price' => 25000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S', 'color' => 'Abu-abu', 'stock' => 25],
                    ['size' => 'M', 'color' => 'Hitam', 'stock' => 15],
                    ['size' => 'L', 'color' => 'Navy', 'stock' => 0], // Out of stock example
                ]
            ],
            [
                'category' => 'Popok',
                'name' => 'Popok Kain Cuci Ulang (Cloth Diaper)',
                'sku' => 'PP-001',
                'description' => 'Clodi (Cloth Diaper) yang ramah lingkungan dan hemat pengeluaran. Lapisan luar waterproof anti-bocor, lapisan dalam stay-dry microfleece lembut, dilengkapi insert microfiber berdaya serap tinggi.',
                'price' => 35000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'All Size', 'color' => 'Hijau Tosca', 'stock' => 18],
                    ['size' => 'All Size', 'color' => 'Soft Pink', 'stock' => 10],
                ]
            ],
            [
                'category' => 'Bedong',
                'name' => 'Bedong Bayi Flanel Lembut Isi 3 Pcs',
                'sku' => 'BD-001',
                'description' => 'Satu set isi 3 pcs bedong bayi flanel berukuran 90x90 cm. Bahan tebal namun tetap bernapas (breathable), memberikan kehangatan ekstra untuk bayi baru lahir.',
                'price' => 75000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'Standard 90x90', 'color' => 'Kuning Motif', 'stock' => 8],
                    ['size' => 'Standard 90x90', 'color' => 'Biru Motif', 'stock' => 14],
                ]
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Topi Bayi Rajut Lucu Double Pompon',
                'sku' => 'AK-001',
                'description' => 'Topi rajut bayi premium dengan hiasan double pompon imut di bagian atas. Melindungi kepala bayi tetap hangat saat keluar rumah. Bahan melar dan lembut.',
                'price' => 18000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => '0-12 Bulan', 'color' => 'Cokelat', 'stock' => 15],
                    ['size' => '0-12 Bulan', 'color' => 'Cream', 'stock' => 22],
                    ['size' => '0-12 Bulan', 'color' => 'Merah', 'stock' => 4],
                ]
            ],
            [
                'category' => 'Stelan',
                'name' => 'Stelan Baju & Celana Bayi Pendek Cotton Premium',
                'sku' => 'ST-001',
                'description' => 'Setelan lengkap baju pendek dan celana pendek bayi bermotif lucu. Menggunakan kancing depan memudahkan pemakaian baju ke bayi yang aktif.',
                'price' => 55000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S (0-6m)', 'color' => 'Hijau Pastel', 'stock' => 12],
                    ['size' => 'M (6-12m)', 'color' => 'Kuning Soft', 'stock' => 9],
                ]
            ],
            [
                'category' => 'Rok',
                'name' => 'Rok Tutu Bayi Princess Cantik Berpayet',
                'sku' => 'RK-001',
                'description' => 'Rok tutu bayi berbahan kain tulle lembut berlapis-lapis untuk tampilan mengembang yang anggun. Pinggang elastis tidak membekas di kulit bayi.',
                'price' => 40000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S (1-2 Tahun)', 'color' => 'Soft Pink', 'stock' => 6],
                    ['size' => 'M (2-3 Tahun)', 'color' => 'Lilac', 'stock' => 10],
                ]
            ],
            [
                'category' => 'Gendongan',
                'name' => 'Gendongan Kaos Premium Anti-Pegal (Geos)',
                'sku' => 'GD-001',
                'description' => 'Gendongan kaos (Geos) praktis tanpa ring or ikat. Terbuat dari katun stretch berkualitas tebal yang kokoh menopang berat badan bayi hingga 15kg.',
                'price' => 85000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'M (BB Ibu 50-60kg)', 'color' => 'Navy Blue', 'stock' => 15],
                    ['size' => 'L (BB Ibu 60-70kg)', 'color' => 'Navy Blue', 'stock' => 10],
                ]
            ],
            [
                'category' => 'Underwear',
                'name' => 'Celana Dalam Segitiga Anak Perempuan Isi 3 Pcs',
                'sku' => 'UW-001',
                'description' => 'Satu pak berisi 3 celana dalam anak perempuan bermotif karakter lucu. Terbuat dari katun rajut tipis menyerap keringat yang elastis dan nyaman dipakai.',
                'price' => 30000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'M (Estimasi 3-5 Tahun)', 'color' => 'Mix Karakter', 'stock' => 30],
                    ['size' => 'L (Estimasi 5-7 Tahun)', 'color' => 'Mix Karakter', 'stock' => 25],
                ]
            ],
            [
                'category' => 'Singlet',
                'name' => 'Kaos Singlet Anak Cotton Putih Lembut',
                'sku' => 'SG-001',
                'description' => 'Kaos singlet basic anak-anak berwarna putih bersih. Sangat direkomendasikan sebagai kaos dalam pakaian harian sekolah atau rumah.',
                'price' => 12000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S', 'color' => 'Putih Bersih', 'stock' => 40],
                    ['size' => 'M', 'color' => 'Putih Bersih', 'stock' => 50],
                    ['size' => 'L', 'color' => 'Putih Bersih', 'stock' => 35],
                ]
            ],
            // === Produk Tambahan ===
            [
                'category' => 'Baju',
                'name' => 'Baju Lengan Panjang Bayi Newborn Polos',
                'sku' => 'BJ-002',
                'description' => 'Baju lengan panjang bayi newborn dari bahan katun organic 100%. Cocok untuk bayi baru lahir usia 0-3 bulan.',
                'price' => 38000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'Newborn (0-3m)', 'color' => 'Putih', 'stock' => 30],
                    ['size' => 'Newborn (0-3m)', 'color' => 'Kuning Lembut', 'stock' => 25],
                ]
            ],
            [
                'category' => 'Baju',
                'name' => 'Kaos Oblong Anak Motif Dinosaurus',
                'sku' => 'BJ-003',
                'description' => 'Kaos anak laki-laki bermotif dinosaurus lucu. Bahan adem, tidak luntur, dan tahan lama setelah dicuci berkali-kali.',
                'price' => 35000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S (1-2 Tahun)', 'color' => 'Hijau', 'stock' => 18],
                    ['size' => 'M (2-3 Tahun)', 'color' => 'Hijau', 'stock' => 22],
                    ['size' => 'L (3-4 Tahun)', 'color' => 'Biru', 'stock' => 14],
                    ['size' => 'XL (4-5 Tahun)', 'color' => 'Biru', 'stock' => 8],
                ]
            ],
            [
                'category' => 'Baju',
                'name' => 'Kemeja Batik Anak Premium',
                'sku' => 'BJ-004',
                'description' => 'Kemeja batik anak laki-laki untuk acara formal atau kondangan. Bahan katun halus tidak gerah.',
                'price' => 65000,
                'status' => 'po',
                'images' => [],
                'variants' => [
                    ['size' => 'S (2-3 Tahun)', 'color' => 'Cokelat Batik', 'stock' => 0],
                    ['size' => 'M (3-4 Tahun)', 'color' => 'Cokelat Batik', 'stock' => 0],
                ]
            ],
            [
                'category' => 'Celana',
                'name' => 'Celana Panjang Training Anak Sporty',
                'sku' => 'CL-002',
                'description' => 'Celana panjang training anak dengan bahan jersey lembut. Pinggang karet dan tali serut, ada saku samping.',
                'price' => 40000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S (2-3 Tahun)', 'color' => 'Abu Muda', 'stock' => 20],
                    ['size' => 'M (3-4 Tahun)', 'color' => 'Abu Muda', 'stock' => 15],
                    ['size' => 'L (4-5 Tahun)', 'color' => 'Hitam', 'stock' => 12],
                ]
            ],
            [
                'category' => 'Celana',
                'name' => 'Celana Jeans Anak Stretch Slim Fit',
                'sku' => 'CL-003',
                'description' => 'Celana jeans anak dengan bahan stretch elastis sehingga nyaman untuk bergerak. Model slim fit kekinian.',
                'price' => 55000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S (2-3 Tahun)', 'color' => 'Dark Blue', 'stock' => 10],
                    ['size' => 'M (3-4 Tahun)', 'color' => 'Dark Blue', 'stock' => 8],
                    ['size' => 'L (4-5 Tahun)', 'color' => 'Light Blue', 'stock' => 3],
                ]
            ],
            [
                'category' => 'Popok',
                'name' => 'Popok Kain Lipat Putih Isi 6 Pcs',
                'sku' => 'PP-002',
                'description' => 'Popok kain lipat tradisional dari bahan katun tebal. Ekonomis dan ramah lingkungan, isi 6 lembar per paket.',
                'price' => 28000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'Standard', 'color' => 'Putih', 'stock' => 45],
                ]
            ],
            [
                'category' => 'Bedong',
                'name' => 'Bedong Instan Kancing Bayi Anti Gerah',
                'sku' => 'BD-002',
                'description' => 'Bedong instan dengan kancing yang praktis. Bahan katun bambu yang adem dan anti bakteri, cocok untuk iklim tropis.',
                'price' => 45000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => '0-3 Bulan', 'color' => 'Abu Salur', 'stock' => 12],
                    ['size' => '0-3 Bulan', 'color' => 'Cream Polos', 'stock' => 2],
                ]
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Sarung Tangan & Kaki Bayi Set Isi 3',
                'sku' => 'AK-002',
                'description' => 'Set sarung tangan dan kaki bayi isi 3 pasang. Melindungi tangan bayi agar tidak mencakar wajah sendiri.',
                'price' => 15000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'Newborn', 'color' => 'Putih Polos', 'stock' => 50],
                    ['size' => 'Newborn', 'color' => 'Mix Warna', 'stock' => 35],
                ]
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Bib Slabber Bayi Anti Air Motif Lucu',
                'sku' => 'AK-003',
                'description' => 'Slabber / celemek makan bayi dengan lapisan anti air di bagian belakang. Mudah dibersihkan dan cepat kering.',
                'price' => 12000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'All Size', 'color' => 'Motif Hewan', 'stock' => 40],
                    ['size' => 'All Size', 'color' => 'Motif Buah', 'stock' => 1],
                ]
            ],
            [
                'category' => 'Stelan',
                'name' => 'Setelan Piyama Anak Lengan Panjang',
                'sku' => 'ST-002',
                'description' => 'Piyama anak lengan panjang celana panjang. Bahan kaos katun halus yang nyaman untuk tidur.',
                'price' => 50000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S (1-2 Tahun)', 'color' => 'Biru Motif Bintang', 'stock' => 14],
                    ['size' => 'M (2-3 Tahun)', 'color' => 'Pink Motif Kelinci', 'stock' => 11],
                    ['size' => 'L (3-4 Tahun)', 'color' => 'Biru Motif Bintang', 'stock' => 5],
                ]
            ],
            [
                'category' => 'Stelan',
                'name' => 'Setelan Gamis Anak Perempuan Syari',
                'sku' => 'ST-003',
                'description' => 'Setelan gamis anak perempuan lengkap dengan kerudung. Bahan wolfis premium yang jatuh dan tidak menerawang.',
                'price' => 85000,
                'status' => 'po',
                'images' => [],
                'variants' => [
                    ['size' => 'S (3-4 Tahun)', 'color' => 'Dusty Pink', 'stock' => 0],
                    ['size' => 'M (4-5 Tahun)', 'color' => 'Dusty Pink', 'stock' => 0],
                    ['size' => 'L (5-6 Tahun)', 'color' => 'Sage Green', 'stock' => 0],
                ]
            ],
            [
                'category' => 'Rok',
                'name' => 'Rok Plisket Anak Perempuan Casual',
                'sku' => 'RK-002',
                'description' => 'Rok plisket anak dengan bahan chiffon yang ringan dan adem. Pinggang karet elastis nyaman dipakai seharian.',
                'price' => 35000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S (2-3 Tahun)', 'color' => 'Cream', 'stock' => 9],
                    ['size' => 'M (3-4 Tahun)', 'color' => 'Dusty Blue', 'stock' => 7],
                ]
            ],
            [
                'category' => 'Gendongan',
                'name' => 'Gendongan Depan Bayi Multifungsi 4in1',
                'sku' => 'GD-002',
                'description' => 'Baby carrier multifungsi 4 posisi gendong. Dilengkapi headrest, hip seat, dan sabuk penopang pinggang. Menahan beban hingga 20kg.',
                'price' => 150000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'All Size', 'color' => 'Navy', 'stock' => 5],
                    ['size' => 'All Size', 'color' => 'Maroon', 'stock' => 3],
                ]
            ],
            [
                'category' => 'Underwear',
                'name' => 'Celana Dalam Boxer Anak Laki-Laki Isi 3',
                'sku' => 'UW-002',
                'description' => 'Celana dalam model boxer anak laki-laki. Bahan katun stretch nyaman, isi 3 pcs dengan motif bervariasi.',
                'price' => 35000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'M (3-5 Tahun)', 'color' => 'Mix Motif', 'stock' => 20],
                    ['size' => 'L (5-7 Tahun)', 'color' => 'Mix Motif', 'stock' => 18],
                    ['size' => 'XL (7-9 Tahun)', 'color' => 'Mix Motif', 'stock' => 4],
                ]
            ],
            [
                'category' => 'Singlet',
                'name' => 'Singlet Bayi Tanpa Lengan Katun Rib',
                'sku' => 'SG-002',
                'description' => 'Singlet bayi tanpa lengan bahan katun rib yang elastis dan adem. Cocok dipakai di dalam baju sebagai lapisan penyerap keringat.',
                'price' => 10000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'S (0-6m)', 'color' => 'Putih', 'stock' => 60],
                    ['size' => 'M (6-12m)', 'color' => 'Putih', 'stock' => 55],
                    ['size' => 'L (12-18m)', 'color' => 'Putih', 'stock' => 40],
                ]
            ],
            [
                'category' => 'Baju',
                'name' => 'Romper Bayi Pendek Motif Kartun',
                'sku' => 'BJ-005',
                'description' => 'Romper/jumper bayi pendek bermotif kartun. Kancing di selangkangan memudahkan ganti popok.',
                'price' => 42000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => '0-3 Bulan', 'color' => 'Biru Langit', 'stock' => 16],
                    ['size' => '3-6 Bulan', 'color' => 'Kuning Cerah', 'stock' => 12],
                    ['size' => '6-12 Bulan', 'color' => 'Pink Muda', 'stock' => 9],
                ]
            ],
            [
                'category' => 'Celana',
                'name' => 'Celana Pop Bayi Polos Isi 3 Pcs',
                'sku' => 'CL-004',
                'description' => 'Celana pop bayi tanpa kaki (model celana pendek). Isi 3 pcs warna polos, pinggang karet lembut.',
                'price' => 22000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'Newborn (0-3m)', 'color' => 'Mix Polos', 'stock' => 35],
                    ['size' => 'S (3-6m)', 'color' => 'Mix Polos', 'stock' => 28],
                ]
            ],
            [
                'category' => 'Bedong',
                'name' => 'Selimut Bedong Muslin Bamboo Premium',
                'sku' => 'BD-003',
                'description' => 'Selimut bedong muslin dari serat bambu. Ultra lembut, breathable, makin dicuci makin halus. Ukuran besar 120x120cm.',
                'price' => 95000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => '120x120cm', 'color' => 'Sage Green', 'stock' => 6],
                    ['size' => '120x120cm', 'color' => 'Dusty Rose', 'stock' => 4],
                    ['size' => '120x120cm', 'color' => 'Ivory', 'stock' => 2],
                ]
            ],
            [
                'category' => 'Aksesoris',
                'name' => 'Kaos Kaki Bayi Anti Slip Isi 5 Pasang',
                'sku' => 'AK-004',
                'description' => 'Kaos kaki bayi dengan sol anti slip agar bayi tidak tergelincir. Isi 5 pasang berbagai motif lucu.',
                'price' => 25000,
                'status' => 'sold_out',
                'images' => [],
                'variants' => [
                    ['size' => '0-12 Bulan', 'color' => 'Mix Motif', 'stock' => 0],
                    ['size' => '1-3 Tahun', 'color' => 'Mix Motif', 'stock' => 0],
                ]
            ],
            [
                'category' => 'Gurita',
                'name' => 'Gurita Bayi Instan Perekat Isi 6 Pcs',
                'sku' => 'GR-001',
                'description' => 'Gurita bayi instan perekat terbuat dari kain katun lembut berkualitas premium, meminimalkan iritasi pada kulit sensitif bayi baru lahir. Dilengkapi perekat praktis.',
                'price' => 38000,
                'status' => 'ready',
                'images' => [],
                'variants' => [
                    ['size' => 'Standard', 'color' => 'Putih', 'stock' => 50],
                ]
            ],
        ];

        // Seed products, images, and variants
        foreach ($productsMock as $pData) {
            $cat = $categories[$pData['category']];

            $product = Product::create([
                'category_id' => $cat->id,
                'name' => $pData['name'],
                'slug' => Str::slug($pData['name']),
                'sku' => $pData['sku'],
                'description' => $pData['description'],
                'price' => $pData['price'],
                'status' => $pData['status'],
            ]);

            // Save images
            foreach ($pData['images'] as $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $img,
                ]);
            }

            // Save variants
            foreach ($pData['variants'] as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $v['size'],
                    'color' => $v['color'],
                    'stock' => $v['stock'],
                ]);
            }
        }
    }
}
