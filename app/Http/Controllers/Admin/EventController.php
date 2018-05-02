<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(){
        $data = [
            "title" => "Ocasiones"
        ];
        return view('admin.events.index', compact('data'));
    }

    public function lists(){
        $Events = Event::where('status', 0)->orWhere('status', 1)->get();
        return response()->json($Events);
    }

    public function update(Request $request) {
        $data = $request->all();
        $Event = Event::find($data['id']);
        unset($data['id']);
        if($Event->update($data))
            return response()->json(['status'=>'ok', 'data'=>$Event]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Event::find($data['id']);
        $model->status = 2;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = Event::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }
}
