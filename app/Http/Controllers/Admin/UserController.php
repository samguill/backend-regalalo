<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Mockery\Exception;

class UserController extends Controller
{
    public function index(){
        $data = [
            "title" => "Usuarios",
            "icon" => "fa-user-secret"
        ];
        return view('admin.users.index', compact('data'));
    }

    public function lists(){
        $stores = User::where('status', 1)->where('type','A')->get();
        return response()->json($stores);
    }

    public function update(Request $request) {
        $data = $request->all();
        if($request->input('password') == '' && $request->input('password') == null){
            $data = $request->except(['password']);
        }
        $user = User::find($data['id']);
        unset($data['id']);
        if($user->update($data)){
            return response()->json(['status'=>'ok', 'data'=>$user]);
        }else{
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
        }

    }

    public function delete(Request $request){
        $data = $request->all();
        $model = User::find($data['id']);
        $model->status = 0;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = User::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }
}
