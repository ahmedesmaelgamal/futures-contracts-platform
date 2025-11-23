<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Unsurpassed>
 */
class UnsurpassedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'phone' => $this->faker->unique()->numerify('+966'.'#########'),
            'national_id' => $this->faker->unique()->numerify('##########'),
            'office_name' => $this->faker->word,
            'office_phone' => $this->faker->unique()->numerify('+966'.'#########'),
        ];
    }
}
