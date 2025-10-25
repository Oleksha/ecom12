<?php

namespace App\Services\Admin;

use App\Models\AdminsRole;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function products(): array
    {
        $products = Product::get();

        // Set Admin/Subadmin Permissions for Products
        $productsModuleCount = AdminsRole::where([
            'subadmin_id' => Auth::guard('admin')->user()->id,
            'module' => 'products',
        ])->count();
        $status = 'success';
        $message = '';
        $productsModule = [];
        if (Auth::guard('admin')->user()->role == 'admin') {
            $productsModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1,
            ];
        } elseif ($productsModuleCount == 0) {
            $status = 'error';
            $message = 'This feature is restricted for you!';
        } else {
            $productsModule = AdminsRole::where([
                'subadmin_id' => Auth::guard('admin')->user()->id,
                'module' => 'products',
            ])->first()->toArray();
        }
        return [
            'products' => $products,
            'productsModule' => $productsModule,
            'status' => $status,
            'message' => $message,
        ];
    }

    public function updateProductStatus($data): int
    {
        $status = ($data['status'] == 'Active') ? 0 : 1;
        Product::where('id', $data['product_id'])->update(['status' => $status]);
        return $status;
    }

    public function deleteProduct(string $id): array
    {
        Product::where('id', $id)->delete();
        $message = 'Product deleted successfully!';
        return ['message' => $message];
    }
}
