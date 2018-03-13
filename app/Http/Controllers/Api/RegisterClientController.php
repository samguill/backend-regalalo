<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterClientController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:clients',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $client = Client::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);
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
        try {
            $client = Client::where('email', $request->input('email'))->first();
            $valid_client = Hash::check($request->input('password'), $client->password);
            if(!$valid_client){
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
            $token = JWTAuth::fromUser($client);
        } catch (JWTException $e){
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }
}
