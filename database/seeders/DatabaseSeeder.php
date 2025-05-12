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
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Ragıp Lapçin',
            'email'    => 'admin@uniemlak.com',
            'password' => Hash::make('k701060K.'),
            'role'     => 'admin',
        ]);
    }
}
