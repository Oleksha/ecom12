<?php

namespace App\Services\Admin;

use App\Models\AdminsRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\ProductsImage;
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

        // Upload Main Image
        if (!empty($data['main_image_hidden'])) {
            $sourcePath = public_path('temp/' . $data['main_image_hidden']);
            $destinationPath = public_path('front/images/products/' . $data['main_image_hidden']);

            if (file_exists($sourcePath)) {
                @copy($sourcePath, $destinationPath);
                @unlink($sourcePath);
            }

            $product->main_image = $data['main_image_hidden'];
        }

        // Upload Product Video
        if (!empty($data['product_video_hidden'])) {
            $sourcePath = public_path('temp/' . $data['product_video_hidden']);
            $destinationPath = public_path('front/videos/products/' . $data['product_video_hidden']);

            if (file_exists($sourcePath)) {
                @copy($sourcePath, $destinationPath);
                @unlink($sourcePath);
            }

            $product->product_video = $data['product_video_hidden'];
        }

        $product->main_image = $request->main_image ?? $product->main_image;
        $product->product_video = $request->product_video ?? $product->product_video;

        $product->save();

        // Upload Alternate Images
        if (!empty($data['product_images'])) {
            // Ensure we have an array
            $imageFiles = is_array($data['product_images'])
                ? $data['product_images']
                : explode(',', $data['product_images']);

            // Remove any empty values
            $imageFiles = array_filter($imageFiles);

            foreach ($imageFiles as $index => $filename) {
                $sourcePath = public_path('temp/' . $filename);
                $destinationPath = public_path('front/images/products/' . $filename);

                if (file_exists($sourcePath)) {
                    @copy($sourcePath, $destinationPath);
                    @unlink($sourcePath);
                }

                ProductsImage::create([
                    'product_id' => $product->id,
                    'image' => $filename,
                    'sort' => $index,
                    'status' => 1,
                ]);
            }
        }

        // Add/Edit Product Attributes
        // Add Product Attributes
        $total_stock = 0;
        foreach ($data['sku'] as $key => $value) {
            if (!empty($value) && !empty($data['size'][$key]) && !empty($data['price'][$key])) {
                // SKU already exists check
                $attrCountSKU = ProductsAttribute::join('products', 'products.id', '=', 'products_attributes.product_id')->where('sku', $value)->count();
                if ($attrCountSKU > 0) {
                    $message = 'SKU already exists. Please add another SKU!';
                    return redirect()->back()->with('success_message', $message);
                }
                // Size already exists check
                $attrCountSize = ProductsAttribute::join('products', 'products.id', '=', 'products_attributes.product_id')
                    ->where(['product_id' => $product->id, 'size' => $data['size'][$key]])->count();
                if ($attrCountSize > 0) {
                    $message = 'Size already exists. Please add another Size!';
                    return redirect()->back()->with('success_message', $message);
                }
                if (empty($data['stock'][$key])) {
                    $data['stock'][$key] = 0;
                }
                $attribute = new ProductsAttribute();
                $attribute->product_id = $product->id;
                $attribute->sku = $value;
                $attribute->size = $data['size'][$key];
                $attribute->price = $data['price'][$key];
                if (!empty($data['stock'][$key])) {
                    $attribute->stock = $data['stock'][$key];
                }
                $attribute->sort = $data['sort'][$key];
                $attribute->status = 1;
                $attribute->save();
                $total_stock = $total_stock + $data['stock'][$key];
            }
        }

        // Edit Product Attributes
        if (isset($data['id']) && $data['id'] != '' && isset($data['attrId'])) {
            foreach ($data['attrId'] as $key => $attr) {
                if (!empty($attr)) {
                    $update_attr = [
                        'price' => $data['update_price'][$key],
                        'stock' => $data['update_stock'][$key],
                        'sort' => $data['update_sort'][$key],
                    ];
                    ProductsAttribute::where('id', $data['attrId'][$key])->update($update_attr);
                }
            }
        }

        // Update Product Stock on Edit Product
        if (isset($data['attrId'])) {
            foreach ($data['attrId'] as $attrKeyId => $attrIdDetails) {
                $proAttrUpdate = ProductsAttribute::find($attrIdDetails);
                $proAttrUpdate->stock = $data['update_stock'][$attrKeyId];
                $total_stock = $total_stock + $data['update_stock'][$attrKeyId];
            }
        }
        Product::where('id', $product->id)->update(['stock' => $total_stock]);

        return $message;
    }

    public function handleImageUpload($file): string
    {
        $imageName = time() . '.' . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('front/images/products'), $imageName);
        return $imageName;
    }

    public function handleVideoUpload($file)
    {
        $videoName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('front/videos/products'), $videoName);
        return $videoName;
    }

    public function deleteProductMainImage(string $id)
    {
        // Get product Main Image
        $product = Product::select('main_image')->where('id', $id)->first();

        if (!$product || !$product->main_image) {
            return 'No image found';
        }

        // Get Product Image Path
        $image_path = public_path('front/images/products/' . $product->main_image);

        // Delete Product Main Image if exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete Product Main Image from products table
        Product::where('id', $id)->update(['main_image' => null]);

        return 'Product main image has been deleted successfully!';
    }

    public function deleteProductVideo(string $id)
    {
        // Get Product Video
        $product = Product::select('product_video')->where('id', $id)->first();

        if (!$product || !$product->product_video) {
            return 'No video found';
        }

        // Get Product Video Path
        $product_video_path = public_path('front/videos/products/' . $product->product_video);

        // Delete Product Main Image if exists
        if (file_exists($product_video_path)) {
            unlink($product_video_path);
        }

        // Delete Product Main Image from products table
        Product::where('id', $id)->update(['product_video' => null]);

        return 'Product Video has been deleted successfully!';
    }

    public function deleteProductImage(string $id)
    {
        // Get Product Image
        $product = ProductsImage::select('image')->where('id', $id)->first();

        if (!$product || !$product->image) {
            return 'No image found';
        }

        // Get Product Image Path
        $image_path = public_path('front/images/products/' . $product->image);

        // Delete Product Image if exists
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete Product Image from products_images table
        ProductsImage::where('id', $id)->delete();

        return 'Product image has been deleted successfully!';
    }

    public function updateAttributeStatus(array $data): int
    {
        $status =($data['status'] == "Active") ? 0 : 1;
        ProductsAttribute::where('id', $data['attribute_id'])->update(['status' => $status]);
        return $status;
    }

    public function deleteProductAttribute(string $id): string
    {
        // Delete Attribute
        ProductsAttribute::where('id', $id)->delete();
        return 'Product Attribute has been deleted successfully!';
    }

    public function updateImageSorting($sortedImages): void
    {
        foreach ($sortedImages as $imageData) {
            if (isset($imageData['id']) && isset($imageData['sort'])) {
                ProductsImage::where('id', $imageData['id'])->update(['sort' => $imageData['sort']]);
            }
        }
    }
}
