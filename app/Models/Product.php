<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->with('parent_category');
    }

    public function product_images(): HasMany
    {
        return $this->hasMany(ProductsImage::class);
    }
}
