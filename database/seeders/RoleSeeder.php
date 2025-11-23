<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::query()->insert([
            ['name' => 'super_admin', 'guard_name' => 'admin'],
            ['name' => 'admin', 'guard_name' => 'admin'],
            ['name' => 'admin_finance', 'guard_name' => 'admin'],
            ['name' => 'admin_marketing', 'guard_name' => 'admin'],
            ['name' => 'admin_support', 'guard_name' => 'admin'],

            ['name' => 'partner_admin', 'guard_name' => 'vendor'],
            ['name' => 'partner_finance', 'guard_name' => 'vendor'],
            ['name' => 'partner_reservation', 'guard_name' => 'vendor'],
        ]);

    }

}
