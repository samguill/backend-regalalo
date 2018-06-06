<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(){

        return view('admin.orders.index');
    }

    public function lists(){

        $stores = Order::get();
        return response()->json($stores);
    }

    public function show(Request $request){
        $id = $request->input('id');
        $order = Order::find($id);
        if($order){
        return view('admin.orders.show',compact('order'));
        }else{
            return redirect('/');
        }

    }
}
