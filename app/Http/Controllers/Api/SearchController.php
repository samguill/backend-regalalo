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

        //Quitando doble o triple espacio y separando el string palabra por palabra
        $searchValues = preg_split('/\s+/', $request->input('searchtext'), -1, PREG_SPLIT_NO_EMPTY);

        $stores = DB::table('stores')
            ->where(function($q) use ($searchValues) {
                foreach ($searchValues as $value) {
                    $q->orWhere('comercial_name', 'LIKE', "%{$value}%");

                }
            })
            ->whereNull('deleted_at')
            ->whereNotNull("logo_store")->get();

        if($request->has('latitude') and $request->has('longitude')) {
            $field_order = ['field'=>'distance','order'=>'DESC'];
        }else{
            $field_order = ['field'=>'price','order'=>'ASC'];
        }

        $result  = $services->union($products)
            ->orderBy($field_order['field'],$field_order['order'])
            ->get();

        $data['items'] = $this->paginate($result);

        $data['stores'] = $this->paginate($stores);

        return response()->json(['status'=>'ok', 'data'=>$data]);


        //Tabla principal del servicios
        //$query = DB::table('services')->select(DB::raw('*'));
    }

    public function query($table, $field_id = null,$store_table = null,$request){

        $query = DB::table($table)->select([$table.'.id','sku_code','name','description',$table.'.slug','featured_image','price','discount','store_id','store_branche_id']);

        if($request->has('latitude') and $request->has('longitude')) {

            $latitude =   $request->input('latitude');
            $longitude =   $request->input('longitude');

            $query->addSelect(
                DB::raw(
                    '(select (acos(sin(radians(store_branches.latitude)) * sin(radians('.$latitude.')) +
                    cos(radians(store_branches.latitude)) * cos(radians('.$latitude.')) *
                    cos(radians(store_branches.longitude) - radians('.$longitude.'))) * 6378) 
                    from store_branches where store_branches.id =  '.$store_table.'.store_branche_id) as distance'));


        }


        $query->addSelect(
            DB::raw(
                '(select EXISTS (select 1 from store_branches, branch_opening_hours
                where branch_opening_hours.store_branche_id = store_branches.id
                AND  branch_opening_hours.weekday = WEEKDAY(CURDATE())
                AND  (CURTIME() >= branch_opening_hours.start_hour ) AND ( CURTIME() <= branch_opening_hours.end_hour)
                AND branch_opening_hours.deleted_at IS NULL
                LIMIT 1)) as open'));


        //Se busca por descripción y el nombre
        if($request->has('searchtext')){

            $searchtext = $request->input('searchtext');

            //Quitando doblo o triple espacio y separando el string palabra por palabra
            $searchValues = preg_split('/\s+/', $searchtext, -1, PREG_SPLIT_NO_EMPTY);

            $query->where(function($q) use ($searchValues) {
                foreach ($searchValues as $value) {
                $q->orWhere('name', 'LIKE', "%{$value}%");
                $q->orWhere('description', 'LIKE', "%{$value}%");
                $q->orWhere('tags', 'LIKE', "%{$value}%");

                }
            });
        }

        $query->addSelect(DB::raw('\''.$table.'\' as type'));

        //precio con descuento
        $query->addSelect(DB::raw('IF(discount >0,price*(1-discount/100),0) as discount_price'));

        //inventario
        $query->addSelect(DB::raw('IFNULL('.$store_table.'.quantity,0) as quantity'));


        $query->leftJoin($store_table,function($join) use ($store_table,$field_id,$table){
            $join->on($table.'.id','=',DB::raw('(SELECT id FROM '.$store_table.' WHERE '.$table.'.id = '.$store_table.'.'.$field_id.' LIMIT 1)'));
        });

        $query->whereNull('deleted_at');

        return $query;

    }

    public static function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
