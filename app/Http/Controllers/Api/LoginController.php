<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class LoginController extends Controller
{

    private $client;

    public function __construct(){
        $this->client = \Laravel\Passport\Client::find(1);
    }

    public function login(Request $request){
        $data = $request->all();

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
        return Route::dispatch($proxy);
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

}
