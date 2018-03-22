<?php
/**
 * Created by PhpStorm.
 * User: Propietario
 * Date: 18/03/2018
 * Time: 2:07 AM
 */

namespace App\Utils;


use App\Models\ProductCharacteristic;

class ParametersUtil
{
    const sex = [
       [ 'id' => 'G', 'value' =>'General'],
       [ 'id' => 'M', 'value' =>'Hombre'],
       [ 'id' => 'F', 'value'  => 'Mujer']
    ];

    public function __construct(){

    }

    static function getProductCharacteristics()
    {

        $productcharacteristic = array_map(
            function($item){

                return [
                    "id" => $item['id'],
                    "value" => $item['name']
                ];
            }, ProductCharacteristic::all(['id','name'])->toArray()
        );

        return $productcharacteristic;
    }

}