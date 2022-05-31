<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PharmaceuticalEstablishment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PharmaceuticalEstablishementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PharmaceuticalEstablishment::factory(100)->create();
    }
}
