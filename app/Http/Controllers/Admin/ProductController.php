<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    )
    {}
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
        return view('admin.products.index', [
            'products' => $result['products'],
            'productsModule' => $result['productsModule']
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
    public function store(Request $request)
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
        $product = Product::findOrFail($id);
        $getCategories = Category::getCategories('Admin');
        return view('admin.products.add_edit_product', compact('title', 'product', 'getCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
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
}
