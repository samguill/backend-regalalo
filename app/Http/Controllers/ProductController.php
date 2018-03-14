<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Interest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(){
        $events = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Event::all()->toArray()
        );

        $interests = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Interest::all()->toArray()
        );
        return view('store.Products.index', compact('events', 'interests'));
    }

    public function lists(){

        $Products = Product::where('store_id',Auth::user()->store->id)->where('status', 0)->orWhere('status', 1)->get();
        return response()->json($Products);
    }

    public function update(Request $request) {
        $data = $request->all();
        $Product = Product::find($data['id']);
        unset($data['id']);
        if($Product->update($data))
            return response()->json(['status'=>'ok', 'data'=>$Product]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Product::find($data['id']);
        $model->status = 2;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = Product::create($request->all());
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
                    $razon_social = $val[0];
                    $ruc = $val[1];
                    $direccion_legal = $val[2];
                    $nombre_comercial = $val[5];
                    $telefono = $val[6];
                    $url = $val[7];



                    $store = Product::create([
                        'business_name' => $razon_social,
                        'ruc' => $ruc,
                        'legal_address' => $direccion_legal,
                        'comercial_name'=> $nombre_comercial,
                        'phone' => $telefono,
                        'site_url' => $url,
                        'financial_entity' => $entidad,
                        'account_type' => $tipo_cuenta,
                        'account_statement_name' => $nombre_cuenta,
                        'bank_account_number' => $numero_cuenta,
                        'cci_account_number' => $cci,
                        'payme_comerce_id' => $payme_comerce,
                        'payme_wallet_id' => $payme_wallet,
                        'analytics_id' => $ga,
                    ]);
    
                    // Representante legal
                    $nombres = $val[3];
                    $dni_rl = $val[4];
                    LegalRepresentative::create([
                        'name' => $nombres,
                        'document_number' => $dni_rl,
                        'store_id' => $store->id
                    ]);

                    // Contacto comercial
                    $nombres_cc = $val[8];
                    $dni_cc = $val[9];
                    $telefono_cc = $val[10];
                    $email_cc = $val[11];
                    ComercialContact::create([
                        'name' => $nombres_cc,
                        'document_number' => $dni_cc,
                        'phone' => $telefono_cc,
                        'email' => $email_cc,
                        'store_id' => $store->id
                    ]);
                }

            }
            return response()->json(['status' => 'ok']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'El formato de archivo es incorrecto.']);
        }
    }
}
