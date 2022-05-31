<?php

namespace Database\Factories;

use App\Models\Classification;
use App\Models\Designation;
use App\Models\PharmaceuticalEstablishment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Device>
 */
class DeviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->words(2, true),
            'name' => $this->faker->word(),
            'type' => $this->faker->word(),
            'de_holder' => $this->faker->words(3, true),
            'pharmaceutical_establishment_id' => rand(1, PharmaceuticalEstablishment::all()->count()),
            'designation_id' => rand(1, Designation::all()->count()),
            'classification_id' => rand(1, Classification::all()->count()),
            'characteristic' => $this->faker->words(3, true),
            'created_at' => $this->faker->dateTimeThisYear('31-12-2022')
        ];
    }
}
