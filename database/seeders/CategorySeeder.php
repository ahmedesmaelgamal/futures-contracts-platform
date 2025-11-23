<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Country;

class CategorySeeder extends Seeder
{

    public function run()
    {
        Category::factory()->count(100)->create();
    }
}
