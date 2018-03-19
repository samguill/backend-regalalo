<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 19/03/18
 * Time: 10:44 AM
 */

namespace App\Http\Controllers;

use App\Models\StoreImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


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
        $image = $request->hasFile('file');
        $image->move('uploads','123');
        return response()->json($image);
    }

}