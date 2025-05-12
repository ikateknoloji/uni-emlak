<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\TemporaryListingImage;

class ImageHelper
{
    /**
     * Yüklenen resmi WebP formatına dönüştürüp,
     * medium ve thumbnail boyutlarını oluşturur, rastgele klasörde saklar
     * ve geçici listeye DB kaydını ekler.
     *
     * @param mixed $upload
     * @param int $quality
     * @return array
     */
    public static function processAndStoreTemporary($upload, $quality = 70)
    {
        // Resmin orijinal ölçülerinin alınması
        $originalImage = Image::read($upload);
        $originalWidth  = $originalImage->width();
        $originalHeight = $originalImage->height();
        $origRatio      = $originalHeight / $originalWidth;
        
        // Geçici klasör için rastgele bir isim oluşturuluyor
        $directory = "temporary_listing_images/" . Str::random(20);
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        // ---------- MEDIUM (800px genişlik) ----------
        $mediumWidth  = 800;
        $mediumHeight = round($mediumWidth * $origRatio);
        $scaleMedium      = max($mediumWidth / $originalWidth, $mediumHeight / $originalHeight);
        $mediumResizeW    = round($originalWidth * $scaleMedium);
        $mediumResizeH    = round($originalHeight * $scaleMedium);
        
        $mediumImage = clone $originalImage;
        $mediumImage->resize($mediumResizeW, $mediumResizeH);
        $cropX = round(($mediumResizeW - $mediumWidth) / 2);
        $cropY = round(($mediumResizeH - $mediumHeight) / 2);
        $mediumImage->crop($mediumWidth, $mediumHeight, $cropX, $cropY);
        
        // ---------- THUMBNAIL (300px genişlik) ----------
        $thumbWidth  = 300;
        $thumbHeight = round($thumbWidth * $origRatio);
        
        $scaleThumb   = max($thumbWidth / $originalWidth, $thumbHeight / $originalHeight);
        $thumbResizeW = round($originalWidth * $scaleThumb);
        $thumbResizeH = round($originalHeight * $scaleThumb);
        
        $thumbImage = clone $originalImage;
        $thumbImage->resize($thumbResizeW, $thumbResizeH);
        $cropXThumb = round(($thumbResizeW - $thumbWidth) / 2);
        $cropYThumb = round(($thumbResizeH - $thumbHeight) / 2);
        $thumbImage->crop($thumbWidth, $thumbHeight, $cropXThumb, $cropYThumb);
        
        // İşlemler her zaman WebP formatında olacak
        $extension = 'webp';
        
        // Dosya isimlerinin oluşturulması
        $originalName = Str::random(40) . '.' . $extension;
        $mediumName   = 'medium_' . Str::random(40) . '.' . $extension;
        $thumbName    = 'thumb_' . Str::random(40) . '.' . $extension;
        
        $originalPath = $directory . '/' . $originalName;
        $mediumPath   = $directory . '/' . $mediumName;
        $thumbPath    = $directory . '/' . $thumbName;
        
        // Dosyaların kaydedilmesi
        Storage::disk('public')->put(
            $originalPath,
            $originalImage->encodeByExtension($extension, quality: $quality)
        );
        Storage::disk('public')->put(
            $mediumPath,
            $mediumImage->encodeByExtension($extension, quality: $quality)
        );
        Storage::disk('public')->put(
            $thumbPath,
            $thumbImage->encodeByExtension($extension, quality: $quality)
        );
        
        // DB kaydı oluşturuluyor
        $tempImage = TemporaryListingImage::create([
            'image_path'         => $originalPath,
            'medium_image_path'  => $mediumPath,
            'thumbnail_path'     => $thumbPath,
        ]);
        
        return [
            'id'                => $tempImage->id,
            'image_path'        => $originalPath,
            'medium_image_path' => $mediumPath,
            'thumbnail_path'    => $thumbPath,
            'width'             => $originalWidth,
            'height'            => $originalHeight,
        ];
    }
    
    /**
     * Verilen geçici listing image id'sine ait kayıt üzerinden,
     * dosyaları public disk'ten siler ve DB kaydını siler.
     *
     * @param int $id
     * @return bool
     */
    public static function deleteTemporary($id)
    {
        $tempImage = TemporaryListingImage::find($id);
        if (!$tempImage) {
            return false;
        }
        
        $disk = Storage::disk('public');
        $paths = [
            'image_path'         => $tempImage->image_path,
            'medium_image_path'  => $tempImage->medium_image_path,
            'thumbnail_path'     => $tempImage->thumbnail_path,
        ];
        
        foreach ($paths as $path) {
            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }
        
        $tempImage->delete();
        return true;
    }
}
