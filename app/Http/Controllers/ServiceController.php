<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(){
        return view('store.services.index');
    }

    public function lists(){
        $Services = Service::where('store_id',Auth::user()->store->id)->where('status', 0)->orWhere('status', 1)->get();
        return response()->json($Services);
    }

    public function update(Request $request) {
        $data = $request->all();
        $Service = Service::find($data['id']);
        unset($data['id']);
        if($Service->update($data))
            return response()->json(['status'=>'ok', 'data'=>$Service]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Service::find($data['id']);
        $model->status = 2;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = Service::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }
}
