<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreController extends Controller {

    public function stores(){
        $stores = Store::where('status', 1)->get();
        return response()->json([
            'status'=>'ok',
            'stores' => $stores]);
    }

    public function products_store(Request $request){
        $store_slug = $request->input('slug');
        $store = Store::where('slug', $store_slug)->first();
        $products = Product::where('status', 0)->where('store_id', $store->id)->get();
        return response()->json([
            'status'=>'ok',
            'products' => $products,
            'store' => $store]);
    }
}
