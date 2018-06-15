<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(){
        $data = [
            "title" => "Artículos"
        ];

        $categories = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["title"]
                ];
            }, Category::all()->toArray()
        );
        return view('admin.posts.index', compact('data', 'categories'));
    }

    public function lists(){
        $brands = Post::all();
        return response()->json($brands);
    }

    public function update(Request $request) {
        $data = $request->all();
        $brand = Post::find($data['id']);
        if($request->file('featured_image')){
            $photo = $request->file('featured_image');
            $path = "uploads/blog/posts/";
            $image = $photo->getClientOriginalName();
            $photo->move($path, $image);
            $brand->update([
                'featured_image' => $path . $image
            ]);
        }
        $data["slug"] = Str::slug($data["title"]);
        $data["user_id"] = Auth::user()->id;
        unset($data['id']);
        unset($data['featured_image']);
        if($brand->update($data))
            return response()->json(['status'=>'ok', 'data'=>$brand]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Post::find($data['id']);
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $data["slug"] = Str::slug($data["title"]);
            $data["user_id"] = Auth::user()->id;
            $post = Post::create($data);
            if($request->file('featured_image')){
                $photo = $request->file('featured_image');
                $path = "uploads/blog/posts/";
                $image = $photo->getClientOriginalName();
                $photo->move($path, $image);
                $post->update([
                    'featured_image' => $path . $image
                ]);
            }
        }catch(Exception $e) {
            return response()->json(['status'=>"error",'message'=>$e->getMessage()]);
        }
        return response()->json(['status'=>"ok",'data'=>$post]);
    }
}
