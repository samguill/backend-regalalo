<?php

namespace App\Http\Controllers;

use App\Models\ProductCharacteristic;
use Illuminate\Http\Request;

class ProductCharacteristicController extends Controller
{
    public function index(){
        $data = [
            "title" => "Características de productos"
        ];
        return view('admin.productscharacteristics.index', compact('data'));
    }

    public function lists(){

        $productcharacteristics = ProductCharacteristic::where('status', 0)->orWhere('status', 1)->get();
        return response()->json($productcharacteristics);
    }

    public function values(Request $request){

        $id =  $request->input('id');
        $productcharacteristicvalues =ProductCharacteristic::where('id',$id)->with('values')->first();

        return view('admin.productscharacteristics.values', compact('productcharacteristicvalues'));
    }
}
