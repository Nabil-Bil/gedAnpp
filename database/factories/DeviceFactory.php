<?php

namespace Database\Factories;

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
            'code'=>$this->faker->unique()->words(2,true),
            'name'=>$this->faker->word(),
            'type'=>$this->faker->word(),
            'de_holder'=>$this->faker->words(3,true),
            'pharmaceutical_establishment_id'=>rand(1,PharmaceuticalEstablishment::all()->count()),
            'designation'=>$this->faker->words(3,true),
            'classification'=>$this->faker->word(),
            'characteristic'=>$this->faker->words(3,true),
            'duration'=>strval($this->faker->randomNumber()) ,
            
        ];
    }
}
