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

        $extension = strtolower($file->getClientOriginalExtension());
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $path = $directory . '/' . $filename;

        // Try to compress using GD library
        if (extension_loaded('gd')) {
            $image = self::createImageFromFile($file->getRealPath(), $extension);
            
            if ($image) {
                $origWidth = imagesx($image);
                $origHeight = imagesy($image);

                // Only resize if larger than maxWidth
                if ($origWidth > $maxWidth) {
                    $ratio = $maxWidth / $origWidth;
                    $newWidth = $maxWidth;
                    $newHeight = (int) ($origHeight * $ratio);

                    $resized = imagecreatetruecolor($newWidth, $newHeight);
                    
                    // Preserve transparency for PNG
                    if ($extension === 'png') {
                        imagealphablending($resized, false);
                        imagesavealpha($resized, true);
                    }

                    imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                    imagedestroy($image);
                    $image = $resized;
                }

                // Save to temp file
                $tempPath = tempnam(sys_get_temp_dir(), 'img_');
                
                switch ($extension) {
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($image, $tempPath, $quality);
                        break;
                    case 'png':
                        imagepng($image, $tempPath, min(9, (int)(9 - ($quality / 100) * 9)));
                        break;
                    case 'webp':
                        if (function_exists('imagewebp')) {
                            imagewebp($image, $tempPath, $quality);
                        } else {
                            imagejpeg($image, $tempPath, $quality);
                            $path = str_replace('.webp', '.jpg', $path);
                        }
                        break;
                    default:
                        imagejpeg($image, $tempPath, $quality);
                        break;
                }

                imagedestroy($image);

                // Store to public disk
                Storage::disk('public')->put($path, file_get_contents($tempPath));
                unlink($tempPath);

                return $path;
            }
        }

        // Fallback: store without compression
        return $file->store($directory, 'public');
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
