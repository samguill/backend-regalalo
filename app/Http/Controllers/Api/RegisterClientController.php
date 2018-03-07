<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterClientController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:clients',
            'name' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        Client::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);
        $client = Client::first();
        $token = JWTAuth::fromUser($client);
        return response()->json(['status'=>'ok', 'token' => $token]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors());
        }
        $credentials = $request->only('email', 'password');
        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e){
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }
}
