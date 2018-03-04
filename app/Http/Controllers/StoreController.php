<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 3/03/18
 * Time: 21:15
 */

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Store;

class StoreController extends Controller
{

    public function index(){
        $stores = Store::get();
        return view('stores.index', compact('stores'));
    }

    public function lists(){
        $stores = Store::get();
        return response()->json($stores);
    }

}