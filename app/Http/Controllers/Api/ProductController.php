<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function search(Request $request){

        //Tabla principa del productos
        $query = DB::table('products')->select(DB::raw('*'));

        // Solo si el usuario ha compartido su ubicación se muestra la distancia
        if($request->has('latitude') and $request->has('longitude')) {

            $latitude =   $request->input('latitude');
            $longitude =   $request->input('longitude');

            $query->addSelect(
                DB::raw(
                    '(select (acos(sin(radians(store_branches.latitude)) * sin(radians('.$latitude.')) +
                    cos(radians(store_branches.latitude)) * cos(radians('.$latitude.')) *
                    cos(radians(store_branches.longitude) - radians('.$longitude.'))) * 6378) 
                    from store_branches where store_branches.id =  inventory.store_branche_id) as distance'));

            $query->orderBy('distance', 'desc');
        }

        //Si se envía los parámetros condiciona la búsqueda con has

        //El Sexo es obligatorio
        if($request->has('sex'))
        {
            $sex =  $request->input('sex');
            $query->where('sex', $sex);
        }

        // Edad
        if($request->has('age'))
        {
            $age = $request->input('age');
            $query->whereRaw('SUBSTRING_INDEX(age, ",", 1) <= ?' ,$age);
            $query->whereRaw('SUBSTRING_INDEX(age, ",", -1) >= ?' ,$age);

        }

         //Solo si coloca precio desde
        if($request->has('budget_from'))
        {
            $budget_from =  $request->input('budget_from');
            $query->where('price','>=' ,$budget_from);
        }

        //Solo si coloca precio hasta
        if($request->has('budget_to'))
        {
            $budget_to =  $request->input('budget_to');
            $query->where('price','<=' ,$budget_to);
        }

        // Solo si coloca ambos (Desde y Hasta)
        if($request->has('budget_from') and $request->has('budget_to'))
        {
            $budget_from =  $request->input('budget_from');
            $budget_to =  $request->input('budget_to');
            $query->whereBetween('price', [$budget_from, $budget_to]);

        }

        //Solo si coloca alguna ocasión
        if($request->has('events'))        {
            $events = $request->input('events');
            foreach ($events as $event) {
                $query->where('event','LIKE','%'.$event.'%');
            }

        }

        //Solo si coloca algún interés
        if($request->has('interests'))
        {
            $interests = $request->input('interests');
            foreach ($interests as $interest) {
            $query->where('interest','LIKE','%'.$interest.'%');
            }

        }
        // Disponible en Delivery, ambos, tienda
        if($request->has('availability')) {
            $availability = $request->input('availability');
            $query->where('availability',$availability);
        }

        //precio con descuento
        $query->addSelect(DB::raw('IF(discount >0,price*(1-discount/100),0) as discount_price'));

        //inventario
        $query->addSelect(DB::raw('IFNULL(inventory.quantity,0) as quantity'));

        $query->leftJoin('inventory','inventory.product_id','=','products.id');

        $result = $query->paginate(15);
        //$result = $query->tosql();

        return response()->json(['status'=>'ok', 'data'=>$result]);

    }

    public function detail(Request $request) {
        $data = [];
        $latitude='';
        $longitude ='';
        if($request->has('latitude')) $latitude =  $request->input('latitude');
        if($request->has('longitude')) $longitude =   $request->input('longitude');

        if($request->has('slug')){
            $slug = $request->input('slug');
            $data = Product::where('slug',$slug)->with([
                'productimages.store_image',
                'productcharacteristicsdetail.characteristic',
                'store.branches.branchopeninghours',
                'store.branches' => function ($query) use($latitude,$longitude) {
                    if($latitude!= '' and $longitude!= '')
                    $query->orderByRaw(' acos(sin(radians(store_branches.latitude)) * sin(radians('.$latitude.')) +
                        cos(radians(store_branches.latitude)) * cos(radians('.$latitude.')) *
                        cos(radians(store_branches.longitude) - radians('.$longitude.'))) * 6378 ASC');
            }

            ])->first();
        };
        return response()->json(['status'=>'ok', 'data'=>$data]);
    }

    public function branche(Request $request) {
        $data = $request->all();
        $branch = StoreBranch::find($data["store_branche_id"]);
        return response()->json(['status'=>'ok', 'data'=>$branch]);
    }
}
