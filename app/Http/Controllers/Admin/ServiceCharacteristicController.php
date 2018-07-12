<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceCharacteristic;
use App\Models\ServiceCharacteristicValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceCharacteristicController extends Controller
{
    public function index(){
        $data = [
            "title" => "Características de servicios"
        ];
        return view('admin.servicescharacteristics.index', compact('data'));
    }

    public function lists(){

        $servicecharacteristics = ServiceCharacteristic::where('status', 0)->orWhere('status', 1)->get();
        return response()->json($servicecharacteristics);
    }

    public function values(Request $request){

        $service_characteristic_id =  $request->input('id');
        $servicecharacteristic=ServiceCharacteristic::where('id',$service_characteristic_id)->first();
        return view('admin.servicescharacteristics.values', compact('service_characteristic_id','servicecharacteristic'));
    }

    public function listValues(Request $request){
        $service_characteristic_id = $request->input('id');
        $values = ServiceCharacteristicValue::where('service_characteristic_id',$service_characteristic_id)->get();
        return response()->json(['status'=>'ok','data' => $values ]);
    }

    public function create_value(Request $request){
        try{
            $model = ServiceCharacteristicValue::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function update_value(Request $request){
        $data = $request->all();
        $model = ServiceCharacteristicValue::find($data['id']);
        unset($data['id']);
        foreach ($data as $field=>$value) {
            $model->$field = $value;
        }

        if($model->save()) {
            return response()->json(['status' => 'ok','data' => $model]);
        }else{
            return response()->json(['status' => 'error',"message" => "No se ha podido actualizar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = ServiceCharacteristic::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = ServiceCharacteristic::find($data['id']);
        $model->values()->delete();
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function update(Request $request) {
        $data = $request->all();
        $store = ServiceCharacteristic::find($data['id']);
        unset($data['id']);
        if($store->update($data)){
            return response()->json(['status'=>'ok', 'data'=>$store]);
        }else{
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
        }

    }
}
