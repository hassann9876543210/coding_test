<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('variants')->insert([
            [
                'id' => 1,
                'title' => 'Color',
                'description' => "Variant's Color",
            ],
            [
                'id' => 2,
                'title' => 'Size',
                'description' => "Variant's Size",
            ],
            [
                'id' => 3,
                'title' => 'Style',
                'description' => "Variant's Style",
            ],
        ]);
    }
}
