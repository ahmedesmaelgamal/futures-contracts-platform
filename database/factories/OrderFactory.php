<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Client;
use App\Models\Investor;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
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
            'client_id' => $this->faker->numberBetween(1, 5),
            'category_id' => $this->faker->numberBetween(1, 5),
            'branch_id' => $this->faker->numberBetween(1, 5),
            'investor_id' => $this->faker->numberBetween(1, 5),
            'vendor_id' => $this->faker->numberBetween(1, 5),

            'order_number' => $this->faker->unique()->numberBetween(1000, 9999),
            'quantity' => $this->faker->numberBetween(1, 100),
            'required_to_pay' => $this->faker->randomFloat(2, 1000, 5000),
            'expected_price' => $this->faker->randomFloat(2, 1000, 5000),
            'Total_expected_commission' => $this->faker->randomFloat(2, 100, 1000),
            'sell_diff' => $this->faker->randomFloat(2, 50, 500),
            'delivered_price_to_client' => $this->faker->randomFloat(2, 1000, 5000),
            'date' => $this->faker->date(),
        ];
    }


}
