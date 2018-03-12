<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        return view('store.Products.index');
    }

    public function lists(){
        $Products = Product::where('status', 0)->orWhere('status', 1)->get();
        return response()->json($Products);
    }

    public function update(Request $request) {
        $data = $request->all();
        $Product = Product::find($data['id']);
        unset($data['id']);
        if($Product->update($data))
            return response()->json(['status'=>'ok', 'data'=>$Product]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Product::find($data['id']);
        $model->status = 2;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = Product::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }
}
