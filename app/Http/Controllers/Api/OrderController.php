<?php

namespace App\Http\Controllers\Api;

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

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
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

        $order['order_code'] = '0' . ($occode->id + 1) . '-' . date("Y");

        $destination_store_branch = [
            "contact_person" => $store->comercial_contact->name,
            "phone" => $store->comercial_contact->phone,
            "address" => $branch->address,
            "latlon" => $branch->latitude . ',' . $branch->longitude,
            "interior" => "",
            "special_instructions" => "",
            "email" => $branch->branch_email

        ];

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
                //"programmed_date" => "2017-11-10 13:00:00",
                "is_return" => false,
                "has_extended_search_time" => "true",
               // "coupon" => "MY_REGISTERED_COUPON"
            ];
            $response = UrbanerUtil::apipost($json, UrbanerUtil::API_CLI_ORDER);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            //throw new \Exception($e->getMessage());
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
        $store_branche_id = $data['store_branche_id'];

        $storebranch = StoreBranch::find($store_branche_id);

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

        return $response;
    }

    public function paymentGenerate(Request $request){
        //$store_id = Store::find()
        $data = $request->all();

        $store = Store::find($data["store_id"]);

        $idEntCommerce = '420';
        $codCardHolderCommerce = 'pruebawallet1';
        $names = 'Arturo';
        $lastNames = 'García';
        $mail = 'arturo.garcia@regalalo.pe';

        //Clave SHA-2 de Wallet
        $claveSecretaWallet = 'nXBqMLdasxQQurMz.422979735';

        $registerVerification = openssl_digest($idEntCommerce . $codCardHolderCommerce . $mail . $claveSecretaWallet, 'sha512');
        //Referencia al Servicio Web de Wallet
        $wsdl = 'https://integracion.alignetsac.com/WALLETWS/services/WalletCommerce?wsdl';

        $client = new \SoapClient($wsdl);

        $params = array(
            'idEntCommerce'=>$idEntCommerce,
            'codCardHolderCommerce'=>$codCardHolderCommerce,
            'names'=>$names,
            'lastNames'=>$lastNames,
            'mail'=>$mail,
            'registerVerification'=>$registerVerification
        );

        //Consumo del metodo RegisterCardHolder
        $result = $client->RegisterCardHolder($params);

        //Se definen todos los parametros obligatorios.
        $acquirerId = '144'; //29
        $idCommerce = '9092';
        $purchaseOperationNumber = '10542';
        $purchaseAmount = $data["total"];
        $purchaseCurrencyCode = '604';

        $claveSecretaPasarela = 'LbABXJkbcaFRLJchXCb?679658268743';

        $purchaseVerification = openssl_digest($acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecretaPasarela, 'sha512');

        $data = array(
            "purchaseVerification" => $purchaseVerification,
            "result" => $result
        );
        return response()->json($data);

        return response()->json($response);
    }
}
