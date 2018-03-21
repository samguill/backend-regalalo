<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function index(){
        $data = [
            "title" => "Intereses"
        ];
        return view('admin.interests.index', compact('data'));
    }

    public function lists(){
        $Interests = Interest::where('status', 0)->orWhere('status', 1)->get();
        return response()->json($Interests);
    }

    public function update(Request $request) {
        $data = $request->all();
        $Interest = Interest::find($data['id']);
        unset($data['id']);
        if($Interest->update($data))
            return response()->json(['status'=>'ok', 'data'=>$Interest]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Interest::find($data['id']);
        $model->status = 2;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = Interest::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }
}
