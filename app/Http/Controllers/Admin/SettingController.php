<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.settings.banner');
    }

    public function banner()
    {
        $banners = Setting::get('hero_banners', []);
        $heroText = [
            'badge' => Setting::get('hero_badge', 'Koleksi Baru 2026'),
            'title_line1' => Setting::get('hero_title_line1', 'Pakaian Lembut & Nyaman'),
            'title_line2' => Setting::get('hero_title_line2', 'Untuk Buah Hati Anda'),
            'description' => Setting::get('hero_description', 'Dapatkan koleksi pakaian bayi, baju anak, setelan gemas, hingga pakaian dalam dengan bahan katun premium yang lembut, aman, dan menyerap keringat.'),
        ];
        return view('admin.settings.banner', compact('banners', 'heroText'));
    }

    public function lokasi()
    {
        $locationText = [
            'badge' => Setting::get('location_badge', 'Kunjungi Kami'),
            'title' => Setting::get('location_title', 'Lokasi Toko Offline'),
            'description' => Setting::get('location_description', 'Silakan datang langsung to toko fisik kami untuk melihat kualitas produk pakaian bayi secara langsung, berkonsultasi mengenai pembelian grosir/partai besar, serta mendapatkan penawaran spesial reseller.'),
        ];
        $storeInfo = [
            'address' => Setting::get('store_address', 'Jl. Berkah Mulia Raya No. 88, Central Business District, Kota Surakarta, Jawa Tengah 57132'),
            'hours' => Setting::get('store_hours', 'Senin - Sabtu: 08.00 - 17.00 WIB (Minggu Libur)'),
            'phone' => Setting::get('store_phone', '628123456789'),
            'map_iframe' => Setting::get('store_map_iframe', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.0863812739343!2d110.82583857500171!3d-7.56555549244837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a16f2c3d0b2f5%3A0x86da51ccbf56bc2e!2sSurakarta%2C%20Surakarta%20City%2C%20Central%20Java!5e0!3m2!1sen!2sid!4v1718000000000!5m2!1sen!2sid'),
            'map_link' => Setting::get('store_map_link', 'https://maps.google.com/?q=Berkah+Mulia+Surakarta'),
        ];
        return view('admin.settings.lokasi', compact('locationText', 'storeInfo'));
    }

    public function kontak()
    {
        $contact = [
            'whatsapp_number' => config('app.whatsapp_number', '628123456789'),
            'admin_email' => config('app.admin_email', 'admin@bmberkahmulia.com'),
        ];
        return view('admin.settings.kontak', compact('contact'));
    }

    public function updateKontak(Request $request)
    {
        $whatsapp = $request->input('whatsapp_number');
        
        // Normalize: remove all non-digit characters
        $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
        
        // Convert country code or zero prefixes to clean suffix
        if (strpos($whatsapp, '628') === 0) {
            $whatsapp = substr($whatsapp, 2);
        } elseif (strpos($whatsapp, '08') === 0) {
            $whatsapp = substr($whatsapp, 1);
        }
        
        $request->merge(['whatsapp_number' => $whatsapp]);
        
        $request->validate([
            'whatsapp_number' => ['required', 'regex:/^8[0-9]{8,12}$/'],
            'admin_email' => 'required|email|max:255',
        ], [
            'whatsapp_number.regex' => 'Format nomor WhatsApp harus diawali dengan angka 8 dan berisi 9-13 digit angka (contoh: 82112619691).',
        ]);

        $fullWhatsapp = '62' . $request->input('whatsapp_number');

        $this->updateEnvFile('WHATSAPP_NUMBER', $fullWhatsapp);
        $this->updateEnvFile('ADMIN_EMAIL', $request->input('admin_email'));

        // Clear config cache so Laravel reads the new env variables immediately
        try {
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            \Illuminate\Support\Facades\Cache::flush();
        } catch (\Exception $e) {
            // Ignore if artisan command fails in certain restricted environments
        }

        return redirect()->route('admin.settings.kontak')
            ->with('success', 'Kontak WhatsApp dan Email berhasil diperbarui!');
    }

    protected function updateEnvFile(string $key, string $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            // Check if key exists in env file (multiline search)
            if (preg_match("/^{$key}=/m", $content)) {
                // Replace existing key
                $content = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $content);
            } else {
                // Append key to the end
                $content .= "\n{$key}=\"{$value}\"\n";
            }
            
            file_put_contents($path, $content);
        }
    }

    public function panduanUkuran()
    {
        $defaultSizeGuide = [
            ['size' => 'Newborn', 'height' => 's/d 55 cm', 'weight' => 's/d 4 kg'],
            ['size' => '0-3m / S', 'height' => '55 - 61 cm', 'weight' => '4 - 5.7 kg'],
            ['size' => '3-6m / M', 'height' => '61 - 67 cm', 'weight' => '5.7 - 7.5 kg'],
            ['size' => '6-9m / L', 'height' => '67 - 72 cm', 'weight' => '7.5 - 9.3 kg'],
            ['size' => '9-12m / XL', 'height' => '72 - 78 cm', 'weight' => '9.3 - 11.1 kg'],
            ['size' => '12-18m / XXL', 'height' => '78 - 83 cm', 'weight' => '11.1 - 12.5 kg'],
            ['size' => '18-24m', 'height' => '83 - 86 cm', 'weight' => '12.5 - 13.6 kg'],
        ];

        $sizeGuide = Setting::get('size_guide', $defaultSizeGuide);
        $sizeGuideNote = Setting::get('size_guide_note', '*Ukuran di atas adalah estimasi rata-rata standar. Disarankan mengukur tinggi dan berat badan anak terlebih dahulu sebelum melakukan pemesanan.');

        return view('admin.settings.panduan-ukuran', compact('sizeGuide', 'sizeGuideNote'));
    }

    public function updatePanduanUkuran(Request $request)
    {
        $request->validate([
            'sizes' => 'required|array|min:1',
            'sizes.*.size' => 'required|string|max:50',
            'sizes.*.height' => 'required|string|max:50',
            'sizes.*.weight' => 'required|string|max:50',
            'note' => 'nullable|string|max:500',
        ]);

        Setting::set('size_guide', $request->input('sizes'));
        Setting::set('size_guide_note', $request->input('note', ''));

        return redirect()->route('admin.settings.panduanUkuran')
            ->with('success', 'Panduan ukuran berhasil diperbarui!');
    }

    public function password()
    {
        return view('admin.settings.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
        }

        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);

        return redirect()->route('admin.settings.password')
            ->with('success', 'Password berhasil diubah!');
    }

    public function updateHeroBanners(Request $request)
    {
        $request->validate([
            'banners' => 'nullable|array',
            'banners.*' => 'image|mimes:jpeg,png,jpg,webp|max:10240',
            'delete_banners' => 'nullable|array',
            'delete_banners.*' => 'string',
        ]);

        $currentBanners = Setting::get('hero_banners', []);

        // Handle deletions first
        if ($request->has('delete_banners')) {
            $deleteBanners = $request->input('delete_banners');
            foreach ($deleteBanners as $bannerToDelete) {
                if (($key = array_search($bannerToDelete, $currentBanners)) !== false) {
                    // Delete from storage disk
                    if (Storage::disk('public')->exists($bannerToDelete)) {
                        Storage::disk('public')->delete($bannerToDelete);
                    }
                    unset($currentBanners[$key]);
                }
            }
            // Re-index array
            $currentBanners = array_values($currentBanners);
        }

        // Handle new uploads
        if ($request->hasFile('banners')) {
            foreach (array_reverse($request->file('banners')) as $file) {
                if ($file->isValid()) {
                    $path = \App\Helpers\ImageHelper::storeCompressed($file, 'banners', 1600, 80);
                    if ($path) {
                        array_unshift($currentBanners, $path);
                    }
                }
            }
        }

        // Save back to settings database
        Setting::set('hero_banners', $currentBanners);

        // Clear general application cache
        

        return redirect()->route('admin.settings.banner')
            ->with('success', 'Pengaturan banner toko berhasil diperbarui!');
    }

    public function updateHeroText(Request $request)
    {
        $request->validate([
            'hero_badge' => 'required|string|max:100',
            'hero_title_line1' => 'required|string|max:100',
            'hero_title_line2' => 'required|string|max:100',
            'hero_description' => 'required|string|max:500',
        ]);

        Setting::set('hero_badge', $request->input('hero_badge'));
        Setting::set('hero_title_line1', $request->input('hero_title_line1'));
        Setting::set('hero_title_line2', $request->input('hero_title_line2'));
        Setting::set('hero_description', $request->input('hero_description'));

        
        
        
        

        return redirect()->route('admin.settings.banner')
            ->with('success', 'Teks hero banner berhasil diperbarui!');
    }

    public function updateLocationText(Request $request)
    {
        $request->validate([
            'location_badge' => 'required|string|max:100',
            'location_title' => 'required|string|max:100',
            'location_description' => 'required|string|max:500',
        ]);

        Setting::set('location_badge', $request->input('location_badge'));
        Setting::set('location_title', $request->input('location_title'));
        Setting::set('location_description', $request->input('location_description'));

        
        
        
        

        return redirect()->route('admin.settings.lokasi')
            ->with('success', 'Teks lokasi toko berhasil diperbarui!');
    }

    public function updateStoreInfo(Request $request)
    {
        $request->validate([
            'store_address' => 'required|string|max:500',
            'store_hours' => 'required|string|max:255',
            'store_phone' => 'required|string|max:20',
            'store_map_iframe' => 'nullable|string|max:1000',
            'store_map_link' => 'nullable|string|max:1000',
        ]);

        Setting::set('store_address', $request->input('store_address'));
        Setting::set('store_hours', $request->input('store_hours'));
        Setting::set('store_phone', $request->input('store_phone'));
        Setting::set('store_map_iframe', $request->input('store_map_iframe'));
        Setting::set('store_map_link', $request->input('store_map_link'));

        // Clear general cache
        
        
        
        
        
        

        return redirect()->route('admin.settings.lokasi')
            ->with('success', 'Informasi dan lokasi toko berhasil diperbarui!');
    }
}
