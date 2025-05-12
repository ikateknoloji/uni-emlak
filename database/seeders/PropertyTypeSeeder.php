<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Arsa'];

        foreach ($types as $type) {
            DB::table('property_types')->insert([
                'name' => $type,
                'slug' => Str::slug($type),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
