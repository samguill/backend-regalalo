<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            "title" => "Escritorio",
            "icon" => "fa-dashboard"
        ];
        if(Auth::user()->type == 'A') {
            $stores = Store::where('status', 1)->get();
            $products = Product::get();
            $services = Service::get();
            $clients = Client::get();
            return view('admin.home', compact('data', 'stores', 'products', 'services', 'clients'));

        }else{
            $store_id = Auth::user()->store->id;
            $products = Product::where('store_id', $store_id)->get();
            $services = Service::where('store_id', $store_id)->get();
            $branches = StoreBranch::where('store_id', $store_id)->get();
            $orders = Order::where('store_id', $store_id)->get();
            return view('store.home', compact('data', 'products', 'services', 'branches', 'orders'));

        }
    }
}
