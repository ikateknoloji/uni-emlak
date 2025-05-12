<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ListingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Kiralık', 'Satılık'];

        foreach ($types as $type) {
            DB::table('listing_types')->insert([
                'name' => $type,
                'slug' => Str::slug($type),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
