<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function quicksearch(Request $request)
    {

        $products = $this->query('products','product_id','inventory',$request);

        $services = $this->query('services','service_id','coupons',$request);

        $result  = $services->union($products)->get();

        $result = $this->paginate($result);

        return response()->json(['status'=>'ok', 'data'=>$result]);


        //Tabla principal del servicios
        //$query = DB::table('services')->select(DB::raw('*'));
    }

    public function query($table, $field_id,$store_table,$request){

        $query = DB::table($table)->select([$table.'.id','sku_code','name','description','slug','featured_image','price','discount','store_id','store_branche_id']);

        if($request->has('latitude') and $request->has('longitude')) {

            $latitude =   $request->input('latitude');
            $longitude =   $request->input('longitude');

            $query->addSelect(
                DB::raw(
                    '(select (acos(sin(radians(store_branches.latitude)) * sin(radians('.$latitude.')) +
                    cos(radians(store_branches.latitude)) * cos(radians('.$latitude.')) *
                    cos(radians(store_branches.longitude) - radians('.$longitude.'))) * 6378) 
                    from store_branches where store_branches.id =  '.$store_table.'.store_branche_id) as distance'));

            $query->orderBy('distance', 'desc');
        }


        //Se busca por descripción y el nombre
        if($request->has('searchtext')){

            $searchtext = $request->input('searchtext');

            $query->leftJoin('store','store.id','=',$table.'.store_id');

            $query->where('name','LIKE','%'.$searchtext.'%');
            $query->orWhere('description','LIKE','%'.$searchtext.'%');
            $query->orWhere('store.comercial_name','LIKE','%'.$searchtext.'%');


        }

        $query->addSelect(DB::raw('\''.$table.'\' as type'));

        //precio con descuento


        $query->addSelect(DB::raw('IF(discount >0,price*(1-discount/100),0) as discount_price'));

        //inventario
        $query->addSelect(DB::raw('IFNULL('.$store_table.'.quantity,0) as quantity'));


        $query->leftJoin($store_table,$store_table.'.'.$field_id,'=',$table.'.id');


        return $query;

    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
