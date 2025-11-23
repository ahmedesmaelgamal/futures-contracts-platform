<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Enums\RoleEnum;
use App\Models\Unsurpassed;
use App\Models\VendorBranch;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UnsurpassedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unsurpassed::factory()->count(100)->create();

    }

}
