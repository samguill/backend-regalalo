<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 3/03/18
 * Time: 21:15
 */

namespace App\Http\Controllers;
use App\Models\ComercialContact;
use App\Models\LegalRepresentative;
use App\Models\StoreBranch;
use App\User;
use Illuminate\Http\Request;
use App\Models\Store;
use Mockery\Exception;

class StoreController extends Controller
{

    public function index(){
        return view('admin.stores.index');
    }

    public function lists(){
        $stores = Store::with('comercial_contact','legal_representatives')->where('status', 0)->orWhere('status', 1)->get();
        return response()->json($stores);
    }

    public function update(Request $request) {
        $data = $request->all();
        $store = Store::find($data['id']);
        unset($data['id']);
        if($store->update($data))
            return response()->json(['status'=>'ok', 'data'=>$store]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Store::find($data['id']);
        $model->status = 2;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = Store::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function generate_user(Request $request){
        $store_id = $request->input('id');
        $store = Store::with('comercial_contact')->find($store_id);

        $faker = \Faker\Factory::create();
        $pin = $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit();

        $store_user = User::create([
            'name' => $store->comercial_name,
            'email' => $store->comercial_contact->email,
            'password' => $pin,
            'type' => 'S'
        ]);

        $store->update(['user_id' => $store_user->id, 'status' => 1]);
        return response()->json(['status'=>'ok','data'=>$store]);
        //return response()->json($pin);
    }

    public function payme_document(Request $request){
        $data = $request->all();
        $store_id = $data["id"];
        $store = Store::with(['legal_representatives', 'comercial_contact'])->find($store_id);
        //return response()->json($store);
        return \PDF::loadView('admin.stores.payme-pdf', compact('store'))->stream();
    }

    // Sucursales
    public function getBranches(Request $request){
        $store_id = $request->input('id');
        return view('admin.stores.branches', compact('store_id'));
    }

    public function listBranches(Request $request){
        $store_id = $request->input('id');
        $branches = StoreBranch::where('store_id',  $store_id)->get();
        return response()->json(['status'=>'ok','data' => $branches]);
    }

    public function create_branch(Request $request){
        try{
            $model = StoreBranch::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function update_branch(Request $request){
        $data = $request->all();
        $model = StoreBranch::find($data['id']);
        unset($data['id']);
        foreach ($data as $field=>$value) {
            $model->$field = $value;
        }

        if($model->save()) {
            return response()->json(['status' => 'ok','data' => $model]);
        }else{
            return response()->json(['status' => 'error',"message" => "No se ha podido actualizar el registro, intente más tarde."]);
        }
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

                    // Financiero
                    $entidad = $val[12];
                    $tipo_cuenta = $val[13];
                    $nombre_cuenta = $val[14];
                    $numero_cuenta = $val[15];
                    $cci = $val[16];
                    $payme_comerce = $val[17];
                    $payme_wallet = $val[18];
                    $ga = $val[19];

                    $store = Store::create([
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