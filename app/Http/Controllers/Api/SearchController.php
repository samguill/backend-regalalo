<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function quicksearch(Request $request)
    {
        //Tabla principal del productos
        $query = DB::table('products')->select(DB::raw('*'));


        if($request->has('latitude') and $request->has('longitude')) {

            $latitude =   $request->input('latitude');
            $longitude =   $request->input('longitude');

            $query->addSelect(
                DB::raw(
                    '(select (acos(sin(radians(store_branches.latitude)) * sin(radians('.$latitude.')) +
                    cos(radians(store_branches.latitude)) * cos(radians('.$latitude.')) *
                    cos(radians(store_branches.longitude) - radians('.$longitude.'))) * 6378) 
                    from store_branches where store_branches.id =  inventory.store_branche_id) as distance'));

            $query->orderBy('distance', 'dsc');
        }


        //Se busca por descripción y el nombre del producto
        if($request->has('description') and $request->has('name'))        {

            $description = $request->input('description');
            $name = $request->input('name');

            $query->where('name','LIKE','%'.$name.'%');
            $query->orWhere('description','LIKE','%'.$description.'%');


        }

        //inventario
        $query->addSelect(DB::raw('IFNULL(inventory.quantity,0) as quantity'));

        $query->leftJoin('inventory','inventory.product_id','=','products.id');

        $result = $query->paginate(15);
        //$result = $query->tosql();

        return response()->json(['status'=>'ok', 'data'=>$result]);


        //Tabla principal del servicios
        //$query = DB::table('services')->select(DB::raw('*'));
    }
}
