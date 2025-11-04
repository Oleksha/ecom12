<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\ColumnPreference;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Session::put('page', 'products');
        $result = $this->productService->products();
        if ($result['status'] == 'error') {
            return redirect('admin/dashboard')->with('error_message', $result['message']);
        }
        $productsSavedOrder = ColumnPreference::where('admin_id', Auth::guard('admin')->id())
            ->where('table_name', 'products')
            ->value('column_order');
        return view('admin.products.index', [
            'products' => $result['products'],
            'productsModule' => $result['productsModule'],
            'productsSavedOrder' => $productsSavedOrder,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Add Product";
        $getCategories = Category::getCategories('Admin');
        return view('admin.products.add_edit_product', compact('title', 'getCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $messages = $this->productService->addEditProduct($request);
        return redirect()->route('products.index')->with('success_message', $messages);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Edit Product";
        $product = Product::with(['product_images', 'attributes'])->findOrFail($id);
        $getCategories = Category::getCategories('Admin');
        return view('admin.products.add_edit_product', compact('title', 'product', 'getCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        $request->merge(['id' => $id]);
        $message = $this->productService->addEditProduct($request);
        return redirect()->route('products.index')->with('success_message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = $this->productService->deleteProduct($id);
        return redirect()->back()->with('success_message', $result['message']);
    }

    public function updateProductStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $status = $this->productService->updateProductStatus($data);
            return response()->json(['status' => $status, 'product_id' => $data['product_id']]);
        }
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $fileName = $this->productService->handleImageUpload($request->file('file'));
            return response()->json(['fileName' => $fileName]);
        }
        return response()->json(['error' => 'No file was uploaded.'], 400);
    }

    public function uploadVideo(Request $request)
    {
        if ($request->hasFile('file')) {
            $fileName = $this->productService->handleVideoUpload($request->file('file'));
            return response()->json(['fileName' => $fileName]);
        }
        return response()->json(['error' => 'No file was uploaded.'], 400);
    }

    public function deleteProductMainImage(string $id)
    {
        $message = $this->productService->deleteProductMainImage($id);
        return redirect()->back()->with('success_message', $message);
    }

    public function deleteProductVideo(string $id)
    {
        $message = $this->productService->deleteProductVideo($id);
        return redirect()->back()->with('success_message', $message);
    }

    public function uploadImages(Request $request)
    {
        if ($request->hasFile('file')) {
            $fileName = $this->productService->handleImageUpload($request->file('file'));
            return response()->json(['fileName' => $fileName]);
        }
    }

    public function deleteProductImage(string $id)
    {
        $message = $this->productService->deleteProductImage($id);
        return redirect()->back()->with('success_message', $message);
    }

    public function deleteTempImage(Request $request)
    {
        $image_path = public_path('front/images/products/'.$request->filename);
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    public function updateAttributeStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            $status = $this->productService->updateAttributeStatus($data);
            return response()->json(['status' => $status, 'attribute_id' => $data['attribute_id']]);
        }
    }

    public function deleteProductAttribute(string $id)
    {
        $message = $this->productService->deleteProductAttribute($id);
        return redirect()->back()->with('success_message', $message);
    }

    public function updateImageSorting(Request $request)
    {
        $this->productService->updateImageSorting($request->sorted_images);
        return response()->json(['status' => 'success']);
    }

    public function deleteDropzoneImage(Request $request)
    {
        $deleted = $this->productService->deleteDropzoneImage($request->image);
        return response()->json(['status' => $deleted ? 'deleted' : 'file_not_found'], $deleted ? 200 : 400);
    }

    public function deleteTempProductVideo(Request $request)
    {
        $deleted = $this->productService->deleteDropzoneVideo($request->filename);
        return response()->json(['status' => $deleted ? 'deleted' : 'file_not_found'], $deleted ? 200 : 400);
    }
}
