<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Listing;
use App\Models\Neighborhood;
use App\Models\DeedType;
use App\Models\PropertyType;
use App\Models\ListingType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
      $price = $this->faker->numberBetween(100_000, 10_000_000);
      $squareMeters = $this->faker->numberBetween(50, 500);

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'neighborhood_id' => Neighborhood::inRandomOrder()->value('id'),
						'deed_type_id' => DeedType::inRandomOrder()->value('id'),
            'property_type_id' => PropertyType::inRandomOrder()->first()->id,
            'listing_type_id' => ListingType::inRandomOrder()->first()->id,
            'block_number' => $this->faker->numberBetween(1, 999),
            'parcel_number' => $this->faker->numberBetween(1, 9999),
            'price' => $price,
            'square_meters' => $squareMeters,
            'price_per_square_meter' => round($price / $squareMeters, 2),
            'is_loan_eligible' => $this->faker->boolean(),
            'publication_status' => 'published',
            'listing_status' => 'available',
            'full_address' => $this->faker->address(),
            'listing_date' => $this->faker->date(),
            'update_date' => $this->faker->date(),
        ];
    }
}
