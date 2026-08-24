<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageCompressor
{
    public static function compress(UploadedFile $file, string $directory, int $maxDimension = 1600, int $quality = 80): string
    {
        $mime = strtolower($file->getMimeType() ?? '');

        if (!in_array($mime, ['image/jpeg', 'image/jpg', 'image/webp'], true)) {
            throw new RuntimeException('Format gambar tidak didukung untuk kompresi: ' . $mime);
        }

        $isWebp = $mime === 'image/webp';

        try {
            $source = $isWebp
                ? @imagecreatefromwebp($file->getRealPath())
                : @imagecreatefromjpeg($file->getRealPath());

            if ($source === false) {
                throw new RuntimeException('Gagal membaca gambar');
            }

            if (!$isWebp && function_exists('exif_read_data')) {
                $exif = @exif_read_data($file->getRealPath());
                $orientation = is_array($exif) ? ($exif['Orientation'] ?? 1) : 1;

                $source = match ($orientation) {
                    3 => imagerotate($source, 180, 0),
                    6 => imagerotate($source, -90, 0),
                    8 => imagerotate($source, 90, 0),
                    default => $source,
                };
            }

            $width = imagesx($source);
            $height = imagesy($source);

            if ($width > $maxDimension || $height > $maxDimension) {
                $scale = min($maxDimension / $width, $maxDimension / $height);
                $newWidth = max(1, (int) round($width * $scale));
                $newHeight = max(1, (int) round($height * $scale));

                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                if ($source instanceof \GdImage) {
                    imagedestroy($source);
                }
                $source = $resized;
            }

            ob_start();
            $saved = $isWebp
                ? imagewebp($source, null, $quality)
                : imagejpeg($source, null, $quality);

            if ($source instanceof \GdImage) {
                imagedestroy($source);
            }

            if (!$saved) {
                throw new RuntimeException('Gagal mengompres gambar');
            }

            $binary = ob_get_clean();
        } catch (\Throwable $e) {
            throw new RuntimeException('Gagal mengompres gambar: ' . $e->getMessage(), 0, $e);
        }

        $filename = Str::random(40) . ($isWebp ? '.webp' : '.jpg');
        $path = trim($directory, '/') . '/' . $filename;

        Storage::disk('public')->put($path, $binary);

        return $path;
    }
}
