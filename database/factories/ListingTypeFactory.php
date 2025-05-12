<?php

namespace Database\Factories;

use App\Models\ListingType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListingType>
 */
class ListingTypeFactory extends Factory
{
    protected $model = ListingType::class;

    protected static array $types = ['Kiralık', 'Satılık'];

    public function definition(): array
    {
        $name = static::$types[array_shift(static::$types)];
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
