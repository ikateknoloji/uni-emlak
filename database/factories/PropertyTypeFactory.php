<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyType>
 */
class PropertyTypeFactory extends Factory
{
    protected $model = PropertyType::class;

    protected static array $types = ['Konut', 'Arsa', 'Ofis'];

    public function definition(): array
    {
        $name = static::$types[array_shift(static::$types)];
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
