<?php

namespace App\Http\Controllers\Api;

use App\Mail\CustomerRegistration;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
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

        $client = Client::create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ]);

        //Enviando correo de bienvenida a la plataforma al cliente
        Mail::to($client->email)->send(new CustomerRegistration($client));

        $params = [
            "grant_type" => "password",
            "client_id" => $this->client->id,
            "client_secret" => $this->client->secret,
            "username" => $data["email"],
            "password" => $data["password"],
            "scope" => "*"
        ];
        $request->request->add($params);

        $get_client = Client::with('directions')->find($client->id);

        $proxy = Request::create('oauth/token', 'POST');
        $response = Route::dispatch($proxy);
        $json = (array) json_decode($response->getContent());
        $json["client"] = $get_client;
        $response->setContent(json_encode($json));
        return $response;
    }
}
