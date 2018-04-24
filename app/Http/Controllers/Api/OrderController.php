<?php

namespace App\Http\Controllers\Api;

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
        $order = $request->input('order');
        $orderdetails = $request->input('orderdetails');
        $destination_client = $request->input('destination_client');
        $store_branche_id = $request->input('store_branche_id');

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
            $data = Order::create([$order]);
            //Detalle
            foreach ($orderdetails as $orderdetail) {
                $orderdetail['order_id'] = $data->id;
                $od = OrderDetail::create([$orderdetail]);

            }
            //Inventario

            $inventory = Inventory::where('product_id', $od['product_id'])->where('store_branche_id', $store_branche_id)->first();

            $inventory->update([
                'quantity' => $inventory->quantity - $od['quantity']
            ]);

            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'quantity' => $od['quantity'],
                'order_id'=>$data->id,
                'movement_type' => 'E'
            ]);


            //Order in Urbaner
            $json = [
                "type" => "1",
                "destinations" => [
                    $destination_store_branch,
                    $destination_client,
                ],
                "payment" => ["backend" => "purse"],
                "description" => "comida",
                "vehicle_id" => "2",
                "memo" => $data->order_code,
                //"programmed_date" => "2017-11-10 13:00:00",
                "is_return" => false,
                "has_extended_search_time" => "true",
                "coupon" => "MY_REGISTERED_COUPON"
            ];
            $response = UrbanerUtil::apipost($json, UrbanerUtil::API_CLI_ORDER);

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
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
}
