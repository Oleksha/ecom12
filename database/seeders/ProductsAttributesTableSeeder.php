<?php

namespace Database\Seeders;

use App\Models\ProductsAttribute;
use Illuminate\Database\Seeder;

class ProductsAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productAttributesRecords = [
            [
                'product_id' => 1,
                'sku' => 'BCT001-S',
                'size' => 'Small',
                'price' => 1000,
                'stock' => 10,
                'sort' => 1,
                'status' => 1,
            ],
            [
                'product_id' => 1,
                'sku' => 'BCT001-M',
                'size' => 'Medium',
                'price' => 1100,
                'stock' => 20,
                'sort' => 2,
                'status' => 1,
            ],
            [
                'product_id' => 1,
                'sku' => 'BCT001-L',
                'size' => 'Large',
                'price' => 1200,
                'stock' => 10,
                'sort' => 3,
                'status' => 1,
            ],
        ];
        ProductsAttribute::insert($productAttributesRecords);
    }
}
