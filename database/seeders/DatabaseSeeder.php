<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeedType;
use App\Models\ListingType;
use App\Models\PropertyType;
use App\Models\Listing;
use App\Models\ListingDetail;
use App\Models\ListingImage;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Listing::factory()->count(250)->create()->each(function (Listing $listing) {

            ListingDetail::factory()->create([
                'listing_id' => $listing->id,
            ]);

            ListingImage::factory()->count(rand(3, 5))->create([
                'listing_id' => $listing->id,
            ]);
        });
    }
}
