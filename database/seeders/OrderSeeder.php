<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Order;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Country;

class OrderSeeder extends Seeder
{

    public function run()
    {
        Order::factory()->count(100)->create();
    }
}
