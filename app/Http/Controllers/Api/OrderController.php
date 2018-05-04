<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Store;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\UrbanerUtil;
use Illuminate\Support\Facades\DB;
use SoapClient;

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */

    public function generateOrder(Request $request){
        $order = $request->input('order');
        $store_branche_id = $request->input('store_branche_id');
        $orderdetails = $request->input('orderdetails');

        $branche = StoreBranch::find($store_branche_id);

        $store = Store::find($branche->store_id);
        $client = Client::find($order["client_id"]);

        $occode = Order::select(['id'])->orderBy('id', 'desc')->first();
        if($occode){
            $order['order_code'] = ($occode->id + 1) . date("Y");
        }else{
            $order['order_code'] = 1 . date("Y");
        }

        //Cabecera
        $data = Order::create($order);
        //Detalle
        foreach ($orderdetails as $orderdetail) {
            $orderdetail['order_id'] = $data->id;
            $od = OrderDetail::create($orderdetail);
        }

        //Clave SHA-2 de Wallet
        $claveSecretaWallet = $store->payme_wallet_password;

        $registerVerification = openssl_digest(
            $store->payme_comerce_id .
            $client->id .
            $client->email .
            $claveSecretaWallet,
            'sha512');

        // Si el estado de proceso con PayMe es 1
        // El pago irá a los servidores de integración
        /*if($store->payme_process_status == 1){
            $wsdl = 'https://integracion.alignetsac.com/WALLETWS/services/WalletCommerce?wsdl';
        }else {
            // Si el estado de proceso con PayMe es 2
            // El pago irá a los servidores de producción
            $wsdl = 'https://www.pay-me.pe/WALLETWS/services/WalletCommerce?wsdl';
        }*/

        $wsdl = 'https://integracion.alignetsac.com/WALLETWS/services/WalletCommerce?wsdl';
        $client_soap = new SoapClient($wsdl);

        $params = array(
            'idEntCommerce' => $store->payme_comerce_id,
            'codCardHolderCommerce'=> "prueba-" . time(),
            'names' => $client->first_name,
            'lastNames' => $client->last_name,
            'mail' => $client->email,
            'reserved1' => '',
            'reserved2' => '',
            'reserved3' => '',
            'registerVerification' => $registerVerification
        );
        $result = $client_soap->RegisterCardHolder($params);
        //Se definen todos los parametros obligatorios.
        $acquirerId = $store->payme_acquirer_id;
        $idCommerce = $store->payme_comerce_id;
        $purchaseOperationNumber = $data->order_code;
        $purchaseAmount = $data->total * 100;
        $purchaseCurrencyCode = '604';

        $claveSecretaPasarela = $store->payme_gateway_password;

        $purchaseVerification = openssl_digest($acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecretaPasarela, 'sha512');

        return response()->json([
            'status' => 'ok',
            'purchaseVerification' => $purchaseVerification,
            'acquirerId' => $acquirerId,
            'idCommerce' => $idCommerce,
            'purchaseOperationNumber' => $purchaseOperationNumber,
            'purchaseAmount' => $purchaseAmount,
            'shippingFirstName' => $client->first_name,
            'shippingLastName' => $client->last_name,
            'shippingEmail' => $client->email,
            'shippingAddress' => 'Direccion ABC',
            'userCommerce' => "CLI-" . $client->id,
            'userCodePayme' => $result->codAsoCardHolderWallet,
            'result' => $result
        ]);
    }

    public function store(Request $request){
        $response='';
        $data='';
        $order = $request->input('order');
        $orderdetails = $request->input('orderdetails');
        $destination_client = $request->input('destination_client');
        $store_branche_id = $request->input('store_branche_id');
        $delivery = $request->input('delivery');

        $store = Store::find($order['store_id']);
        $branch = $store->branches()->where('id', $store_branche_id)->first();

        //Codigo de orden
        $occode = Order::select(['id'])->orderBy('id', 'desc')->first();
        if($occode){
            $order['order_code'] = '0' . ($occode->id + 1) . '-' . date("Y");
        }else{
            $order['order_code'] = '0' . 1 . '-' . date("Y");
        }

        $destination_store_branch = [
            "contact_person" => $store->comercial_contact->name,
            "phone" => $store->comercial_contact->phone,
            "address" => $branch->address,
            "latlon" => $branch->latitude . ',' . $branch->longitude,
            "interior" => "",
            "special_instructions" => "",
            "email" => $branch->branch_email

        ];

        //return response()->json(['status' => 'ok', 'data' => $destination_store_branch]);

        DB::beginTransaction();
        try {
            //Cabecera
            $data = Order::create($order);
            //Detalle
            foreach ($orderdetails as $orderdetail) {
                $orderdetail['order_id'] = $data->id;
                $od = OrderDetail::create($orderdetail);
            }

            //Inventario
            $inventory = Inventory::where('product_id', $od['product_id'])->where('store_branche_id', $store_branche_id)->first();

            if(isset($inventory)){
                $inventory->update([
                    'quantity' => $inventory->quantity - $od['quantity']
                ]);

                InventoryMovement::create([
                    'inventory_id' => $inventory->id,
                    'quantity' => $od['quantity'],
                    'order_id'=>$data->id,
                    'movement_type' => 'E'
                ]);
            }

            //Order in Urbaner
            if($delivery){
                $json = [
                    "type" => "1",
                    "destinations" => [
                        $destination_store_branch,
                        $destination_client,
                    ],
                    "payment" => [
                        "backend" => "card",
                        "args" => [
                            "bankcard" => 261
                        ]
                    ],
                    "description" => "comida",
                    "vehicle_id" => "2",
                    "memo" => $data->order_code,
                    "is_return" => false,
                    "has_extended_search_time" => "true",
                ];
                $response = UrbanerUtil::apipost($json, UrbanerUtil::API_CLI_ORDER);
                $od->update([
                    "tracking_url" => $response->tracking,
                    "tracking_code" => $response->code,
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'order' => $data,
                'urbaner' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status' => 'ok',
            'order' => $data,
            'urbaner' => $response
        ]);

    }


    public function calculateDelivery(Request $request){
        $data = $request->all();
        $storebranch = StoreBranch::find($data['store_branche_id']);
        $destinations = [
            'destinations' => [
                    ['latlon' => $data['lat_origin'].','.$data['lon_origin']],
                    ['latlon' =>  $storebranch->latitude.','.$storebranch->longitude]
                    ],
            "package_type_id"=> 1,
            "is_return"=> false
        ];
        $response = UrbanerUtil::apipost($destinations, UrbanerUtil::API_CLI_PRICE);
        return response()->json($response);
    }

    public function orders(Request $request){

        $result= '';

        if($request->has('client_id')) {
        $client_id = $request->input('client_id');

            $result = Order::where('client_id',$client_id )->get();

        }
        return response()->json(['status'=>'ok', 'data'=>$result]);
    }

    public function orderdetails(Request $request){

        $result= '';

        if($request->has('order_id')) {
            $order_id = $request->input('order_id');

            $result = OrderDetail::where('order_id',$order_id )->get();

        }
        return response()->json(['status'=>'ok', 'data'=>$result]);
    }
}
