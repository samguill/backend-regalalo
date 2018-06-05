<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\ClientDirection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class ClientDirectionController extends Controller {

    public function store(Request $request){
        $user_login = Auth::user();
        try{
            $data = $request->all();
            $data["client_id"] = $user_login->id;
            ClientDirection::create($data);
            $client = Client::with('directions')->find($user_login->id);
        }catch(Exception $e) {
            return response()->json(['status'=>"error",'message'=>$e->getMessage()]);
        }
        return response()->json(['status'=>"ok",'data'=>$client]);
    }

    public function directions(){
        $user_login = Auth::user();
        $data =  ClientDirection::where('client_id',$user_login->id)->get();
        return response()->json([
            'status'=>'ok',
            'directions' => $data
        ]);
    }
    
}
