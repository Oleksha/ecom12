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
}
