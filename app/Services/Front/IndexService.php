<?php

namespace App\Services\Front;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class IndexService
{
    public function getHomePageBanners(): array
    {
        $homeSliderBanners = Banner::where('type', 'Slider')
            ->where('status', 1)
            ->orderBy('sort', 'desc')
            ->get()->toArray();

        $homeFixBanners = Banner::where('type', 'Fix')
            ->where('status', 1)
            ->orderBy('sort', 'desc')
            ->get()->toArray();

        $logoBanners = Banner::where('type', 'Logo')
            ->where('status', 1)
            ->orderByDesc('sort')
            ->get()->toArray();

        return compact('homeSliderBanners', 'homeFixBanners', 'logoBanners');
    }

    public function featuredProducts(): array
    {
        $featuredProducts = Product::select('id','category_id','product_name','product_applied_on','product_price','product_discount','final_price','group_code','main_image')
            ->with(['product_images'])
            ->where(['is_featured' => 'Yes', 'status' => 1])
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->limit(8)
            ->get()->toArray();
        return compact('featuredProducts');
    }

    public function newArrivalProducts(): array
    {
        $newArrivalProducts = Product::select('id','category_id','product_name','product_applied_on','product_price','product_discount','final_price','group_code','main_image')
            ->with(['product_images'])
            ->where('status', 1)
            ->where('stock', '>', 0)
            ->latest()
            ->limit(8)
            ->orderBy('sort')
            ->get()->toArray();
        return compact('newArrivalProducts');
    }

    public function homeCategories(): array
    {
        $categories = Category::select('id','name', 'image', 'url')
            ->whereNull('parent_id') // Извлекать только категории верхнего уровня (родительские)
            ->where('status', 1) // Только активные категории
            ->where('menu_status', 1) //Только категории, отмеченные для показа в меню/на главной странице
            ->get()
            ->map(function ($category) {
                $allCategoryIds = $this->getAllCategoryIds($category->id); // Получить эту категорию + идентификаторы ее подкатегорий
                $productCount = Product::whereIn('category_id', $allCategoryIds)
                    ->where('status', 1)
                    ->where('stock', '>', 0)
                    ->count(); // Подсчет активных и имеющихся на складе товаров на всех уровнях
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => $category->image,
                    'url' => $category->url,
                    'product_count' => $productCount, // Добавляем количество продуктов к каждой категории
                ];
            });
        return ['categories' => $categories->toArray()];
    }

    private function getAllCategoryIds($parentId): array
    {
        $categoryIds = [$parentId]; // Начать с текущей родительской категории
        $childIds = Category::where('parent_id', $parentId)
            ->where('status', 1)
            ->pluck('id'); // Получить идентификаторы дочерних категорий
        foreach ($childIds as $childId) {
            $categoryIds = array_merge($categoryIds, $this->getAllCategoryIds($childId)); // Рекурсивно для получения под-подкатегорий
        }
        return $categoryIds; // Вернуть все идентификаторы дочерних и под-дочерних категорий
    }
}
