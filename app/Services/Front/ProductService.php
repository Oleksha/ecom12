<?php

namespace App\Services\Front;

use App\Models\Category;
use App\Models\Product;

class ProductService
{
    public function getCategoryListingData($url): array
    {
        $categoryInfo = Category::categoryDetails($url);
        $products = Product::with('product_images')
            ->whereIn('category_id', $categoryInfo['categoryIds'])
            ->where('status', 1)
            ->paginate(30);

        return [
            'categoryDetails' => $categoryInfo['categoryDetails'],
            'categoryProducts' => $products,
        ];
    }
}
