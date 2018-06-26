<?php
/**
 * Created by PhpStorm.
 * User: Isaac
 * Date: 24/04/2018
 * Time: 12:14 AM
 */

namespace App\Utils;
use GuzzleHttp\Client;

class UrbanerUtil
{
    const API_CLI_PRICE = "/api/cli/price/";
    const API_CLI_ORDER = "/api/cli/order/";

    public static function apipost($json,$url){

        $client = new Client();
        $accessToken = env('TOKEN_URBANER');
        $base_url_urbaner = env('BASE_URL_URBANER');


        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'token '.$accessToken
        ];

        $res = $client->request('POST', $base_url_urbaner.$url,[
            'headers' =>$headers,
            'json' =>  $json,
            'verify' => false,
        ]);

        /*if($res->getStatusCode()>=401)
            return 'El servicio de Urbarne no responde';*/

        $body = $res->getBody();
        $data = \GuzzleHttp\json_decode($body->getContents());
        return $data;
    }

}