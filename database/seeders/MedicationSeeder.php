<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $medications = Medication::factory(100)->create();

        foreach ($medications as $medication) {
            for ($i = 0; $i < rand(1, 10); $i++) {
                $medication->dcis()->attach(rand(1, 99));
            }
        }
    }
}
