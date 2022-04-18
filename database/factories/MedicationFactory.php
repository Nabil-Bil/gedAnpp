<?php

namespace Database\Factories;

use App\Models\Form;
use App\Models\Dosage;
use App\Models\Presentation;
use App\Models\PharmaceuticalEstablishment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medication>
 */
class MedicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code'=>$this->faker->unique()->word(),
            'name'=>$this->faker->word(),
            'type'=>$this->faker->word(),
            'type'=>$this->faker->word(),
            'de_holder'=>$this->faker->words(3,true),
            'conditioning'=>$this->faker->word(),
            'pharmaceutical_establishment_id'=>rand(1,PharmaceuticalEstablishment::all()->count()),
            'form_id'=>rand(1,Form::all()->count()),
            'presentation_id'=>rand(1,Presentation::all()->count()),
            'dosage_id'=>rand(1,Dosage::all()->count()),
        ];
    }
}
