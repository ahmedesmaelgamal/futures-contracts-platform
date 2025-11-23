<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Investor;
use App\Models\VendorBranch;
use Illuminate\Database\Seeder;

class VendorBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VendorBranch::factory()->count(100)->create();
    }
}
