<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Direction>
 */
class DirectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->company,
            "service" => $this->faker->companySuffix(),
            "one" => rand(0, 1),
            "two" => rand(0, 1),
            "three" => rand(0, 1),
            "four" => rand(0, 1),
            "five" => rand(0, 1),

        ];
    }
}
