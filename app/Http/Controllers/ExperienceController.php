<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(){
        return view('admin.experiences.index');
    }

    public function lists(){
        $experiences = Experience::where('status', 0)->orWhere('status', 1)->get();
        return response()->json($experiences);
    }

    public function update(Request $request) {
        $data = $request->all();
        $exerience = Experience::find($data['id']);
        unset($data['id']);
        if($exerience->update($data))
            return response()->json(['status'=>'ok', 'data'=>$exerience]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Experience::find($data['id']);
        $model->status = 2;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = Experience::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }
}
