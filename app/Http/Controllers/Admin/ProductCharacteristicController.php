<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProductCharacteristic;
use App\Models\ProductCharacteristicValue;
use Illuminate\Http\Request;

class ProductCharacteristicController extends Controller
{
    public function index(){
        $data = [
            "title" => "Características de productos"
        ];
        return view('admin.productscharacteristics.index', compact('data'));
    }

    public function lists(){

        $productcharacteristics = ProductCharacteristic::where('status', 0)->orWhere('status', 1)->get();
        return response()->json($productcharacteristics);
    }

    public function values(Request $request){

        $product_characteristic_id =  $request->input('id');
        $productcharacteristic=ProductCharacteristic::where('id',$product_characteristic_id)->first();
        return view('admin.productscharacteristics.values', compact('product_characteristic_id','productcharacteristic'));
    }

    public function listValues(Request $request){
        $product_characteristic_id = $request->input('id');
        $values = ProductCharacteristicValue::where('product_characteristic_id',$product_characteristic_id)->get();
        return response()->json(['status'=>'ok','data' => $values ]);
    }

    public function create_value(Request $request){
        try{
            $model = ProductCharacteristicValue::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function update_value(Request $request){
        $data = $request->all();
        $model = ProductCharacteristicValue::find($data['id']);
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
            $model = ProductCharacteristic::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = ProductCharacteristic::find($data['id']);
        $model->values()->delete();
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function update(Request $request) {
        $data = $request->all();
        $store = ProductCharacteristic::find($data['id']);
        unset($data['id']);
        if($store->update($data)){
            return response()->json(['status'=>'ok', 'data'=>$store]);
        }else{
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
        }

    }
}
