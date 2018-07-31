<?php

namespace App\Http\Controllers\Admin;

use App\Models\BlogImage;
use App\Models\Store;
use App\Models\StoreImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class MultimediaController extends Controller {

    public function index(){
        $stores = array_map(
            function ($item){
                return [
                    "value" => $item["id"],
                    "label" => $item["comercial_name"]
                ];
            }, Store::get()->toArray()
        );
        return view('admin.multimedia.index', compact('stores'));
    }

    public function get_images(Request $request){
        $data = $request->all();
        if ($data["store_id"] == 0){
            $images = BlogImage::get();
        }else{
            $images = StoreImage::where("store_id", $data["store_id"])->get();
        }
        return response()->json(["status" => "ok", "data" => $images]);
    }

    public function upload(Request $request){
        $image = $request->file('file');
        $name = $this->clean_image_name($image->getClientOriginalName()) . "." . $image->getClientOriginalExtension();
        $store_id = $request->input("store_id");
        if ($store_id == 0){
            $path = "uploads/blog/";
            $image->move($path , $name);
            $model = BlogImage::create([
                'image_path' => $path . $name
            ]);
        }else{
            $store = Store::find($store_id);
            $ruc = $store->ruc;
            $path = "uploads/stores/" . $ruc . "/";
            $image->move($path , $name);
            $model = StoreImage::create([
                'store_id' => $store_id,
                'image_path' => $path . $name
            ]);
        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function delete_file(Request $request){
        $file_id = $request->input('id');
        $store_id = $request->input('store_id');
        if ($store_id == 0){
            $image = BlogImage::find($file_id);
            if(File::exists($image["image_path"])) File::delete($image["image_path"]);
            $image->delete();
        }else{
            $image = StoreImage::find($file_id);
            if(File::exists($image["image_path"])) File::delete($image["image_path"]);
            $image->delete();
        }
        return response()->json(['status'=>"ok",'data'=>$image]);
    }

    public function clean_image_name($name){
        $string = str_replace(" ", "-", $name);
        return preg_replace('/[^A-Za-z0-9\-]/', "", $string);
    }

}
