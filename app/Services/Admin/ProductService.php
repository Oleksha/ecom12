<?php

namespace App\Services\Admin;

use App\Models\AdminsRole;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductService
{
    public function products(): array
    {
        $products = Product::with('category')->get();

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

    public function addEditProduct(Request $request): string
    {
        $data = $request->all();

        if (isset($data['id']) && $data['id'] != '') {
            $product = Product::find($data['id']);
            $message = 'Product updated successfully!';
        } else {
            $product = new Product();
            $message = 'Product added successfully!';
        }

        $product->admin_id = Auth::guard('admin')->user()->id;
        $product->admin_role = Auth::guard('admin')->user()->role;

        $product->category_id = $data['category_id'];
        $product->product_name = $data['product_name'];
        $product->product_code = $data['product_code'];
        $product->product_color = $data['product_color'];
        $product->family_color = $data['family_color'];
        $product->group_code = $data['group_code'];
        $product->product_weight = $data['product_weight'] ?? 0;
        $product->product_price = $data['product_price'];
        $product->product_gst = $data['product_gst'] ?? 0;
        $product->product_discount = $data['product_discount'] ?? 0;
        $product->is_featured = $data['is_featured'] ?? 'No';

        // Calculate discount & final price
        if (!empty($data['product_discount']) && $data['product_discount'] > 0) {
            $product->product_applied_on = 'product';
            $product->product_discount_amount = ($data['product_price'] * $data['product_discount']) / 100;
        } else {
            $getCategoryDiscount = Category::select('discount')
                ->where('id', $data['category_id'])->first();
            if ($getCategoryDiscount && $getCategoryDiscount->discount > 0) {
                $product->product_applied_on = 'category';
                $product->product_discount = $getCategoryDiscount->discount;
                $product->product_discount_amount = ($data['product_price'] * $getCategoryDiscount->discount) / 100;
            } else {
                $product->product_applied_on = '';
                $product->product_discount_amount = 0;
            }
        }

        $product->final_price = $data['product_price'] - $product->product_discount_amount;

        // Optional fields
        $product->description = $data['product_description'] ?? '';
        $product->wash_care = $data['wash_care'] ?? '';
        $product->search_keywords = $data['search_keywords'] ?? '';
        $product->meta_title = $data['meta_title'] ?? '';
        $product->meta_description = $data['meta_description'] ?? '';
        $product->meta_keywords = $data['meta_keywords'] ?? '';
        $product->status = 1;

        $product->save();

        return $message;
    }
}
