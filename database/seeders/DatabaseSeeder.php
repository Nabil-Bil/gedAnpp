<?php

namespace Database\Seeders;

use App\Models\Direction;
use App\Models\PharmaceuticalEstablishment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DirectionSeeder::class,
            UserSeeder::class,
            PharmaceuticalEstablishementSeeder::class,
            CompositionSeeder::class,
            DciSeeder::class,
            MedicationSeeder::class,
            ClassificationSeeder::class,
            DesignationSeeder::class,
            DeviceSeeder::class,
        ]);
    }
}
