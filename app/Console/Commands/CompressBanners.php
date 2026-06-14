<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

#[Signature('app:compress-banners')]
#[Description('Compress existing large PNG/JPG banners to WebP format')]
class CompressBanners extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting banner compression...');

        if (!extension_loaded('gd') || !function_exists('imagewebp')) {
            $this->error('GD extension or imagewebp is not available. Aborting.');
            return 1;
        }

        $banners = Setting::get('hero_banners', []);
        if (empty($banners)) {
            $this->warn('No custom hero banners found in settings.');
            return 0;
        }

        $updatedBanners = [];
        $hasChanges = false;

        foreach ($banners as $bannerPath) {
            $extension = strtolower(pathinfo($bannerPath, PATHINFO_EXTENSION));
            
            if ($extension === 'webp') {
                // Already WebP, keep it
                $updatedBanners[] = $bannerPath;
                continue;
            }

            $disk = Storage::disk('public');
            if (!$disk->exists($bannerPath)) {
                $this->warn("File not found: {$bannerPath}, skipping.");
                $updatedBanners[] = $bannerPath;
                continue;
            }

            $absolutePath = $disk->path($bannerPath);
            $this->line("Compressing: {$bannerPath} (" . round(filesize($absolutePath) / 1024) . " KB)...");

            // Load image using GD
            $image = null;
            if ($extension === 'jpg' || $extension === 'jpeg') {
                $image = @imagecreatefromjpeg($absolutePath);
            } elseif ($extension === 'png') {
                $image = @imagecreatefrompng($absolutePath);
            }

            if (!$image) {
                $this->error("Failed to load image {$bannerPath}, skipping.");
                $updatedBanners[] = $bannerPath;
                continue;
            }

            // Define WebP path
            $newPath = preg_replace('/\.(png|jpg|jpeg)$/i', '.webp', $bannerPath);
            $newAbsolutePath = $disk->path($newPath);

            // Compress & save to WebP
            imagealphablending($image, false);
            imagesavealpha($image, true);

            $success = imagewebp($image, $newAbsolutePath, 80); // 80% quality
            imagedestroy($image);

            if ($success) {
                $this->info("Saved as: {$newPath} (" . round(filesize($newAbsolutePath) / 1024) . " KB)");
                // Delete original png/jpg file
                $disk->delete($bannerPath);
                $updatedBanners[] = $newPath;
                $hasChanges = true;
            } else {
                $this->error("Failed to save WebP for {$bannerPath}");
                $updatedBanners[] = $bannerPath;
            }
        }

        if ($hasChanges) {
            Setting::set('hero_banners', $updatedBanners);
            // Clear settings cache
            Cache::forget('setting.hero_banners');
            $this->info('Successfully updated settings database with compressed WebP banners!');
        } else {
            $this->info('No banners needed compression.');
        }

        return 0;
    }
}
