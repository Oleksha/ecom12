<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    )
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Session::put('page', 'categories');
        $result = $this->categoryService->categories();
        if ($result['status'] == 'error') {
            return redirect('admin/dashboard')->with('error_message', $result['message']);
        }
        return view('admin.categories.index', [
            'categories' => $result['categories'],
            'categoriesModule' => $result['categoriesModule'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Добавить категорию';
        $category = new Category();
        $getCategories = Category::getCategories('Admin');
        /*dd($getCategories);*/
        return view('admin.categories.add_edit_category',
            compact('title', 'getCategories', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = $this->categoryService->addEditCategory($request);
        return redirect()->route('categories.index')->with('success_message', $message);
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
        $title = 'Редактировать категорию';
        $category = Category::findOrFail($id);
        $getCategories = Category::getCategories('Admin');
        return view('admin.categories.add_edit_category',
            compact('title', 'category', 'getCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->merge(['id' => $id]); // Ensure `addEditCategory` handles both Add/Edit
        $message = $this->categoryService->addEditCategory($request);
        return redirect()->route('categories.index')
            ->with('success_message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
