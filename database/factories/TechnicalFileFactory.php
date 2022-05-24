<?php

namespace Database\Factories;

use App\Models\Device;
use App\Models\Medication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TechnicalFile>
 */
class TechnicalFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $code=$this->faker->unique()->word();
        $medicationStatus = [
            "Réception",
            "Recevable",
            "Etude technico règlementaire",
            "Evaluation technique",
            "Contrôle Qualité",
            "Elaboration décision d’enregistrement",
            "DE remise",
        ];
        $deviceStatus = [
            "Réception",
            "Recevable",
            "Etude technico règlementaire",
            "Evaluation technique",
            "Contrôle Qualité",
            "Elaboration décision d’homologation",
            "DH remise",
        ];

        $random=rand(0,1);

        $dci_medication_id= $random%2==0?Medication::all()[rand(1,99)]->dcis[0]->id:null;
        $device_code=$random%2==1?Device::all()[rand(1,99)]->code:null;
        $status='';
        if($dci_medication_id==null){
            $status=$deviceStatus[rand(0,count($deviceStatus)-1)];
        }else{
            $status=$medicationStatus[rand(0,count($medicationStatus)-1)];
        }
        return [
            'code' =>$code,
            'status'=>$status,
            'dci_medication_id'=>$dci_medication_id,
            'device_code'=>$device_code,
            
        ];
    }
}
