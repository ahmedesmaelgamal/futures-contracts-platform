<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Enums\RoleEnum;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'name' => 'admin',
            'user_name' => 'admin',
            'phone' => '+966567700065',
            'code' => Str::random(11),
            'email' => 'S-ali07@hotmail.com',
            'role_id' => 1,
            'status' => 1,
            'password' => bcrypt('admin'),
        ]);
        $ahmed = Admin::create([
            'name' => 'ahmed',
            'phone' => '+966123456787',

            'user_name' => 'ahmed',
            'code' => Str::random(11),
            'email' => 'ahmedesmaelgamal@gmail.com',
            'role_id' => 1,
            'status' => 1,

            'password' => bcrypt('admin'),
        ]);
        $mohamed = Admin::create([
            'name' => 'mohamed',
            'phone' => '+966123456782',

            'user_name' => 'mohamed',
            'code' => Str::random(11),
            'email' => 'amomatter48@gmail.com',
            'role_id' => 1,
            'status' => 1,

            'password' => bcrypt('admin'),
        ]);

        $permissions=Permission::where('guard_name','admin')->get();
       $admin->syncPermissions($permissions);
       $ahmed->syncPermissions($permissions);
       $mohamed->syncPermissions($permissions);
    }

}
