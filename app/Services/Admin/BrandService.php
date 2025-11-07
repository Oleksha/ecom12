<?php

namespace App\Services\Admin;

use App\Models\AdminsRole;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;

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
                'module' => 'brands',
            ])->count();
            if ($brandsModuleCount > 0) {
                $status = 'error';
                $message = 'This feature is restricted for you.';
            } else {
                $brandsModule = AdminsRole::where([
                    'subadmin_id' => $admin->id,
                    'module' => 'brands',
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
}
