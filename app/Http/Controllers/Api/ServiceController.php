<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function search(Request $request){

        //Tabla principa del servicios
        $query = DB::table('services')->select(DB::raw('*'));

        // Solo si el usuario ha compartido su ubicación se muestra la distancia
        if($request->has('latitude') and $request->has('longitude')) {

            $latitude =   $request->input('latitude');
            $longitude =   $request->input('longitude');

            $query->addSelect(
                DB::raw(
                    '(select (acos(sin(radians(store_branches.latitude)) * sin(radians('.$latitude.')) +
                    cos(radians(store_branches.latitude)) * cos(radians('.$latitude.')) *
                    cos(radians(store_branches.longitude) - radians('.$longitude.'))) * 6378) 
                    from store_branches where store_branches.store_id =  coupons.store_branche_id) as distance'));

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
        if($request->has('experiences'))        {
            $experiences = $request->input('experiences');
            foreach ($experiences as $experience) {
                $query->where('experience','LIKE','%'.$experience.'%');
            }

        }

        // Disponible en Delivery, ambos, tienda
        if($request->has('availability')) {
            $availability = $request->input('availability');
            $query->where('availability',$availability);
        }

        //cupos disponibles
        $query->addSelect(DB::raw('IFNULL(coupons.quantity,0) as quantity'));

        $query->leftJoin('coupons','coupons.service_id','=','services.id');

        $result = $query->paginate(15);

        return response()->json(['status'=>'ok', 'data'=>$result]);

    }


    public function detail(Request $request)
    {
        $data = [];

        if($request->has('slug')){

            $slug = $request->input('slug');

            $data = Service::where('slug',$slug)->with(['serviceimages.store_image','store'])->first();

        };

        return response()->json(['status'=>'ok', 'data'=>$data]);

    }
}
