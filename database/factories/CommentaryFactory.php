<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Commentary>
 */
class CommentaryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' =>User::all()[rand(1,99)]->id,
            'document_id'=>Document::all()[rand(1,20)]->id,
            'content' =>$this->faker->sentences(rand(1,2),true)
        ];
    }
}
