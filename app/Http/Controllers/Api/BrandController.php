<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller {

    public function products(Request $request){
        $data = $request->all();
        $brand = Brand::where("slug", $data["slug"])->first();
        $products = Product::where("brand_id", $brand->id)->get();
        $res = SearchController::paginate($products);
        return response()->json($res);
    }

}
