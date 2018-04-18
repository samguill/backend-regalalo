<?php

namespace App\Http\Controllers\Api;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
