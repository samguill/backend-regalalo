<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
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
        return view('admin.inventory.index', compact('stores'));
    }

    public function lists(){
        $inventory= DB::table('inventory')
            ->select(DB::raw('*'))
            ->addSelect(DB::raw('(select products.name from products where products.id=inventory.product_id) as product_name'))
            ->addSelect(DB::raw('(select store_branches.name from store_branches where store_branches.id=inventory.store_branche_id) as branch_name'))
            ->get();
        return response()->json($inventory);
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

        $products = array_map(function($item){
            return [
                "value" => $item["id"],
                "label" => $item["name"]
            ];
        }, Product::where("store_id", $store_id)->get()->toArray());
        return response()->json(["status" => "ok" , "branches" => $branches, "products" => $products]);
    }

    public function incoming(Request $request){
        $data = $request->all();
        $products = $data["inventory_products"];
        foreach ($products as $product){
            $inventory = Inventory::where("product_id", $product["product_id"])
                ->where("store_branche_id", $product["branch_id"])->first();
            if (isset($inventory)){
                $inventory->update([
                    "quantity" => $inventory->quantity + (int)$product["quantity"]
                ]);
                $inventory_id = $inventory->id;
            }else{
                $store = Inventory::create([
                    'product_id' => $product["product_id"],
                    'quantity' => $product["quantity"],
                    'store_branche_id' => $product["branch_id"]
                ]);
                $inventory_id = $store->id;
            }
            InventoryMovement::create([
                'inventory_id' => $inventory_id,
                'quantity' => $product['quantity'],
                'movement_type' => 'I'
            ]);
        }
        return response()->json(["status" => "ok"]);
    }

    public function outgoing(Request $request){
        $data = $request->all();
        $products = $data['inventory_products'];
        foreach($products as $product) {
            $inventory = Inventory::where('id', $product['product_id'])
                ->where('store_branche_id', $product['branch_id'])->first();
            $inventory->update([
                'quantity' => $inventory->quantity - (int)$product['quantity'],
            ]);

            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'quantity' => $product['quantity'],
                'movement_type' => 'E'
            ]);
        }
        return response()->json(["status" => "ok"]);
    }

    public function movements(Request $request){
        $inventory = Inventory::where('id', $request->input('id'))->with(['movements.order', 'product'])->first();
        return view('admin.inventory.show', compact('inventory'));
    }

}
