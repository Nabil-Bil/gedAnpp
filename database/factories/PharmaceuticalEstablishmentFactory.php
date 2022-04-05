<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PharmaceuticalEstablishment>
 */
class PharmaceuticalEstablishmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->company(),
            'email'=>$this->faker->companyEmail(),
            'fixed'=>$this->faker->tollFreePhoneNumber(),
            'mobile'=>$this->faker->phoneNumber(),
            'fax'=>$this->faker->e164PhoneNumber(),
            'address'=>$this->faker->address(),
            'nature'=>$this->faker->word(),
            'agreement'=>$this->faker->sentence(3,false),
            'status'=>$this->faker->word(),
            'manager_name'=>$this->faker->name(),
            'tech_manager_name'=>$this->faker->name(),
            'activity'=>$this->faker->words(2,true),


        ];
    }
}
