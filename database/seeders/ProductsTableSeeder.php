<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menTShirtsCategory = Category::where('name', 'Мужские футболки')->first();
        if ($menTShirtsCategory) {
            Product::create([
                'category_id' => $menTShirtsCategory->id,
                'brand_id' => 1,
                'admin_id' => 1,
                'admin_role' => 'admin',
                'product_name' => 'Синяя футболка',
                'product_code' => 'BCT001',
                'product_color' => 'Темно синий',
                'family_color' => 'Синий',
                'group_code' => 'BCT000',
                'product_price' => 1000,
                'product_discount' => 10,
                'product_discount_amount' => 100,
                'product_applied_on' => 'product',
                'product_gst' => 12,
                'final_price' => 900,
                'main_image' => '',
                'product_weight' => 500,
                'product_video' => '',
                'description' => 'Тестовый продукт',
                'wash_care' => '',
                'search_keywords' => '',
                'fabric' => '',
                'pattern' => '',
                'sleeve' => '',
                'fit' => '',
                'occasion' => '',
                'stock' => 10,
                'sort' => 1,
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => '',
                'is_featured' => 'No',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            Product::create([
                'category_id' => $menTShirtsCategory->id,
                'brand_id' => 1,
                'admin_id' => 1,
                'admin_role' => 'admin',
                'product_name' => 'Красная футболка',
                'product_code' => 'RCT001',
                'product_color' => 'Красный',
                'family_color' => 'Красный',
                'group_code' => 'BT000',
                'product_price' => 2000,
                'product_discount' => 0,
                'product_discount_amount' => 0,
                'product_applied_on' => '',
                'product_gst' => 12,
                'final_price' => 2000,
                'main_image' => '',
                'product_weight' => 400,
                'product_video' => '',
                'description' => 'Тестовый продукт',
                'wash_care' => '',
                'search_keywords' => '',
                'fabric' => '',
                'pattern' => '',
                'sleeve' => '',
                'fit' => '',
                'occasion' => '',
                'stock' => 10,
                'sort' => 2,
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => '',
                'is_featured' => 'Yes',
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
