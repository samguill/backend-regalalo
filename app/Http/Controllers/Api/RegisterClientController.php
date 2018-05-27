<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class RegisterClientController extends Controller
{

    private $client;

    public function __construct() {
        $this->client = \Laravel\Passport\Client::find(1);
    }

    public function register(Request $request){
        $data = $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:clients',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        Client::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);

        $params = [
            "grant_type" => "password",
            "client_id" => $this->client->id,
            "client_secret" => $this->client->secret,
            "username" => $data["email"],
            "password" => $data["password"],
            "scope" => "*"
        ];
        $request->request->add($params);

        $proxy = Request::create('oauth/token', 'POST');
        return Route::dispatch($proxy);
    }

    /*public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required'
        ]);
        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }
        try {
            $client = Client::where('email', $request->input('email'))->first();
            if(count($client) == 0){
                return response()->json(['status' => 'error', 'message' => "El usuario no existe"]);
            }else{
                $valid_client = Hash::check($request->input('password'), $client->password);
                if(!$valid_client){
                    return response()->json(['status' => 'error', 'message' => 'Datos incorrectos. Inténtalo de nuevo.']);
                }
                $token = JWTAuth::fromUser($client, ['client' => $client]);
            }
        } catch (JWTException $e){
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(['status'=>'ok', 'token' => $token]);
    }

    public function profile(Request $request){
        $token = $request->input('token');
        return response()->json($token);
    }*/
}
