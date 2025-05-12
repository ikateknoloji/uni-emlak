<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ListingDetail;
use App\Models\Listing;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListingDetail>
 */
class ListingDetailFactory extends Factory
{
    protected $model = ListingDetail::class;

    public function definition(): array
    {
        return [
            'listing_id' => Listing::inRandomOrder()->first()->id,
            'content' => function () {
                $blocks = [];

                // Başlık
                $blocks[] = '<h1>' . $this->faker->sentence(rand(3, 6)) . '</h1>';

                // Alt başlıklar + paragraflar
                foreach (range(1, rand(2, 4)) as $_) {
                    $blocks[] = '<h2>' . $this->faker->sentence(rand(4, 8)) . '</h2>';
                    foreach ($this->faker->paragraphs(rand(2, 5)) as $p) {
                        $blocks[] = '<p>' . $p . '</p>';
                    }
                }

                return implode("\n", $blocks);
            }
        ];
    }
}
