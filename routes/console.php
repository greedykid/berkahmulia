<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('images:optimize', function () {
    $this->info('Starting image optimization...');
    
    // Optimize hero banners
    $heroFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('hero');
    foreach ($heroFiles as $file) {
        $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($file);
        if (file_exists($fullPath) && strtolower(pathinfo($fullPath, PATHINFO_EXTENSION)) === 'webp') {
            $src = @imagecreatefromwebp($fullPath);
            if ($src) {
                $w = imagesx($src);
                $h = imagesy($src);
                if ($w > 800) {
                    $ratio = 800 / $w;
                    $newW = 800;
                    $newH = (int) ($h * $ratio);
                    $dst = imagecreatetruecolor($newW, $newH);
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
                    imagewebp($dst, $fullPath, 80);
                    imagedestroy($dst);
                    $this->info("Optimized hero banner: $file ($w x $h -> 800 x $newH)");
                }
                imagedestroy($src);
            }
        }
    }
    
    // Optimize products
    $productFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('products');
    foreach ($productFiles as $file) {
        $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($file);
        if (file_exists($fullPath)) {
            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $src = null;
            if ($ext === 'webp') {
                $src = @imagecreatefromwebp($fullPath);
            } elseif ($ext === 'jpg' || $ext === 'jpeg') {
                $src = @imagecreatefromjpeg($fullPath);
            } elseif ($ext === 'png') {
                $src = @imagecreatefrompng($fullPath);
            }
            
            if ($src) {
                $w = imagesx($src);
                $h = imagesy($src);
                if ($w > 800) {
                    $ratio = 800 / $w;
                    $newW = 800;
                    $newH = (int) ($h * $ratio);
                    $dst = imagecreatetruecolor($newW, $newH);
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
                    
                    if ($ext === 'webp') {
                        imagewebp($dst, $fullPath, 80);
                    } elseif ($ext === 'png') {
                        imagepng($dst, $fullPath, 7);
                    } else {
                        imagejpeg($dst, $fullPath, 80);
                    }
                    imagedestroy($dst);
                    $this->info("Optimized product image: $file ($w x $h -> 800 x $newH)");
                }
                imagedestroy($src);
            }
        }
    }
    
    $this->info('Image optimization completed!');
})->purpose('Optimize existing uploaded images to 800px max width');
