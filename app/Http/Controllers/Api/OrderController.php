<?php

namespace App\Http\Controllers\Api;

use App\Mail\ShippingOrder;
use App\Models\Client;
use App\Models\ClientDirection;
use App\Models\Coupon;
use App\Models\CouponMovement;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use SoapClient;

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */

    public function generateOrder(Request $request){
        $client_login = Auth::user();
        $order = $request->input('order');
        $order["client_id"] = $client_login->id;
        $orderdetails = $request->input('orderdetails');
        $store_branche_id = $request->input('store_branche_id');
        $delivery = $request->input('delivery');

        //Obteniendo sucursal de tienda
        $branche = StoreBranch::find($store_branche_id);
        //Obteniendo tienda
        $store = Store::find($branche->store_id);
        //Obteniendo cliente
        $client = Client::find($order["client_id"]);

        // Creando orden de compra local
        $data =  $this->storeOrder($order, $orderdetails, $store_branche_id, $delivery);

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
        if($store->payme_process_status == 1){
            $wsdl = 'https://integracion.alignetsac.com/WALLETWS/services/WalletCommerce?wsdl';
        }else {
            // Si el estado de proceso con PayMe es 2
            // El pago irá a los servidores de producción
            $wsdl = 'https://www.pay-me.pe/WALLETWS/services/WalletCommerce?wsdl';
        }

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

    public function comerce_alignet(Request $request){
        //Storage::put('resp-' . time() . ".json", json_encode($request->all()));
        if($request->has('authorizationResult')){
            //Obteniendo la autorización de payme
            $authorizationResult = $request->input('authorizationResult');
            $purchaseOperationNumber = $request->input('purchaseOperationNumber');

            //Obteniendo la orden de acuerdo a la respuesta de payme
            $order = Order::where('order_code', $purchaseOperationNumber)->first();

            // Validacion de respuesta de payme
            // solo si el pago es autorizado se descuenta del inventario y se envía a urbaner
            if($authorizationResult === "00"){
                // Se actualiza la orden de compra de acuerdo de acuerdo a si ha elegido
                // delivery o no:
                if($order->delivery){
                    //P: Pending, A: Atended, R: Rejected payment, D: Delivery pendiente
                    $order->update([
                        'status' => 'D'
                    ]);

                    //Ademas se genera la orden en urbaner
                    $this->storeUrbaner($order);

                }else{
                    $order->update(['status' => 'A']);
                }
                //Actualizando el inventario o cupon de acuerdo al tipo de compra
                if($order->order_type=='product'){

                    $this->inventory($order);

                }else{

                    $this->coupon($order);
                }

                // Mensaje a cliente
                Mail::to(["marzioperez@gmail.com"])->send(new ShippingOrder($order, "client"));
                // Mensaje a la tienda
                Mail::to(["marzioperez@gmail.com"])->send(new ShippingOrder($order, "store"));
            }else{
                // Se actualiza la orden de compra de acuerdo como rechazado:
                //P: Pending, A: Atended, R: Rejected payment, D: Delivary pendiente
                $order->update(['status' => 'R']);
            }
        };

        return redirect()->away('https://regalalo.pe/mi-cuenta');
        //return redirect()->away('http://localhost:4200/#/mis-pedidos');
    }


    public function inventory($order){
        // Se obtiene la orden
        $order = Order::find($order->id);
        // Se obtiene el detalle de la orden
        $order_details = OrderDetail::where('order_id', $order->id)->get();
        foreach ($order_details as $od){
            $store_branche_id = $od['store_branche_id'];
            //Inventario
            $inventory = Inventory::where('product_id', $od['product_id'])->where('store_branche_id', $store_branche_id)->first();
            if(isset($inventory)){
                // Se realiza el movimiento del inventario de tipo E: Egreso
                $inventory->update([
                    'quantity' => $inventory->quantity - $od['quantity']
                ]);
                InventoryMovement::create([
                    'inventory_id' => $inventory->id,
                    'quantity' => $od['quantity'],
                    'order_id'=>$order->id,
                    'movement_type' => 'E'
                ]);
            }
        }
    }


    public function coupon($order){
        // Se obtiene la orden
        $order = Order::find($order->id);
        // Se obtiene el detalle de la orden
        $order_details = OrderDetail::where('order_id', $order->id)->get();
        foreach ($order_details as $od){
            $store_branche_id = $od['store_branche_id'];
            //Cupones
            $coupon = Coupon::where('service_id', $od['service_id'])->where('store_branche_id', $store_branche_id)->first();
            if(isset($coupon)){
                // Se realiza el movimiento de cupones de tipo E: Egreso
                $coupon->update([
                    'quantity' => $coupon->quantity - $od['quantity']
                ]);
                CouponMovement::create([
                    'coupon_id' => $coupon->id,
                    'quantity' => $od['quantity'],
                    'order_id'=>$order->id,
                    'movement_type' => 'E'
                ]);
            }
        }
    }

    public function storeUrbaner($order){

        $orderdetails = $order->orderdetails;

        $client = Client::find($order->client_id);

        $clientDirection = ClientDirection::find($order->client_direction_id);

        $destination_client = [
            'contact_person'=> $client->first_name . " " . $client->last_name,
            'phone'=> $client->phone,
            'address'=> $clientDirection->address,
            'latlon'=> $clientDirection->latitude . "," . $clientDirection->longitude,
            'email' =>$client->email
        ];

        foreach ($orderdetails  as $orderdetail) {
            $store_branche_id = $orderdetail->store_branche_id;
        }

        $store = Store::find($order['store_id']);
        $branch = $store->branches()->where('id', $store_branche_id)->first();

        //Origen del envío
        $destination_store_branch = [
            "contact_person" => $store->comercial_contact->name,
            "phone" => $branch->phone,
            "address" => $branch->address,
            "latlon" => $branch->latitude . ',' . $branch->longitude,
            "interior" => "",
            "special_instructions" => "",
            "email" => $branch->branch_email
        ];

        DB::beginTransaction();
        try {
            //Order in Urbaner
            $json = [
                "type" => "1",
                "destinations" => [
                    $destination_store_branch,
                    $destination_client,
                ],
                "payment" => [
                    "backend" => "card",
                    "args" => [
                        "bankcard" => 4902
                    ]
                ],
                "description" => "comida",
                "vehicle_id" => "2",
                "memo" => $order->order_code,
                "is_return" => false,
                "has_extended_search_time" => "true"
            ];
            //Storage::put('send-urbaner-' . time() . ".json", json_encode($json));
            $response = UrbanerUtil::apipost($json, UrbanerUtil::API_CLI_ORDER);
            //Storage::put('resp-urbaner-' . time() . ".json", json_encode($response));

            foreach ($orderdetails  as $orderdetail) {
                $od = OrderDetail::find($orderdetail->id);
                $od->update([
                    "tracking_url" => $response->tracking,
                    "tracking_code" => $response->code,
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'order' => $order,
                'urbaner' => $e->getMessage()
            ]);
        }

        DB::commit();

        return response()->json([
            'status' => 'ok',
            'order' => $order,
            'urbaner' => $response
        ]);

    }

    public function storeOrder($order, $orderdetails, $store_branche_id, $delivery){
        $store = Store::find($order['store_id']);
        $branch = $store->branches()->where('id', $store_branche_id)->first();
        //Generando Codigo de orden
        $occode = Order::select(['id'])->orderBy('id', 'desc')->first();
        if($occode){
            //$order['order_code'] = ($occode->id + 1) . date("Y");
            $order['order_code'] = ($occode->id + 1) . "2016";
        }else{
            //$order['order_code'] = 1 . date("Y");
            $order['order_code'] = 60 . "2016";
        }
        if($delivery){
            $order["delivery"] = $delivery;
            foreach ($orderdetails as $orderdetail) {
                $order['total'] = $order['total']+$orderdetail['price_delivery'];
            }
        }
        //Generando Cabecera de orden
        $data = Order::create($order);
        //Generando Detalle
        foreach ($orderdetails as $orderdetail) {
            $orderdetail['order_id'] = $data->id;
            $orderdetail['store_branche_id'] = $store_branche_id;
            $od = OrderDetail::create($orderdetail);
        }
        return $data;
    }


    public function calculateDelivery(Request $request){
        $data = $request->all();
        $storebranch = StoreBranch::find($data['store_branche_id']);
        $client_direction = ClientDirection::find($data["client_direction_id"]);
        $destinations = [
            'destinations' => [
                    ['latlon' => $client_direction->latitude .','. $client_direction->longitude],
                    ['latlon' =>  $storebranch->latitude.','.$storebranch->longitude]
                    ],
            "package_type_id"=> 1,
            "is_return"=> false
        ];
        $response = UrbanerUtil::apipost($destinations, UrbanerUtil::API_CLI_PRICE);
        return response()->json($response);
    }

    public function orders(){
        $user_login = Auth::user();
        $result = Order::with('store', 'clientdirection', 'orderdetails.product', 'orderdetails.service', 'orderdetails.branch')
            ->where('client_id',$user_login->id)->orderBy('created_at', 'DESC')
            ->get();
        return response()->json(['status'=>'ok', 'data'=>$result]);
    }
}
