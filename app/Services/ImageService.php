<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
  /**
   * Compress dan simpan gambar ke storage public
   * Target ukuran sekitar 200KB
   *
   * @param UploadedFile $file File yang diupload
   * @param string $folder Folder tujuan di storage/public
   * @param int $maxSizeKb Target ukuran maksimal dalam KB
   * @return string|null Path relatif di storage
   */
  public static function compressAndStore(UploadedFile $file, string $folder, int $maxSizeKb = 200): ?string
  {
    $extension = strtolower($file->getClientOriginalExtension());
    $mimeType = $file->getMimeType();

    // Baca gambar sesuai tipe
    $image = match (true) {
      str_contains($mimeType, 'jpeg'), str_contains($mimeType, 'jpg') => imagecreatefromjpeg($file->getRealPath()),
      str_contains($mimeType, 'png') => imagecreatefrompng($file->getRealPath()),
      str_contains($mimeType, 'webp') => imagecreatefromwebp($file->getRealPath()),
      str_contains($mimeType, 'gif') => imagecreatefromgif($file->getRealPath()),
      default => null,
    };

    if (!$image) {
      // Fallback: simpan tanpa compress jika format tidak didukung
      return $file->store($folder, 'public');
    }

    // Resize jika terlalu besar (max 1200px di sisi terpanjang)
    $width = imagesx($image);
    $height = imagesy($image);
    $maxDimension = 1200;

    if ($width > $maxDimension || $height > $maxDimension) {
      if ($width > $height) {
        $newWidth = $maxDimension;
        $newHeight = intval($height * ($maxDimension / $width));
      } else {
        $newHeight = $maxDimension;
        $newWidth = intval($width * ($maxDimension / $height));
      }

      $resized = imagecreatetruecolor($newWidth, $newHeight);

      // Preserve transparency for PNG
      if (str_contains($mimeType, 'png')) {
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
      }

      imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
      imagedestroy($image);
      $image = $resized;
    }

    // Generate filename
    $filename = $folder . '/' . uniqid() . '_' . time() . '.jpg';
    $absolutePath = Storage::disk('public')->path($filename);

    // Pastikan folder ada
    $dir = dirname($absolutePath);
    if (!is_dir($dir)) {
      mkdir($dir, 0755, true);
    }

    // Compress secara progresif sampai di bawah target
    $quality = 85;
    $minQuality = 20;

    do {
      imagejpeg($image, $absolutePath, $quality);
      $fileSize = filesize($absolutePath) / 1024; // KB

      if ($fileSize <= $maxSizeKb) {
        break;
      }

      $quality -= 10;
    } while ($quality >= $minQuality);

    // Jika masih terlalu besar, resize lagi lebih kecil
    if ($fileSize > $maxSizeKb && $quality <= $minQuality) {
      $currentWidth = imagesx($image);
      $currentHeight = imagesy($image);
      $scale = sqrt($maxSizeKb / $fileSize); // estimasi rasio pengecilan

      $newWidth = max(200, intval($currentWidth * $scale));
      $newHeight = max(200, intval($currentHeight * $scale));

      $smaller = imagecreatetruecolor($newWidth, $newHeight);
      imagecopyresampled($smaller, $image, 0, 0, 0, 0, $newWidth, $newHeight, $currentWidth, $currentHeight);
      imagejpeg($smaller, $absolutePath, 60);
      imagedestroy($smaller);
    }

    imagedestroy($image);

    $finalSize = round(filesize($absolutePath) / 1024, 1);
    \Log::info("Image compressed: {$filename} ({$finalSize}KB)");

    return $filename;
  }
}
