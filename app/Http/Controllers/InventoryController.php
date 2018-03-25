<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(){

        $products = Product::where('store_id',Auth::user()->store->id)->where('status', 0)->orWhere('status', 1)->get();

        return view('store.inventory.index', compact('products'));
    }

    public function lists(){

        $Inventory = Inventory::get();
        return response()->json($Inventory);
    }
}
