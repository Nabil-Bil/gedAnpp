<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@ged.dz',
            'password' => Hash::make('admin@ged.dz'),
            'role' => 'administrateur',
            "direction_id" => "1",


        ]);

        User::create([
            'first_name' => 'directeur',
            'last_name' => 'directeur',
            'email' => 'directeur@ged.dz',
            'password' => Hash::make('directeur@ged.dz'),
            'role' => 'directeur',
            "direction_id" => "2",
        ]);

        User::create([
            'first_name' => 'evaluateur',
            'last_name' => 'evaluateur',
            'email' => 'evaluateur@ged.dz',
            'password' => Hash::make('evaluateur@ged.dz'),
            'role' => 'evaluateur',
            "direction_id" => "2",
        ]);

        User::factory(100)->create();
    }
}
