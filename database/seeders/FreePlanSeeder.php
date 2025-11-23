<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\PlanDetail;
use Illuminate\Database\Seeder;

class FreePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $freePlan =  Plan::create([
            'name' => 'الخطة المجانية',
            'price' => 0,
            'description' => 'خطة مجانية للتجربة',
            'status' => 1,
            'period' => 365,
            'image' => 'assets/seeder/free_plan.webp',
            'discount' => 100,

        ]);


        PlanDetail::create([
            'plan_id' => $freePlan->id,
            'key' => 'Vendor',
            'value' => 10

        ]);



        PlanDetail::create([
            'plan_id' => $freePlan->id,
            'key' => 'Branch',
            'value' => 10

        ]);
        PlanDetail::create([
            'plan_id' => $freePlan->id,
            'key' => 'Investor',
            'value' => 10

        ]);
        PlanDetail::create([
            'plan_id' => $freePlan->id,
            'key' => 'Order',
            'value' => 10

        ]);
    }
}
