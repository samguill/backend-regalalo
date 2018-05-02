<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        foreach ($branches  as $branch){
            $branchesArray[] = $branch['id'];
        }

        $inventoryproducts =
            array_map(
                function($item){

                  //  dd($item);
                    return [
                        "id" => $item["id"],
                        "value" =>  $item["product"]["name"].' - '.$item["branch"]["name"],
                        "quantity" => $item["quantity"],
                        "store_branche_id" => $item["store_branche_id"]
                    ];
                },Inventory::with('product','branch')->whereIn('store_branche_id',$branchesArray)->where('quantity','<>',0)->get()->toArray()
            );
        return view('store.inventory.index', compact('products','branches', 'inventoryproducts'));
    }

    public function lists(){

        $branches = Auth::user()->store->branches()->get();
        foreach ($branches  as $branch){
        $branchesArray[] = $branch->id;
        }


        $Inventory= DB::table('inventory')
            ->select(DB::raw('*'))
            ->addSelect(DB::raw('(select products.name from products where products.id=inventory.product_id) as product_name'))
            ->addSelect(DB::raw('(select store_branches.name from store_branches where store_branches.id=inventory.store_branche_id) as branch_name'))
            ->whereIn('store_branche_id',$branchesArray)
            ->get();

        return response()->json($Inventory);
    }

    public function movements(Request $request)
    {
        $id = $request->input('id');
        $inventory = Inventory::where('id', $id)->with(['movements.order', 'product'])->first();

        return view('store.inventory.show', compact('inventory'));
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
                'movement_type' => 'I'
            ]);

        }

        return response()->json(['status' => 'ok','data' => $inventory]);
    }


    public function outgoinginventory(Request $request){

        $data= $request->input('products');

        foreach($data as $product) {

            $inventory = Inventory::where('id', $product['value'])->where('store_branche_id', $product['store_branche_id'])->first();

            $inventory->update([

                'quantity' =>  $inventory->quantity - $product['quantity'],

            ]) ;

            InventoryMovement::create([
                'inventory_id' => $inventory->id,
                'quantity' => $product['quantity'],
                'movement_type' => 'E'
            ]);

        }

        return response()->json(['status' => 'ok','data' => $inventory]);

    }


    public function listProductInventory(Request $request){
        $product_id = $request->input('product_id');

        $productinventory = Inventory::where('product_id',$product_id)->first();
    }

}
