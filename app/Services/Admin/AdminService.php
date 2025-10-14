<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AdminService
{
    public function login($data): int
    {
        if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            // Remember Admin Email and Password
            if (!empty($data['remember'])) {
                setcookie("email", $data['email'], time() + 3600);
                setcookie("password", $data['password'], time() + 3600);
            } else {
                setcookie("email", "");
                setcookie("password", "");
            }
            $loginStatus = 1;
        } else {
            $loginStatus = 0;
        }
        return $loginStatus;
    }

    public function verifyPassword($data): string
    {
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            return "true";
        } else {
            return "false";
        }
    }

    public function updatePassword($data)
    {
        // Check if the Current Password is correct
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            // Check if new password and confirm password match
            if ($data['new_pwd'] == $data['confirm_pwd']) {
                Admin::where('email', Auth::guard('admin')->user()->email)
                    ->update(['password' => Hash::make($data['new_pwd'])]);
                $status ="success";
                $message = 'Password has been updated successfully!';
            } else {
                $status = "error";
                $message = 'New Password and Confirm Password must match!';
            }
        } else {
            $status = "error";
            $message = 'Your Current Password is incorrect!';
        }
        return ['status' => $status, 'message' => $message];
    }

    public function updateDetails($request)
    {
        $data = $request->all();

        // Update Admin Image
        if ($request->hasFile('image')) {
            $image_tmp = $request->file('image');
            if ($image_tmp->isValid()) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($image_tmp);
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(111, 99999) . '.' . $extension;
                $image_path = 'admin/images/photos/' . $imageName;
                $image->save($image_path);
            } elseif (!empty($data['current_image'])) {
                $imageName = $data['current_image'];
            } else {
                $imageName = "";
            }
        }

        // Update Admin Details
        Admin::where('email',Auth::guard('admin')->user()->email)->update([
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'image' => $imageName,
        ]);
    }

    public function deleteProfileImage($adminId)
    {
        $profileImage = Admin::where('id', $adminId)->value('image');
        if ($profileImage) {
            $profileImagePath = 'admin/images/photos/' . $profileImage;
            if (file_exists(public_path($profileImagePath))) {
                unlink(public_path($profileImagePath));
            }
            Admin::where('id', $adminId)->update(['image' => null]);
            return ['status' => true, 'message' => "Profile Image deleted successfully!"];
        } else {
            return ['status' => false, 'message' => "Profile Image not found!"];
        }
    }

    public function subadmins()
    {
        return Admin::where('role', 'subadmin')->get();
    }

    public function updateSubadminStatus($data): int
    {
        $status = ($data['status'] == "Active") ? 0 : 1;
        Admin::where('id', $data['subadmin_id'])->update(['status' => $status]);
        return $status;
    }

    public function deleteSubadmins(string $id): array
    {
        // Delete Sub Admin
        Admin::where('id', $id)->delete();
        $message = "Subadmin deleted successfully!";
        return array("message" => $message);
    }
}
