<?php

namespace Database\Seeders;

use App\Models\Dosage;
use App\Models\Form;
use App\Models\Presentation;
use Illuminate\Database\Seeder;

class CompositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dosage::factory(100)->create();
        Form::factory(100)->create();
        Presentation::factory(100)->create();
    }
}
