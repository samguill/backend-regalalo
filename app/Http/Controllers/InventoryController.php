<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
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

        $Inventory = Inventory::get();
        return response()->json($Inventory);
    }

    public function incominginventory(Request $request)
    {

        $data = $request->input('products');

        foreach ($data['products'] as $product) {

            $inventory = Inventory::where('product_id', $product['product_id'])->where('branch_id', $product['branch_id'])->first();

            if (iseet($inventory)) {

                $inventory->update([

                    'quantity' => $inventory->quantity + $product['quantity'],

                ]);

                $inventory_id = $inventory->id;

            } else {


                $inventorycreate = Inventory::create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'branch_id' => $product['branch_id'],

                ]);
                $inventory_id = $inventorycreate->id;


            }

            InventoryMovement::create([
                'inventory_id' => $inventory_id,
                'branch_id' => $product['branch_id'],
                'quantity' => $product['quantity'],
                'tipo_movimiento' => 'I'
            ]);

        }
    }

}
