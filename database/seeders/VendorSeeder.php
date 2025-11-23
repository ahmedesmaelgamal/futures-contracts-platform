<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Branch;
use App\Models\Vendor;
use App\Models\VendorBranch;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        $vendor = Vendor::create([
            'name' => 'vendor',
            'phone' => '+966535353535',
            'national_id' => '7474747474',
            'commercial_number' => '6565656565',
            'plan_id' => 1,

            'city_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'S-ali07@hotmail.com',
            'password' => bcrypt('vendor'),
        ]);


        // create main branch
        $b1 = Branch::create([
            'name' => 'الفرع الرئيسي',
            'is_main' => true,
            'vendor_id' => $vendor->id

        ]);

        VendorBranch::create([
            'vendor_id' => $vendor->id,
            'branch_id' => $b1->id
        ]);


        $ahmed = Vendor::create([
            'name' => 'ahmed',
            'phone' => '+966222222222',
            'national_id' => '9696969696',
            'commercial_number' => '9696969695',
            'plan_id' => 1,
            'city_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'ahmed',
            'email' => 'ahmedesmaelgamal@gmail.com',
            'password' => bcrypt('vendor'),
        ]);


        // create main branch
        $b2 = Branch::create([
            'name' => 'الفرع الرئيسي',
            'is_main' => true,
            'vendor_id' => $ahmed->id

        ]);

        VendorBranch::create([
            'vendor_id' => $ahmed->id,
            'branch_id' => $b2->id
        ]);


        $mohamed = Vendor::create([
            'name' => 'amo',
            'phone' => '+966444444444',
            'national_id' => '4141414141',
            'commercial_number' => '4545454545',
            'plan_id' => 1,
            'city_id' => 1,

            'role_id' => 6,
            'status' => 1,
            'username' => 'amo',
            'email' => 'amomatter48@gmail.com',
            'password' => bcrypt('vendor'),
        ]);

        // create main branch
        $b3 = Branch::create([
            'name' => 'الفرع الرئيسي',
            'is_main' => true,
            'vendor_id' => $mohamed->id

        ]);

        VendorBranch::create([
            'vendor_id' => $mohamed->id,
            'branch_id' => $b3->id,
        ]);


        $permissions = Permission::where('guard_name', 'vendor')->get();
        $vendor->syncPermissions($permissions);
        $ahmed->syncPermissions($permissions);
        $mohamed->syncPermissions($permissions);
    }
}
