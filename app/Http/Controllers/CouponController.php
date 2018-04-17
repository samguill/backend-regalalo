<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index(){
        $services = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["sku_code"] .' - '.$item["name"],
                    "sku_code" => $item["sku_code"],
                    "description" => $item["description"],
                    "price" => $item["price"]
                ];
            },Service::where('store_id',Auth::user()->store->id)->where('status', 0)->orWhere('status', 1)->get()->toArray()
        );

        $branches = Auth::user()->store->branches()->get()->toArray();

        foreach ($branches  as $branch){
            $branchesArray[] = $branch['id'];
        }

        $couponservices =
            array_map(
                function($item){

                    //  dd($item);
                    return [
                        "id" => $item["id"],
                        "value" =>  $item["product"]["name"].' - '.$item["branch"]["name"],
                        "quantity" => $item["quantity"],
                        "store_branche_id" => $item["store_branche_id"]
                    ];
                },Coupon::with('product','branch')->whereIn('store_branche_id',$branchesArray)->where('quantity','<>',0)->get()->toArray()
            );
        return view('store.coupons.index', compact('services','branches', 'couponservices'));
    }
}
