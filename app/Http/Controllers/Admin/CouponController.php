<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Coupon;
use App\Models\CouponMovement;
use App\Models\Service;
use App\Models\Store;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{

    public function index(){
        $stores = array_map(
            function ($item){
                return [
                    "value" => $item["id"],
                    "label" => $item["comercial_name"]
                ];
            }, Store::get()->toArray()
        );
        return view('admin.coupons.index', compact('stores'));
    }

    public function lists(){
        $coupons= DB::table('coupons')
            ->select(DB::raw('*'))
            ->addSelect(DB::raw('(select services.name from services where services.id=coupons.service_id) as service_name'))
            ->addSelect(DB::raw('(select store_branches.name from store_branches where store_branches.id=coupons.store_branche_id) as branch_name'))
            ->get();
        return response()->json($coupons);
    }

    public function branches_store(Request $request){
        $data = $request->all();
        $store_id = $data["store_id"];
        $branches = array_map(function($item){
            return [
                "value" => $item["id"],
                "label" => $item["name"]
            ];
        }, StoreBranch::where("store_id", $store_id)->get()->toArray());

        $services = array_map(function($item){
            return [
                "value" => $item["id"],
                "label" => $item["name"]
            ];
        }, Service::where("store_id", $store_id)->get()->toArray());
        return response()->json(["status" => "ok" , "branches" => $branches, "services" => $services]);
    }

    public function incoming(Request $request){
        $data = $request->all();
        $services = $data["coupons_services"];
        foreach ($services as $service){
            $coupon = Coupon::where("service_id", $service["service_id"])
                ->where("store_branche_id", $service["branch_id"])->first();
            if (isset($coupon)){
                $coupon->update([
                    "quantity" => $coupon->quantity + (int)$service["quantity"]
                ]);
                $coupon_id = $coupon->id;
            }else{
                $store = Coupon::create([
                    'service_id' => $service["service_id"],
                    'quantity' => $service["quantity"],
                    'store_branche_id' => $service["branch_id"]
                ]);
                $coupon_id = $store->id;
            }
            CouponMovement::create([
                'coupon_id' => $coupon_id,
                'quantity' => $service['quantity'],
                'movement_type' => 'I'
            ]);
        }
        return response()->json(["status" => "ok"]);
    }

    public function outgoing(Request $request){
        $data = $request->all();
        $services = $data['coupons_services'];
        foreach($services as $service) {
            $coupon = Coupon::where('service_id', $service['service_id'])
                ->where('store_branche_id', $service['branch_id'])->first();
            $coupon->update([
                'quantity' => $coupon->quantity - (int)$service['quantity'],
            ]);

            CouponMovement::create([
                'coupon_id' => $coupon->id,
                'quantity' => $service['quantity'],
                'movement_type' => 'E'
            ]);
        }
        return response()->json(["status" => "ok"]);
    }

    public function movements(Request $request){
        $coupon = Coupon::where('id', $request->input('id'))->with(['movements.order', 'service'])->first();
        return view('admin.coupons.show', compact('coupon'));
    }

}
