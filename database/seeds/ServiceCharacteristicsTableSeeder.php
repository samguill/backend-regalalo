<?php

use App\Models\ServiceCharacteristic;
use App\Models\ServiceCharacteristicValue;
use Illuminate\Database\Seeder;

class ServiceCharacteristicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $servicecharacteristic=ServiceCharacteristic::create(['name'=>'Paquetes']);

      ServiceCharacteristicValue::create(['key'=>'C','value'=>'Completo', 'service_characteristic_id'=>$servicecharacteristic->id]);
        ServiceCharacteristicValue::create(['key'=>'B','value'=>'Básico', 'service_characteristic_id'=>$servicecharacteristic->id]);


        $servicecharacteristic= ServiceCharacteristic::create(['name'=>'Horarios']);
        ServiceCharacteristicValue::create(['key'=>'M','value'=>'Mañana', 'service_characteristic_id'=>$servicecharacteristic->id]);
        ServiceCharacteristicValue::create(['key'=>'T','value'=>'Tarde', 'service_characteristic_id'=>$servicecharacteristic->id]);

    }
}
