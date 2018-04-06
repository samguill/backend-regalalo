<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index(){



       $products = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["sku_code"] .' - '.$item["name"],
                    "sku_code" => $item["sku_code"],
                    "description" => $item["description"],
                    "price" => $item["price"]
                ];
            },Product::where('store_id',Auth::user()->store->id)->where('status', 0)->orWhere('status', 1)->get()->toArray()
        );

       $branches = Auth::user()->store->branches()->get()->toArray();

        return view('store.inventory.index', compact('products','branches'));
    }

    public function lists(){

        $branches = Auth::user()->store->branches()->get();
        foreach ($branches  as $branch){
        $branchesArray[] = $branch->id;
        }


        $Inventory = Inventory::whereIn('store_branche_id',$branchesArray)->with(['branch:id,name','product:id,name'])->get();

        return response()->json($Inventory);
    }

    public function incominginventory(Request $request)
    {

        $products = $request->input('products');

        foreach ($products as $product) {

            $inventory = Inventory::where('product_id', $product['value'])->where('store_branche_id', $product['branchValue'])->first();

            if (isset($inventory)) {

                $inventory->update([

                    'quantity' => $inventory->quantity + $product['quantity'],

                ]);

                $inventory_id = $inventory->id;

            } else {


                $inventorycreate = Inventory::create([
                    'product_id' => $product['value'],
                    'quantity' => $product['quantity'],
                    'store_branche_id' => $product['branchValue'],

                ]);
                $inventory_id = $inventorycreate->id;


            }

            InventoryMovement::create([
                'inventory_id' => $inventory_id,
                'quantity' => $product['quantity'],
                'tipo_movimiento' => 'I'
            ]);

        }

        return response()->json(['status' => 'ok','data' => $inventory]);
    }

}
