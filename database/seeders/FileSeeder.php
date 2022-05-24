<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Document;
use App\Models\TechnicalFile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $technicalFileNumber=50;
        TechnicalFile::factory($technicalFileNumber)->create()->each(function ($file) {
            $samplePdf = Storage::disk('public')->get('sample.pdf');
            for($i=0;$i<rand(2,3);$i++){
                $fileName=Factory::create()->word();
            Storage::disk('public')->put("technicalFiles/{$file['code']}/{$fileName}.pdf", $samplePdf);
            Document::factory()->create([
                'module_number' => rand(1, 5),
                'path' => "technicalFiles/{$file['code']}/{$fileName}.pdf",
                'technical_file_code' => $file['code']
            ]);
            }   
        });
    }
}
