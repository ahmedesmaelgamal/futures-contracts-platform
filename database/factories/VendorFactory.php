<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class VendorFactory extends Factory
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
            'name' => $this->faker->company(),
            'phone' => $this->faker->unique()->phoneNumber(),
            'commercial_number' => $this->faker->unique()->numerify('##########'),
            'email' => Fake()->unique()->safeEmail(),
            'national_id' => $this->faker->optional()->numerify('##############'),
            'username' => $this->faker->unique()->userName(),
            'password' => bcrypt('password'), // Default password
            'role_id' => $this->faker->numerify('###'),
            'city_id' => $this->faker->numberBetween(1, 10),
            'branch_id' => \App\Models\Branch::factory(),
            'parent_id' => 1, // Assuming you have 10 vendors
            'plan_id' => 1, // Assuming you have 5 plans
            'status' => $this->faker->boolean(80), // 80% chance of being active
            'profit_ratio' => $this->faker->randomFloat(2, 0, 100), // Random percentage
            'is_profit_ratio_static' => $this->faker->boolean(),
            'image' => $this->faker->optional()->imageUrl(),
//            'otp' => $this->faker->optional()->numerify('######'),
//            'otp_expire_at' => $this->faker->optional()->dateTimeBetween('now', '+1 day'),
        ];
    }
}
