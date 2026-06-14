<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Store and compress an uploaded image.
     * Resizes to max width while maintaining aspect ratio.
     * Returns the stored path.
     */
    public static function storeCompressed(UploadedFile $file, string $directory, int $maxWidth = 1200, int $quality = 80): ?string
    {
        if (!$file->isValid()) return null;

        $origExtension = strtolower($file->getClientOriginalExtension());
        
        // Use webp if GD is loaded and supports it, otherwise fallback to original extension
        $useWebp = extension_loaded('gd') && function_exists('imagewebp');
        $targetExtension = $useWebp ? 'webp' : ($origExtension === 'png' ? 'png' : 'jpg');

        $filename = uniqid() . '_' . time() . '.' . $targetExtension;
        $path = $directory . '/' . $filename;

        // Try to compress using GD library
        if (extension_loaded('gd')) {
            $image = self::createImageFromFile($file->getRealPath(), $origExtension);
            
            if ($image) {
                $origWidth = imagesx($image);
                $origHeight = imagesy($image);

                // Only resize if larger than maxWidth
                if ($origWidth > $maxWidth) {
                    $ratio = $maxWidth / $origWidth;
                    $newWidth = $maxWidth;
                    $newHeight = (int) ($origHeight * $ratio);

                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    
                    // Support transparency for PNG/WebP
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);

                    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                    imagedestroy($image);
                    $image = $resized;
                } else {
                    // Even if not resizing, preserve alpha transparency for the source
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                }

                // Save to temp file
                $tempPath = tempnam(sys_get_temp_dir(), 'img_');
                
                if ($targetExtension === 'webp') {
                    imagewebp($image, $tempPath, $quality);
                } elseif ($targetExtension === 'png') {
                    imagepng($image, $tempPath, min(9, (int)(9 - ($quality / 100) * 9)));
                } else {
                    imagejpeg($image, $tempPath, $quality);
                }

                imagedestroy($image);

                // Store to public disk
                Storage::disk('public')->put($path, file_get_contents($tempPath));
                unlink($tempPath);

                return $path;
            }
        }

        // Fallback: store without compression
        return $file->storeAs($directory, $filename, 'public');
    }

    private static function createImageFromFile(string $filePath, string $extension)
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                return @imagecreatefromjpeg($filePath);
            case 'png':
                return @imagecreatefrompng($filePath);
            case 'webp':
                if (function_exists('imagecreatefromwebp')) {
                    return @imagecreatefromwebp($filePath);
                }
                return null;
            default:
                return null;
        }
    }
}
