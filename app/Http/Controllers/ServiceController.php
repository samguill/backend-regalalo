<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(){

        $experiences = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Experience::all()->toArray()
        );

        return view('store.services.index', compact('experiences'));
    }

    public function lists(){
        $Services = Service::where('store_id',Auth::user()->store->id)->where('status', 0)->orWhere('status', 1)->get();
        return response()->json($Services);
    }

    public function update(Request $request) {
        $data = $request->all();
        $Service = Service::find($data['id']);
        unset($data['id']);
        $data['age'] = json_encode(array_map(function($age){return intval($age);},explode(",",$data['age'])));
        $data['experience'] = json_encode(array_map(function($experience){return intval($experience);},explode(",",$data['experience'])));
        if($Service->update($data))
            return response()->json(['status'=>'ok', 'data'=>$Service]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Service::find($data['id']);
        $model->status = 2;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $data['store_id'] = Auth::user()->store->id;
            $data['age'] = json_encode(array_map(function($age){return intval($age);},explode(",",$data['age'])));
            $data['experience'] = json_encode(array_map(function($experience){return intval($experience);},explode(",",$data['experience'])));
            $model = Service::create($data);
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }


    // Carga masiva
    public function masive_charge(Request $request){
        $file = $request->file('excel');
        ini_set('max_execution_time', 300);
        if ($file->extension() == "xls" || $file->extension() == "xlsx") {
            $objPHPExcel = \PHPExcel_IOFactory::load($file);
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                for ($row = 3; $row <= $highestRow; ++$row) {
                    $val = array();
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val[] = $cell->getValue();
                    }

                    // Tienda
                    $name = $val[0];
                    $sku_code = $val[1];
                    $discount = $val[2];
                    $price = $val[3];
                    $product_presentation = $val[4];
                    $description = $val[5];
                    $age = $val[6];
                    $availability = $val[7];


                    Service::create([

                        'name'=>  $name,
                        'sku_code'=> $sku_code,
                        'discount'=> $discount,
                        'price'=> $price,
                        'product_presentation'=> $product_presentation,
                        'description'=> $description ,
                        'age'=> $age,
                        'availability'=>  $availability,
                        'store_id'=> Auth::user()->store->id

                    ]);

                }

            }
            return response()->json(['status' => 'ok']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'El formato de archivo es incorrecto.']);
        }
    }
}
