<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(){

        return view('store.orders.index');
    }

    public function lists(){

        $stores = Order::where('store_id',Auth::user()->store->id)->get();
        return response()->json($stores);
    }
}
