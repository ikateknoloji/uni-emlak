<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ImageHelper;
use App\Models\Listing;
use App\Models\ListingImage;


class ListingImageFactory extends Factory
{
    protected $model = ListingImage::class;

    public function definition(): array
    {
        // Kaynak klasördeki dosyaları oku (örneğin "ilan_images")
        $sourceFolder = 'ilan_images';
        $files = Storage::disk('public')->files($sourceFolder);

        if (empty($files)) {
            throw new \Exception("Kaynak klasörde resim bulunamadı: " . $sourceFolder);
        }

        // Rastgele bir dosya seç
        $randomFile = $files[array_rand($files)];
        // Dosya içeriğini al
        $upload = Storage::disk('public')->get($randomFile);

        // Helper aracılığıyla resmi işle ve geçici olarak kaydet
        $record = ImageHelper::processAndStoreTemporary($upload, 80);

        return [
            'listing_id'         => Listing::factory(),
            'image_path'         => $record['image_path'],
            'medium_image_path'  => $record['medium_image_path'],
            'thumbnail_path'     => $record['thumbnail_path'],
            'width'              => $record['width'],
            'height'             => $record['height'],
            'main_image'         => false,
            'order_number'       => 0,
        ];
    }
}

