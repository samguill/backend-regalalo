<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(){
        $data = [
            "title" => "Páginas",
            "icon" => "fa-window-restore"
        ];
        return view('admin.pages.index', compact('data'));
    }

    public function lists(){
        $pages = Page::get();
        return response()->json($pages);
    }

    public function update(Request $request) {
        $data = $request->all();
        $page = Page::find($data['id']);
        unset($data['id']);
        $data['slug'] = Str::slug($data["title"]);
        if($page->update($data)){
            return response()->json(['status'=>'ok', 'data'=>$page]);
        }else{
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
        }

    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Page::find($data['id']);
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $data['slug'] = Str::slug($data["title"]);

            $model = Page::create($data);
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }
}
