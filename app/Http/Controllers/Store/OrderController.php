<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
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

    public function show(Request $request){
        $id = $request->input('id');
        $order = Order::find($id);

        if(isset($order) and Auth::user()->store->id ==$order->store_id){
        return view('store.orders.show',compact('order'));

        } else{
            return redirect('/');
        }

    }
}
