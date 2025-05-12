<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Listing;
use App\Models\ListingDetail;
use App\Models\ListingImage;

class ListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Listing::factory()->count(250)->create()->each(function (Listing $listing) {
            // İlgili detayları oluştur
            ListingDetail::factory()->create([
                'listing_id' => $listing->id,
            ]);

            // Rastgele sayıda görüntü oluştur
            ListingImage::factory()->count(rand(3, 5))->create([
                'listing_id' => $listing->id,
            ]);
        });
    }
}
