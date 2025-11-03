<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    // Show login form
    Route::get('login', [AdminController::class, 'create'])->name('admin.login');
    // Handle login form submission
    Route::post('login', [AdminController::class, 'store'])->name('admin.login.request');
    Route::group(['middleware' => ['admin']], function () {
        // Dashboard route
        Route::resource('dashboard', AdminController::class)->only(['index']);
        // Display Update Password Page
        Route::get('update-password', [AdminController::class, 'edit'])
            ->name('admin.update-password');
        // Verify Password
        Route::post('verify-password', [AdminController::class, 'verifyPassword'])
            ->name('admin.verify-password');
        // Update Password Request
        Route::post('update-password', [AdminController::class, 'update'])
            ->name('admin.update-password.request');
        // Display Update Admin Details
        Route::get('update-details', [AdminController::class, 'editDetails'])
            ->name('admin.update-details');
        // Update Admin Details
        Route::post('update-details', [AdminController::class, 'updateDetails'])
            ->name('admin.update-details.request');
        // Delete Profile Image
        Route::post('delete-profile-image', [AdminController::class, 'deleteProfileImage']);

        // Sub-Admins
        Route::get('subadmins', [AdminController::class, 'subadmins']);
        Route::post('update-subadmin-status', [AdminController::class, 'updateSubadminStatus']);
        Route::post('add-edit-subadmin/request', [AdminController::class, 'addEditSubadminRequest']);
        Route::get('add-edit-subadmin/{id?}', [AdminController::class, 'addEditSubadmin']);
        Route::get('delete-subadmin/{id}', [AdminController::class, 'deleteSubadmins']);
        Route::get('/update-role/{id}', [AdminController::class, 'updateRole']);
        Route::post('/update-role/request', [AdminController::class, 'updateRoleRequest']);

        // Categories
        Route::resource('categories', CategoryController::class);
        Route::post('update-category-status', [CategoryController::class, 'updateCategoryStatus']);
        Route::post('delete-category-image', [CategoryController::class, 'deleteCategoryImage']);
        Route::post('delete-size-chart-image', [CategoryController::class, 'deleteSizeChartImage']);

        // Products
        Route::resource('products', ProductController::class);
        Route::post('delete-category-image', [CategoryController::class, 'deleteCategoryImage']);
        Route::post('product/upload-image', [ProductController::class, 'uploadImage'])->name('product.upload.image');
        Route::post('product/upload-images', [ProductController::class, 'uploadImages'])->name('product.upload.images');
        Route::post('product/delete-temp-image', [ProductController::class, 'deleteTempImage'])->name('product.delete.temp.image');
        Route::post('product/update-image-sorting', [ProductController::class, 'updateImageSorting'])->name('admin.product.update-image-sorting');
        Route::get('delete-product-image/{id?}', [ProductController::class, 'deleteProductImage']);
        Route::post('product/upload-video', [ProductController::class, 'uploadVideo'])->name('product.upload.video');
        Route::get('delete-product-main-image/{id?}', [ProductController::class, 'deleteProductMainImage']);
        Route::get('delete-product-video/{id?}', [ProductController::class, 'deleteProductVideo']);

        // Attribute Product
        Route::post('update-attribute-status', [ProductController::class, 'updateAttributeStatus']);
        Route::get('delete-product-attribute/{id}', [ProductController::class, 'deleteProductAttribute']);

        // Admin Logout
        Route::get('logout', [AdminController::class, 'destroy'])->name('admin.logout');
    });
});
