<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(){
        $data = [
            "title" => "Categorías"
        ];
        return view('admin.categories.index', compact('data'));
    }

    public function lists(){
        $brands = Category::all();
        return response()->json($brands);
    }

    public function update(Request $request) {
        $data = $request->all();
        $category = Category::find($data['id']);
        if($request->file('featured_image')){
            $photo = $request->file('featured_image');
            $path = "uploads/blog/categories/";
            $image = $photo->getClientOriginalName();
            $photo->move($path, $image);
            $category->update([
                'featured_image' => $path . $image
            ]);
        }
        $data["slug"] = Str::slug($data["title"]);
        $data["user_id"] = Auth::user()->id;
        unset($data['id']);
        unset($data['featured_image']);
        if($category->update($data))
            return response()->json(['status'=>'ok', 'data'=>$category]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Category::find($data['id']);
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $data["slug"] = Str::slug($data["title"]);
            $data["user_id"] = Auth::user()->id;
            $category = Category::create($data);
            if($request->file('featured_image')){
                $photo = $request->file('featured_image');
                $path = "uploads/brands/";
                $image = $photo->getClientOriginalName();
                $photo->move($path, $image);
                $category->update([
                    'featured_image' => $path . $image
                ]);
            }
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$category]);
    }
}
