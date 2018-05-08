<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponMovement;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        if (count($branches) > 0){
            $couponservices = array_map(
                function($item){
                    return [
                        "id" => $item["id"],
                        "value" =>  $item["service"]["name"].' - '.$item["branch"]["name"],
                        "quantity" => $item["quantity"],
                        "store_branche_id" => $item["store_branche_id"]
                    ];
                },Coupon::with('service','branch')->whereIn('store_branche_id',$branchesArray)->where('quantity','<>',0)->get()->toArray()
            );
        }else{
            $couponservices = [];
        }


        return view('store.coupons.index', compact('services','branches', 'couponservices'));
    }



    public function lists(){

        $branches = Auth::user()->store->branches()->get();
        foreach ($branches  as $branch){
            $branchesArray[] = $branch->id;
        }


        $data= DB::table('coupons')
            ->select(DB::raw('*'))
            ->addSelect(DB::raw('(select services.name from services where services.id=coupons.service_id) as service_name'))
            ->addSelect(DB::raw('(select store_branches.name from store_branches where store_branches.id=coupons.store_branche_id) as branch_name'))
            ->whereIn('store_branche_id',$branchesArray)
            ->get();

        return response()->json($data);
    }


    public function incomingcoupons(Request $request)
    {

        $services = $request->input('services');

        foreach ($services as $service) {

            $coupon = Coupon::where('service_id', $service['value'])->where('store_branche_id', $service['branchValue'])->first();

            if (isset($coupon)) {

                $coupon->update([

                    'quantity' => $coupon->quantity + $service['quantity'],

                ]);

                $coupon_id = $coupon->id;

            } else {


                $couponcreate = Coupon::create([
                    'service_id' => $service['value'],
                    'quantity' => $service['quantity'],
                    'store_branche_id' => $service['branchValue'],

                ]);
                $coupon_id = $couponcreate->id;


            }

            CouponMovement::create([
                'coupon_id' => $coupon_id,
                'quantity' => $service['quantity'],
                'movement_type' => 'I'
            ]);

        }

        return response()->json(['status' => 'ok','data' => $coupon]);
    }


    public function outgoingcoupons(Request $request){

        $data= $request->input('services');

        foreach($data as $service) {

            $coupon = Coupon::where('id', $service['value'])->where('store_branche_id', $service['store_branche_id'])->first();

            $coupon->update([

                'quantity' =>  $coupon->quantity - $service['quantity'],

            ]) ;

            CouponMovement::create([
                'coupon_id' => $coupon->id,
                'quantity' => $coupon['quantity'],
                'movement_type' => 'E'
            ]);

        }

        return response()->json(['status' => 'ok','data' => $coupon]);

    }


    public function movements(Request $request)
    {
        $id = $request->input('id');
        $coupon = Coupon::where('id', $id)->with(['movements.order', 'service'])->first();

        return view('store.coupons.show', compact('coupon'));
    }
}
