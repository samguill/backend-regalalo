<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 19/03/18
 * Time: 10:44 AM
 */

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class StoreImageController extends Controller {

    public function index(){
        $store_id = Auth::user()->store->id;
        return view('store.multimedia.index', compact('store_id'));
    }

    public function lists(Request $request){
        $store_id = $request->input('id');
        $images = StoreImage::where('store_id', $store_id)->get();
        return response()->json(['status'=>'ok','data' => $images]);
    }

    public function upload(Request $request){
        $image = $request->file('file');
        $name = $image->getClientOriginalName();
        $store_id = Auth::user()->store->id;
        $store = Store::find($store_id);
        $ruc = $store->ruc;

        $path = "uploads/stores/" . $ruc . "/";

        $image->move($path , $image->getClientOriginalName());

        $model = StoreImage::create([
            'store_id' => $store_id,
            'image_path' => $path . $name
        ]);
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function delete_file(Request $request){
        $file_id = $request->input('id');

        $store_image = StoreImage::find($file_id);
        if(File::exists($store_image["image_path"])) File::delete($store_image["image_path"]);
        $store_image->delete();

        return response()->json(['status'=>"ok",'data'=>$store_image]);
    }

}