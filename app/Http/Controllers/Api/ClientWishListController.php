<?php

namespace App\Http\Controllers\Api;

use App\Models\ClientWishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;
use Illuminate\Support\Facades\Auth;

class ClientWishListController extends Controller {

    public function lists(){
        $user_login = Auth::user();
        $wishlist = ClientWishlist::with('product','service')->where('client_id', $user_login->id)->get();
        return response()->json($wishlist);
    }

    public function create(Request $request){
        $user_login = Auth::user();
        try{
            $data = $request->all();
            $data["client_id"] = $user_login->id;
            $model = ClientWishlist::create($data);
        }catch(Exception $e) {
            return response()->json(['status'=>"error",'message'=>$e->getMessage()]);
        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function delete(Request $request){
        $id = $request->input('id');
        $clientwish = ClientWishlist::find($id);
        $model = $clientwish;
        $clientwish->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }
}
