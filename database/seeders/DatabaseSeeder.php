<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WilayahSeeder::class,
            BidangHukumSeeder::class,
            UserSeeder::class,
            AdvokatSeeder::class,
            OperasionalSeeder::class,
        ]);
    }
}
