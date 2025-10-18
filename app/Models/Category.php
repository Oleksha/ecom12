<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
}
