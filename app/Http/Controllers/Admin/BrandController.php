<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(){
        $data = [
            "title" => "Marcas"
        ];
        return view('admin.brands.index', compact('data'));
    }

    public function lists(){
        $brands = Brand::all();
        return response()->json($brands);
    }

    public function update(Request $request) {
        $data = $request->all();
        $brand = Brand::find($data['id']);
        if($request->file('image')){
            $photo = $request->file('image');
            $path = "uploads/brands/";
            $image = $photo->getClientOriginalName();
            $photo->move($path, $image);
            $brand->update([
                'image' => $path . $image
            ]);
        }
        unset($data['id']);
        if($brand->update($data))
            return response()->json(['status'=>'ok', 'data'=>$brand]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Brand::find($data['id']);
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $brand = Brand::create([
                'name' => $data["name"],
                'slug' => Str::slug($data["name"]),
            ]);
            if($request->has('image')){
                $photo = $request->file('image');
                $path = "uploads/brands/";
                $image = $photo->getClientOriginalName();
                $photo->move($path, $image);
                $brand->update([
                    'image' => $path . $image
                ]);
            }
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$brand]);
    }
}
