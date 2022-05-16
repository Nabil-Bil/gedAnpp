<?php

namespace Database\Factories;

use App\Models\Medication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TechnicalFile>
 */
class TechnicalFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $code=$this->faker->words(3,true);
        return [
            'code' =>$code,
            'status'=>$this->faker->word(),
            'medication_code'=>Medication::all()[rand(1,100)]['code'],
        ];
    }
}
