<?php

namespace App\Services\Admin;

use App\Models\AdminsRole;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CategoryService
{
    public function categories(): array
    {
        $categories = Category::with('parent_category')->get();
        $admin = Auth::guard('admin')->user();
        $categoriesModuleCount = AdminsRole::where([
            'subadmin_id' => $admin->id,
            'module' => 'categories'
        ])->count();
        $status = 'success';
        $message = '';
        $categoriesModule = [];

        // Admin has full access
        if ($admin->role == 'admin') {
            $categoriesModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1,
            ];
        } elseif ($categoriesModuleCount == 0) {
            $status = 'error';
            $message = 'Эта функция ограничена для вас!';
        } else {
            $categoriesModule = AdminsRole::where([
                'subadmin_id' => $admin->id,
                'module' => 'categories'
            ])->first()->toArray();
        }
        return [
            'categories' => $categories,
            'categoriesModule' => $categoriesModule,
            'status' => $status,
            'message' => $message,
        ];
    }

    public function addEditCategory(Request $request): string
    {
        $data = $request->all();
        if (isset($data['id']) && $data['id'] != '') {
            // Edit Category
            $category = Category::find($data['id']);
            $message = 'Category updated successfully!';
        } else {
            // Add Category
            $category = new Category();
            $message = 'Category added successfully!';
        }

        // Save parent_id (null for Main Category)
        $category->parent_id = !empty($data['parent_id']) ? $data['parent_id'] : null;

        // Upload Category Image
        if ($request->hasFile('category_image')) {
            $image_tmp = $request->file('category_image');
            if ($image_tmp->isValid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image_tmp);
                $extension = $image_tmp->getClientOriginalExtension();
                $filename = rand(111, 9999) . '.' . $extension;
                $image_path = 'front/images/categories/' . $filename;
                $data['image'] = $filename;
                $image->save($image_path);
                $category->image = $data['image'];
            }
        }

        // Upload Size Chart
        if ($request->hasFile('size_chart')) {
            $size_chart_tmp = $request->file('size_chart');
            if ($size_chart_tmp->isValid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($size_chart_tmp);
                $size_chart_extension = $size_chart_tmp->getClientOriginalExtension();
                $size_chart_filename = rand(111, 9999) . '.' . $size_chart_extension;
                $size_chart_image_path = 'front/images/size-charts/' . $size_chart_filename;
                $data['size_chart'] = $size_chart_filename;
                $image->save($size_chart_image_path);
                $category->size_chart = $data['size_chart'];
            }
        }

        // Format name and URL
        $data['category_name'] = str_replace('-', ' ', mb_convert_case(mb_strtolower($data['category_name']), MB_CASE_TITLE, "UTF-8"));
        $data['url'] = str_replace(' ', '-', mb_strtolower($data['url']));

        $category->name = $data['category_name'];

        // Discount default
        if (empty($data['category_discount'])) {
            $data['category_discount'] = 0;
        }

        $category->discount = $data['category_discount'];
        $category->description = $data['description'];
        $category->url = $data['url'];
        $category->meta_title = $data['meta_title'];
        $category->meta_description = $data['meta_description'];
        $category->meta_keywords = $data['meta_keywords'];

        // Menu status
        if (!empty($data['menu_status'])) {
            $category->menu_status = 1;
        } else {
            $category->menu_status = 0;
        }

        // Status default
        $category->status = 1;
        $category->save();
        return $message;
    }

    public function updateCategoryStatus(array $data): int
    {
        $status = ($data['status'] == 'Active') ? 0 : 1;
        Category::where('id', $data['category_id'])->update(['status' => $status]);
        return $status;
    }

    public function deleteCategory(string $id): array
    {
        Category::where('id', $id)->delete();
        $message = 'Category deleted successfully!';
        return ['message' => $message];
    }

    public function deleteCategoryImage($categoryId): array
    {
        $categoryImage = Category::where('id', $categoryId)->value('image');
        if ($categoryImage) {
            $categoryImagePath = 'front/images/categories/' . $categoryImage;
            if (file_exists(public_path($categoryImagePath))) {
                unlink(public_path($categoryImagePath));
            }
            Category::where('id', $categoryId)->update(['image' => null]);
            return ['status' => true, 'message' => 'Category image deleted successfully!'];
        }
        return ['status' => false, 'message' => 'Category image not found!'];
    }

    public function deleteSizeChartImage(mixed $categoryId): array
    {
        $sizeChartImage = Category::where('id', $categoryId)->value('size_chart');
        if ($sizeChartImage) {
            $sizeChartImagePath = 'front/images/size-charts/' . $sizeChartImage;
            if (file_exists(public_path($sizeChartImagePath))) {
                unlink(public_path($sizeChartImagePath));
            }
            Category::where('id', $categoryId)->update(['size_chart' => null]);
            return ['status' => true, 'message' => 'Size Chart image deleted successfully!'];
        }
        return ['status' => false, 'message' => 'Size Chart image not found!'];
    }
}
