<?php

namespace App\Services\Admin;

use App\Models\AdminsRole;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BrandService
{
    public function brands(): array
    {
        $brands = Brand::get();
        $admin = Auth::guard('admin')->user();
        $status = 'success';
        $message = '';
        $productsModule = [];
        // Admin has full access
        if ($admin->role == 'admin') {
            $brandsModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1,
            ];
        } else {
            $brandsModuleCount = AdminsRole::where([
                'subadmin_id' => $admin->id,
                'module' => 'brand',
            ])->count();
            if ($brandsModuleCount > 0) {
                $status = 'error';
                $message = 'This feature is restricted for you.';
            } else {
                $brandsModule = AdminsRole::where([
                    'subadmin_id' => $admin->id,
                    'module' => 'brand',
                ])->first()->toArray();
            }
        }
        return [
            'brands' => $brands,
            'brandsModule' => $brandsModule,
            'status' => $status,
            'message' => $message,
        ];
    }

    public function updateBrandStatus($data): int
    {
        $status = ($data['status'] == 'Active') ? 0 : 1;
        Brand::where('id', $data['brand_id'])->update(['status' => $status]);
        return $status;
    }

    public function deleteBrand(string $id): array
    {
        Brand::where('id', $id)->delete();
        $message = 'Brand deleted successfully.';
        return ['message' => $message];
    }

    public function addEditBrand(Request $request): string
    {
        $data = $request->all();

        if (isset($data['id']) && $data['id'] != '') {
            // Изменение бренда
            $brand = Brand::find($data['id']);
            $message = 'Бренд обновлен!';
        } else {
            // Добавление бренда
            $brand = new Brand();
            $message = 'Бренд создан!';
        }

        // Загрузка изображения бренда
        if ($request->hasFile('image')) {
            $image_tmp = $request->file('image');
            if ($image_tmp->isValid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image_tmp);
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111, 9999) . '.' . $extension;
                $imagePath = 'front/images/brands/' . $imageName;
                $image->save($imagePath);
                $brand->image = $imageName;
            }
        }
        // Загрузка логотипа бренда
        if ($request->hasFile('logo')) {
            $image_logo = $request->file('logo');
            if ($image_logo->isValid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image_logo);
                $extension = $image_logo->getClientOriginalExtension();
                $logoName = rand(111, 9999) . '.' . $extension;
                $logoPath = 'front/images/logos/' . $logoName;
                $image->save($logoPath);
                $brand->logo = $logoName;
            }
        }
        // Форматирование имени и URL
        $data['name'] = str_replace('-', ' ', mb_convert_case(mb_strtolower($data['name']), MB_CASE_TITLE, "UTF-8"));
        $data['url'] = str_replace(' ', '-', mb_strtolower($data['url']));

        $brand->name = $data['name'];

        // Скидка по умолчанию
        if (empty($data['brand_discount']))  $data['brand_discount'] = 0;

        $brand->discount = $data['brand_discount'];
        $brand->description = $data['description'];
        $brand->url = $data['url'];
        $brand->meta_title = $data['meta_title'];
        $brand->meta_description = $data['meta_description'];
        $brand->meta_keywords = $data['meta_keywords'];

        // Menu Status
        if (!empty($data['menu_status'])) {
            $brand->menu_status = 1;
        } else {
            $brand->menu_status = 0;
        }

        // Статус по умолчанию
        $brand->status = 1;

        $brand->save();

        return $message;
    }
}
