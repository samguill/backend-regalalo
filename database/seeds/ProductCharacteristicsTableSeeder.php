<?php

use App\Models\ProductCharacteristic;
use App\Models\ProductCharacteristicValue;
use Illuminate\Database\Seeder;

class ProductCharacteristicsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productcharacteristic=ProductCharacteristic::create(['name'=>'Talla de camisas/ polos']);

        ProductCharacteristicValue::create(['key'=>'S','value'=>'S', 'product_characteristic_id'=>$productcharacteristic->id]);
        ProductCharacteristicValue::create(['key'=>'M','value'=>'M', 'product_characteristic_id'=>$productcharacteristic->id]);
        ProductCharacteristicValue::create(['key'=>'L','value'=>'L', 'product_characteristic_id'=>$productcharacteristic->id]);
        ProductCharacteristicValue::create(['key'=>'XL','value'=>'XL', 'product_characteristic_id'=>$productcharacteristic->id]);

        $productcharacteristic= ProductCharacteristic::create(['name'=>'Talla de pantalones']);
        ProductCharacteristicValue::create(['key'=>'28','value'=>'28', 'product_characteristic_id'=>$productcharacteristic->id]);
        ProductCharacteristicValue::create(['key'=>'30','value'=>'30', 'product_characteristic_id'=>$productcharacteristic->id]);
        ProductCharacteristicValue::create(['key'=>'32','value'=>'32', 'product_characteristic_id'=>$productcharacteristic->id]);
        ProductCharacteristicValue::create(['key'=>'36','value'=>'36', 'product_characteristic_id'=>$productcharacteristic->id]);
        ProductCharacteristicValue::create(['key'=>'38','value'=>'38', 'product_characteristic_id'=>$productcharacteristic->id]);

    }
}
