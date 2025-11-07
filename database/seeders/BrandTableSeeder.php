<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brandRecords = [
            ['name' => 'Arrow', 'image' => '', 'logo' => '', 'discount' => 0, 'description' => '', 'url' => 'arrow', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'status' => 1],
            ['name' => 'Gar', 'image' => '', 'logo' => '', 'discount' => 0, 'description' => '', 'url' => 'gap', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'status' => 1],
            ['name' => 'Lee', 'image' => '', 'logo' => '', 'discount' => 0, 'description' => '', 'url' => 'lee', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'status' => 1],
            ['name' => 'Monte Carlo', 'image' => '', 'logo' => '', 'discount' => 0, 'description' => '', 'url' => 'monte-carlo', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'status' => 1],
            ['name' => 'Peter England', 'image' => '', 'logo' => '', 'discount' => 0, 'description' => '', 'url' => 'peter-england', 'meta_title' => '', 'meta_description' => '', 'meta_keywords' => '', 'status' => 1],
        ];
        Brand::insert($brandRecords);
    }
}
