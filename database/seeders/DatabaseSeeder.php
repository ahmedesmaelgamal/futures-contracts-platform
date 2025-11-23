<?php

namespace Database\Seeders;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountrySeeder::class,
            CitySeeder::class,
            AreaSeeder::class,
            RegionSeeder::class
        ]);
        $this->call(FreePlanSeeder::class);

        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(VendorSeeder::class);

//         $this->call(BranchSeeder::class);
//         $this->call(VendorBranchSeeder::class);
//         $this->call(InvestorSeeder::class);
//         $this->call(ClientSeeder::class);
//         $this->call(UnsurpassedSeeder::class);
//         $this->call(CategorySeeder::class);
//         $this->call(OrderSeeder::class);

    }
}
