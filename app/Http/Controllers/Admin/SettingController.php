<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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
        $announcementText = Setting::get('store_announcement_text', 'Selamat datang di Berkah Mulia! Koleksi Pakaian Bayi, Anak-anak & Pakaian Dalam Terbaik.');
        return view('admin.settings.banner', compact('banners', 'heroText', 'announcementText'));
    }

    public function lokasi()
    {
        $locationText = [
            'badge' => Setting::get('location_badge', 'Kunjungi Kami'),
            'title' => Setting::get('location_title', 'Lokasi Toko Offline'),
            'description' => Setting::get('location_description', 'Silakan datang langsung to toko fisik kami untuk melihat kualitas produk pakaian bayi secara langsung, berkonsultasi mengenai pembelian grosir/partai besar, serta mendapatkan penawaran spesial reseller.'),
        ];
        $storeInfo = [
            'address' => Setting::get('store_address', 'Jl. Poin Mas 40, Sawangan , Kota Depok, Jawa Barat'),
            'hours' => Setting::get('store_hours', 'Senin - Sabtu: 08.00 - 17.00 WIB (Minggu Libur)'),
            'phone' => Setting::get('store_phone', '628123456789'),
            'map_iframe' => Setting::get('store_map_iframe', 'https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3964.9688145288073!2d106.79495617499184!3d-6.398020293592605!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zNsKwMjMnNTIuOSJTIDEwNsKwNDcnNTEuMSJF!5e0!3m2!1sid!2sid!4v1781447494862!5m2!1sid!2sid'),
            'map_link' => Setting::get('store_map_link', 'https://maps.app.goo.gl/mYnJQ52kxqzy784y8'),
        ];
        return view('admin.settings.lokasi', compact('locationText', 'storeInfo'));
    }

    public function kontak()
    {
        $contact = [
            'whatsapp_number' => Setting::get('whatsapp_number', config('app.whatsapp_number', '628123456789')),
            'admin_email' => Setting::get('admin_email', config('app.admin_email', 'admin@bmberkahmulia.com')),
            'whatsapp_message_template' => Setting::get('whatsapp_message_template', 'Halo Admin Berkah Mulia, saya ingin bertanya mengenai produk...'),
            'instagram_url' => Setting::get('instagram_url', 'https://www.instagram.com'),
            'tiktok_url' => Setting::get('tiktok_url', 'https://www.tiktok.com'),
            'tiktok_name' => Setting::get('tiktok_name', ''),
            'shopee_url' => Setting::get('shopee_url', 'https://shopee.co.id'),
            'show_instagram_nav' => Setting::get('show_instagram_nav', true),
            'show_tiktok_nav' => Setting::get('show_tiktok_nav', true),
            'show_whatsapp_nav' => Setting::get('show_whatsapp_nav', true),
            'show_whatsapp_floating' => Setting::get('show_whatsapp_floating', true),
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
            'whatsapp_message_template' => 'nullable|string',
            'instagram_url' => 'nullable|url|max:255',
            'tiktok_url' => 'nullable|url|max:255',
            'tiktok_name' => 'nullable|string|max:100',
            'shopee_url' => 'nullable|url|max:255',
        ], [
            'whatsapp_number.regex' => 'Format nomor WhatsApp harus diawali dengan angka 8 dan berisi 9-13 digit angka (contoh: 82112619691).',
            'instagram_url.url' => 'Format Link Instagram tidak valid. Pastikan diawali dengan http:// atau https://.',
            'tiktok_url.url' => 'Format Link TikTok tidak valid. Pastikan diawali dengan http:// atau https://.',
            'shopee_url.url' => 'Format Link Shopee tidak valid. Pastikan diawali dengan http:// atau https://.',
        ]);

        $fullWhatsapp = '62' . $request->input('whatsapp_number');

        Setting::set('whatsapp_number', $fullWhatsapp);
        Setting::set('admin_email', $request->input('admin_email'));
        Setting::set('whatsapp_message_template', $request->input('whatsapp_message_template'));
        Setting::set('instagram_url', $request->input('instagram_url'));
        Setting::set('tiktok_url', $request->input('tiktok_url'));
        Setting::set('tiktok_name', $request->input('tiktok_name'));
        Setting::set('shopee_url', $request->input('shopee_url'));
        Setting::set('show_instagram_nav', $request->boolean('show_instagram_nav'));
        Setting::set('show_tiktok_nav', $request->boolean('show_tiktok_nav'));
        Setting::set('show_whatsapp_nav', $request->boolean('show_whatsapp_nav'));
        Setting::set('show_whatsapp_floating', $request->boolean('show_whatsapp_floating'));

        // Clear cache so Laravel reads the new settings immediately
        try {
            \Illuminate\Support\Facades\Cache::flush();
        } catch (\Exception $e) {
            // Ignore if cache flushing fails
        }

        return redirect()->route('admin.settings.kontak')
            ->with('success', 'Kontak WhatsApp, Email, dan Media Sosial berhasil diperbarui!');
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
                    $path = \App\Helpers\ImageHelper::storeCompressed($file, 'hero', 1600, 80);
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

    public function updateAnnouncementText(Request $request)
    {
        $request->validate([
            'store_announcement_text' => 'required|string|max:255',
        ]);

        Setting::set('store_announcement_text', $request->input('store_announcement_text'));

        return redirect()->route('admin.settings.banner')
            ->with('success', 'Teks pengumuman top bar berhasil diperbarui!');
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
