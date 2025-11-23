<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvestorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('ar_SA');

        return [
            'name' => $faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'national_id' => $this->faker->unique()->numerify('###########'),
          'branch_id' => optional(\App\Models\Branch::where('is_main', 1)->inRandomOrder()->first())->id,
//'vendor_id' => optional(\App\Models\Vendor::where('branch_id', optional(\App\Models\Branch::where('is_main', 1)->inRandomOrder()->first())->id)->inRandomOrder()->first())->id,
        ];
    }
}
