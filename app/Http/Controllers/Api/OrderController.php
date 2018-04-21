<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client as client;
class OrderController extends Controller
{
    public function store(Request $request){

        $order = $request->input('order');

        $orderdetails = $request->input('orderdetails');

        //Cabecera
        $data = Order::create([$order]);
        //Detalle
        foreach ($orderdetails as $orderdetail){

            $orderdetail['order_id'] = $data->id;

            OrderDetail::create([$orderdetail]);

        }

        return response()->json([
            'status'=>'ok',
            'order' => $data]);

    }


    public function calculateDelivery(Request $request){

        $client = new client();
        $data = $request->all();
        $store_branche_id = $data['store_branche_id'];

        $storebranch = StoreBranch::find($store_branche_id);

        $base_url_urbaner = env('BASE_URL_URBANER');
        $accessToken = env('TOKEN_URBANER');

      $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'token '.$accessToken
        ];

        $destinations = [
            'destinations' => [
                    ['latlon' => $data['lat_origin'].','.$data['lon_origin']],
                    ['latlon' =>  $storebranch->latitude.','.$storebranch->longitude]
                    ],
            "package_type_id"=> 1,
            "is_return"=> false
        ];


        $res = $client->request('POST', $base_url_urbaner.'/api/cli/price/',[
            'headers' =>$headers,
            'json' =>  $destinations
        ]);

        /*if($res->getStatusCode()>=401)
            return 'El servicio de Urbarne no responde';*/

        $body = $res->getBody();
        return $body;
    }
}
