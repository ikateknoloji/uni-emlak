<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeedTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
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

        foreach ($types as $type) {
            DB::table('deed_types')->insert([
                'name' => $type,
                'slug' => Str::slug($type),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
