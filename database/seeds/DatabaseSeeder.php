<?php

use Database\Seeders\UserSeeder;
use Database\Seeders\VariantSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            UserSeeder::class,
            VariantSeeder::class,
        ]);
    }
}
