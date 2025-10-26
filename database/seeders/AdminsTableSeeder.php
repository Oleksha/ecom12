<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('123456');
        $admin = new Admin;
        $admin->name = 'Oleg Deryabin';
        $admin->role = 'admin';
        $admin->mobile = '79061291066';
        $admin->email = 'admin@mail.ru';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();

        $admin = new Admin;
        $admin->name = 'Amit Gupta';
        $admin->role = 'subadmin';
        $admin->mobile = '97000000000';
        $admin->email = 'oleksha@mail.ru';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();

        $admin = new Admin;
        $admin->name = 'John Doe';
        $admin->role = 'subadmin';
        $admin->mobile = '96000000000';
        $admin->email = 'john@mail.ru';
        $admin->password = $password;
        $admin->status = 1;
        $admin->save();
    }
}
