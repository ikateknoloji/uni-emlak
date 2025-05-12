<?php

namespace Database\Factories;

use App\Models\DeedType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DeedType>
 */
class DeedTypeFactory extends Factory
{
    protected $model = DeedType::class;

    protected static array $types = [
        'Hisseli Tapu',
        'Müstakil Parsel',
        'Tahsis',
        'Zilliyet',
        'Belirtilmemiş',
        'Yabancıdan',
        'Tapu yok',
        'Kıbrıs Tapulu',
        'Kooperatiften Hisseli Tapu',
        'Müstakil Tapulu',
        'İntifa Hakkı Tesisli',
    ];

    public function definition(): array
    {
        $name = static::$types[array_shift(static::$types)];
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
