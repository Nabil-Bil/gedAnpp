<?php

namespace Database\Seeders;


use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        $profile_picture=Http::get('https://pixabay.com/api/',
        [
            'key'=>Config('values.PIXABAY_KEY'),
            'image_type'=>'vector',
            'per_page'=>3,
        ]);

        User::create([
            'name'=>'Super admin',
            'email'=>'admin@ged.dz',
            'password'=>Hash::make('admin@ged.dz'),
            'role'=>'super admin',
            'path'=>$profile_picture['hits'][0]['previewURL']
        ]);

        User::create([
            'name'=>'directeur',
            'email'=>'directeur@ged.dz',
            'password'=>Hash::make('directeur@ged.dz'),
            'role'=>'directeur',
            'path'=>$profile_picture['hits'][1]['previewURL']
        ]);

        User::create([
            'name'=>'Evaluateur',
            'email'=>'evaluateur@ged.dz',
            'password'=>Hash::make('evaluateur@ged.dz'),
            'role'=>'evaluateur',
            'path'=>$profile_picture['hits'][2]['previewURL']
        ]);
        
    }
}
