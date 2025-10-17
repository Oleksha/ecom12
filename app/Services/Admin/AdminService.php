<?php

namespace App\Services\Admin;

use App\Models\Admin;
use App\Models\AdminsRole;
use Auth;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use function Laravel\Prompts\password;

class AdminService
{
    public function login($data): int
    {
        $admin = Admin::where('email', $data['email'])->first();
        if ($admin) {
            if ($admin->status == 0) {
                return "inactive";
            }
            if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
                // Remember Admin Email and Password
                if (!empty($data['remember'])) {
                    setcookie("email", $data['email'], time() + 3600);
                    setcookie("password", $data['password'], time() + 3600);
                } else {
                    setcookie("email", "");
                    setcookie("password", "");
                }
                return 'success'; // Return success if login is successful
            } else {
               return 'invalid'; // Return invalid if credentials are incorrect
            }
        } else {
            return 'invalid'; // Return invalid if email is not found
        }
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

    public function addEditSubadmin($request): array
    {
        $data = $request->all();
        if (isset($data['id']) && $data['id'] != "") {
            $subadmin_data = Admin::find($data['id']);
            $message = "Subadmin updated successfully!";
        } else {
            $subadmin_data = new Admin();
            $message = "Subadmin added successfully!";
        }

        // Upload Admin Image
        if ($request->hasFile('image')) {
            $image_tmp = $request->file('image');
            if ($image_tmp->isValid()) {
                // Create image manager with desired driver
                $manager = new ImageManager(new Driver());
                // Read image from file system
                $image = $manager->read($image_tmp);
                // Get Image Extension
                $extension = $image_tmp->getClientOriginalExtension();
                // Generate New Image Name
                $imageName = rand(111, 99999) . '.' . $extension;
                $image_path = 'admin/images/photos/' . $imageName;
                // Save image in specified path
                $image->save($image_path);
            }
        } elseif (!empty($data['current_image'])) {
            $imageName = $data['current_image'];
        } else {
            $imageName = "";
        }

        $subadmin_data->name = $data['name'];
        $subadmin_data->mobile = $data['mobile'];
        $subadmin_data->image = $imageName;
        if (!isset($data['id'])) {
            $subadmin_data->role = "subadmin";
            $subadmin_data->status = 1;
            $subadmin_data->email = $data['email'];
        }
        if ($data['password'] != "") {
            $subadmin_data->password = Hash::make($data['password']);
        }
        $subadmin_data->save();
        return array('message' => $message);
    }

    public function updateRole($request): array
    {
        $data = $request->all();
        // Remove existing roles before updating
        AdminsRole::where('subadmin_id', $data['subadmin_id'])->delete();
        // Assign new roles dynamically
        foreach ($data as $key => $value) {
            if (!is_array($value)) continue; // Skip non-module fields
            $view = $value['view'] ?? 0;
            $edit = $value['edit'] ?? 0;
            $full = $value['full'] ?? 0;
            AdminsRole::insert([
                'subadmin_id' => $data['subadmin_id'],
                'module' => $key,
                'view_access' => $view,
                'edit_access' => $edit,
                'full_access' => $full,
            ]);
        }
        return ['message' => "Subadmin Roles updated successfully!"];
    }
}
