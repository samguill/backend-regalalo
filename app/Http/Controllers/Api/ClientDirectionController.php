<?php

namespace App\Http\Controllers\Api;

use App\Models\ClientDirection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientDirectionController extends Controller
{
    public function store(Request $request){

        $direction = $request->input('direction');
        $data =  ClientDirection::create([$direction]);
        return response()->json([
            'status'=>'ok',
            'directions' => $data]);

    }

    public function directions(Request $request){

        $client_id = $request->input('client_id');

        $data =  ClientDirection::where('client_id',$client_id)->get();

        return response()->json([
            'status'=>'ok',
            'directions' => $data]);

    }



}
