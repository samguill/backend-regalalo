<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function search(Request $request){

        //Tabla principa del productos
        $query = DB::table('products');

        //Si se envía los parámetros condiciona la búsqueda con has

        //El Sexo es obligatorio
        if($request->has('sex'))
        {
            $sex =  $request->input('sex');
            $query->where('sex', $sex);
        }

        // Edad
        if($request->has('ages'))
        {
            $ages = $request->input('ages');
            foreach ($ages as $age) {
                $query->where('age','LIKE','%'.$age.'%');
            }

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
        $result = $query->paginate(15);

        return response()->json(['status'=>'ok', 'data'=>$result]);

    }
}
