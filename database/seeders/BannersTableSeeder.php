<?php

namespace Database\Seeders;

use App\Models\Banner;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bannerRecords = [
            ['id' => 1, 'type' => 'Slider', 'image' => 'carousel-1.jpg', 'link' => '', 'title' => 'Products on Sale', 'alt' => 'Products on Sale', 'sort' => 1, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'type' => 'Slider', 'image' => 'carousel-2.jpg', 'link' => '', 'title' => 'Flat 50% Off', 'alt' => 'Flat 50% Off', 'sort' => 2, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'type' => 'Slider', 'image' => 'carousel-3.jpg', 'link' => '', 'title' => 'Summer Sale', 'alt' => 'Summer Sale', 'sort' => 3, 'status' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];
        Banner::insert($bannerRecords);
    }
}
