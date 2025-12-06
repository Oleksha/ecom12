<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    public function parent_category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'parent_id')
            ->select('id', 'name', 'url')
            ->where('status', 1)
            ->orderBy('id', 'ASC');
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->where('status', 1);
    }

    public static function getCategories($type): array
    {
        $getCategories = Category::with(['subcategories.subcategories'])
            ->whereNull('parent_id')
            ->where('status', 1);
        if ($type == 'Front') {
            $getCategories = $getCategories->where('menu_status', 1);
        }
        return $getCategories->get()->toArray();
    }

    public static function categoryDetails($url): ?array
    {
        $category = self::with(['subcategories' => function ($query) {
                $query->with('subcategories:id,parent_id,name'); // Nested subcategories
            }])
            ->where('url', $url)
            ->where('status', 1)
            ->first();

        if (!$category) return null;

        $categoryIds = [$category->id];

        // Add first-level subcategories
        foreach ($category->subcategories as $subcategory) {
            $categoryIds[] = $subcategory->id;

            // Add second-level subcategories (sub-subcategories)
            foreach ($subcategory->subcategories as $subsubcategory) {
                $categoryIds[] = $subsubcategory->id;
            }
        }

        $breadcrumbs = '<div class="px-2 py-1 mb-1" style="background-color: #f9f9f9">';
        $breadcrumbs .= '<nav aria-label="breadcrumbs">';
        $breadcrumbs .= '<ol class="breadcrumb mb-0" style="background-color: #f9f9f9; --bs-breadcrumb-divider:\'>\';">';
        $breadcrumbs .= '<li class="breadcrumb-item"><a href="' . url('/') . '" class="text-dark text-decoration-none">Главная</a></li>';
        if ($category->parent_id == 0) {
            $breadcrumbs .= '<li class="breadcrumb-item fw-bold active" aria-current="page">' . $category->name . '</li>';
        } else {
            $parentCategory = self::select('name', 'url')
                ->where('id', $category->parent_id)
                ->first();
            if ($parentCategory) {
                $breadcrumbs .= '<li class="breadcrumb-item fw-bold"><a href="' . url($parentCategory->url) . '" class="text-dark text-decoration-none">' . $parentCategory->name . '</a></li>';
            }
            $breadcrumbs .= '<li class="breadcrumb-item fw-bold active" aria-current="page">' . $category->name . '</li>';
        }
        $breadcrumbs .= '</ol>';
        $breadcrumbs .= '</nav>';
        $breadcrumbs .= '</div>';

        return [
            'categoryIds' => $categoryIds,
            'categoryDetails' => $category,
            'breadcrumbs' => $breadcrumbs
        ];
    }
}
