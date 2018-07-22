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
        $page = null;
        if($data["page"]){
            $page = $data["page"];
        }
        $res = SearchController::paginate($products, 16, $page);
        return response()->json(['status'=>'ok', 'data'=>$res]);
    }

}
