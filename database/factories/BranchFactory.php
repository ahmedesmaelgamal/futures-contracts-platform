<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BranchFactory extends Factory
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
            'name' => $faker->streetName(),
            'address' => $faker->address(),
            'status' => $this->faker->boolean,
            'is_main' => $this->faker->boolean(20),
            'vendor_id' => $this->faker->NumberBetween(1,10),
        ];
    }


}
