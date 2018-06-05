<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{

    private $client;

    public function __construct(){
        $this->client = \Laravel\Passport\Client::find(1);
    }

    public function login(Request $request){
        $data = $request->all();
        $sucess = false;

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $params = [
            "grant_type" => "password",
            "client_id" => $this->client->id,
            "client_secret" => $this->client->secret,
            "username" => $data["username"],
            "password" => $data["password"],
            "scope" => "*"
        ];
        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');
        $response = Route::dispatch($proxy);

        $client = Client::with('directions')->where('email', $data["username"])->first();
        if($client){
            if(Hash::check($data["password"], $client->password)){
                $sucess = true;
            }else{
                $sucess = false;
            }
        }else{
            $sucess = false;
        }

        if($sucess){
            $json = (array) json_decode($response->getContent());
            $json["client"] = $client;
            $response->setContent(json_encode($json));
            return $response;
        }else{
            return $response;
        }
    }

    public function refresh(Request $request){
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        $params = [
            "grant_type" => "refresh_token",
            "client_id" => $this->client->id,
            "client_secret" => $this->client->secret,
            "username" => request("username"),
            "password" => request("password"),
            "scope" => "*"
        ];
        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');
        return Route::dispatch($proxy);
    }

    public function logout(Request $request){
        $accessToken = Auth::user()->token();
        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(["revoked" => true]);
        $accessToken->revoke();
        return response()->json(["status" => "ok"], 200);
    }

    public function profile(){
        $user_login = Auth::user();
        return response()->json($user_login);
    }

    public function update_profile(Request $request){
        $user_login = Auth::user();
        $data = $request->all();

        if($request->input('password') == '' && $request->input('password') == null){
            $data = $request->except(['password']);
        }
        $user = Client::with('directions')->find($user_login->id);
        unset($user_login->id);
        if($user->update($data)){
            return response()->json(['status'=>'ok', 'data'=>$user]);
        }else{
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
        }
    }

}
