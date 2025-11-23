<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
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
            'phone' => $this->faker->phoneNumber(),
            'national_id' => $this->faker->unique()->randomNumber(8),
            'status' => $this->faker->boolean(),
            'branch_id' => $this->faker->numberBetween(1,10),
        ];
    }


}
