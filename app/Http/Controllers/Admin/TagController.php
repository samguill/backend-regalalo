<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;

class TagController extends Controller {

    public function index(){
        $data = [
            "title" => "Etiquetas"
        ];
        return view('admin.tags.index', compact('data'));
    }

    public function lists(){
        $tags = Tag::all();
        return response()->json($tags);
    }

    public function update(Request $request) {
        $data = $request->all();
        $tag = Tag::find($data['id']);
        unset($data['id']);
        if($tag->update($data))
            return response()->json(['status'=>'ok', 'data'=>$tag]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Tag::find($data['id']);
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $tag = Tag::create($data);
        }catch(Exception $e) {
            return response()->json(['status'=>"error",'message'=>$e->getMessage()]);
        }
        return response()->json(['status'=>"ok",'data'=>$tag]);
    }

}
