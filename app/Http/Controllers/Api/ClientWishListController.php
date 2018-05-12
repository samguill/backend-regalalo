<?php

namespace App\Http\Controllers\Api;

use App\Models\ClientWishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientWishListController extends Controller
{
    public function lists(Request $request){

        $clientewishlist = '';
        $data = $request->all();

        if($request->has('client_id'))
            $clientewishlist = ClientWishlist::with('product','service','client' )->where('client_id', $data['client_id'] )->get();

        return response()->json($clientewishlist);
    }

    public function create(Request $request){
        try{
            $model = ClientWishlist::create($request->all());
        }catch(Exception $e) {

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
