<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Investor;
use Illuminate\Database\Seeder;

class InvestorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Investor::factory()->count(100)->create();
    }
}
