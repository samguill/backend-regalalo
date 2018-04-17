<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function store(Request $request){

        $data = $request->all();

        $order = Order::create([$data]);

        return response()->json([
            'status'=>'ok',
            'order' => $order]);

    }
}
