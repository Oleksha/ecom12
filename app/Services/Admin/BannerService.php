<?php

namespace App\Services\Admin;

use App\Models\AdminsRole;
use App\Models\Banner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class BannerService
{
    /**
     * Get all banners and module permissions
     */
    public function banners()
    {
        $admin = Auth::guard('admin')->user();
        $banners = Banner::orderBy('sort')->get();
        $bannersModuleCount = AdminsRole::where([
            'subadmin_id' => $admin->id,
            'module' => 'banners',
        ])->count();

        $bannersModule = [];

        if ($admin->role == 'admin') {
            $bannersModule = [
                'view_access' => 1,
                'edit_access' => 1,
                'full_access' => 1,
            ];
        } elseif ($bannersModuleCount == 0) {
            return [
                'status' => 'error',
                'message' => 'This feature is restricted for you!'
            ];
        } else {
            $bannersModule = AdminsRole::where([
                'subadmin_id' => $admin->id,
                'module' => 'banners',
            ])->first()->toArray();
        }

        return [
            'status' => 'success',
            'banners' => $banners,
            'bannersModule' => $bannersModule,
        ];
    }

    /**
     * Update banner status via AJAX
     */
    public function updateBannerStatus($data): int
    {
        $status = ($data['status'] == 'Active') ? 0 : 1;
        Banner::where('id', $data['id'])->update(['status' => $status]);
        return $status;
    }

    /**
     * Delete a banner
     */
    public function deleteBanner($id): array
    {
        $banner = Banner::findOrFail($id);
        $bannerImagePath = public_path('front/images/banners/' . $banner->image);

        if (File::exists($bannerImagePath)) {
            File::delete($bannerImagePath);
        }

        $banner->delete();

        return ['status' => 'success', 'message' => 'Banner deleted successfully!'];
    }

    public function addEditBanner($request)
    {
        $data = $request->all();
        $banner = isset($data['id']) ? Banner::find($data['id']) : new Banner();
        $banner->type = $data['type'];
        $banner->link = $data['link'];
        $banner->title = $data['title'];
        $banner->alt = $data['alt'];
        $banner->sort = $data['sort'] ?? 0;
        $banner->status = $data['status'] ?? 1;

        // Handle image upload
        if ($request->hasFile('image')) {
            /*$image_tmp = $request->file('image');
            if ($image_tmp->isValid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image_tmp);
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111, 9999) . '.' . $extension;
                $imagePath = 'front/images/brands/' . $imageName;
                $image->save($imagePath);
                $brand->image = $imageName;
            }*/
            $path = 'front/images/banners/';
            if (!File::exists(public_path($path))) {
                File::makeDirectory(public_path($path), $mode = 0755, true);
            }

            // Delete old image if editing
            if (!empty($banner->image) && File::exists(public_path($path . $banner->image))) {
                File::delete(public_path($path . $banner->image));
            }

            $image = $request->file('image');
            $imageName = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path($path), $imageName);
            $banner->image = $imageName;
        }

        $banner->save();
        return isset($data['id']) ? 'Баннер успешно обновлен!' : 'Баннер успешно добавлен!';
    }
}
