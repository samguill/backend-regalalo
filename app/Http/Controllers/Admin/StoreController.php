<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 3/03/18
 * Time: 21:15
 */

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Mail\Mail\StoreAccess;
use App\Models\ComercialContact;
use App\Models\LegalRepresentative;
use App\Models\StoreBranch;
use App\User;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;
use Illuminate\Support\Str;

class StoreController extends Controller
{

    public function index(){
        $data = [
            "title" => "Tiendas",
            "icon" => "fa-building"
        ];
        return view('admin.stores.index', compact('data'));
    }

    public function edit(Request $request){
        $store_id = $request->input('id');
        $store = Store::with('branches', 'comercial_contact', 'legal_representatives', 'user')->find($store_id);
        $data = [
            "title" => "Editar datos de tienda: " . $store->comercial_name,
            "icon" => "fa-building"
        ];
        return view('admin.stores.edit', compact('store', 'data'));
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

        $comercial_contacts = ComercialContact::where("store_id", $data["id"])->get();
        if (count($comercial_contacts) > 0){
            foreach ($comercial_contacts as $comercial_contact){
                $comercial_contact->delete();
            }
        }

        $legal_representatives = LegalRepresentative::where("store_id", $data["id"])->get();
        if (count($legal_representatives) > 0){
            foreach ($legal_representatives as $legal_representative){
                $legal_representative->delete();
            }
        }

        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $data['slug'] = Str::slug($data["comercial_name"]);
            $model = Store::create($data);
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function upload_logo(Request $request){
        $image = $request->file('file');
        $name = $image->getClientOriginalName();
        $store_id = $request->input('store_id');

        $store = Store::find($store_id);
        $ruc = $store->ruc;

        $path = "uploads/stores/" . $ruc . "/";
        $image->move($path , $image->getClientOriginalName());

        $model = $store->update([
            'logo_store' => $path . $name
        ]);
        return response()->json(['status'=>"ok",'data'=>$store->logo_store]);
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

        //Mail::to($store->comercial_contact->email)->send(new StoreAccess($store_user, $pin));
        Mail::to(["arturo.garcia@regalalo.pe", "juan.saavedra@regalalo.pe"])->send(new StoreAccess($store_user, $pin));

        $store->update([
            'user_id' => $store_user->id,
            'status' => 1
        ]);

        $path = public_path() . "/uploads/stores/" . $store->ruc;

        File::isDirectory($path) or File::makeDirectory($path, 0775, true, true);
        return response()->json(['status'=>'ok','data'=>$store, 'pin' => $pin]);
    }

    public function payme_document(Request $request){
        $data = $request->all();
        $store_id = $data["id"];
        $store = Store::with(['legal_representatives', 'comercial_contact'])->find($store_id);
        return \PDF::loadView('admin.stores.payme-pdf', compact('store'))->stream();
    }

    // Sucursales
    public function getBranches(Request $request){
        $auth = Auth::user();
        if($auth["type"] == "S"){
            $store_id = Auth::user()->store->id;
            return view('store.branches.index', compact('store_id'));
        }else{
            $store_id = $request->input('id');
            return view('admin.stores.branches', compact('store_id'));
        }
    }

    public function listBranches(Request $request){
        $store_id = $request->input('id');
        $branches = StoreBranch::with('branchopeninghours')->where('store_id',  $store_id)->get();
        return response()->json(['status'=>'ok','data' => $branches]);
    }

    public function create_branch(Request $request){
        $data = $request->all();
        unset($data['id']);
        try{
            $model = StoreBranch::create($data);
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
                    $payme_acquirer_id = $val[19];
                    $payme_wallet_password = $val[20];
                    $payme_gateway_password = $val[21];
                    $ga = $val[22];

                    $store = Store::where('ruc', $ruc)->first();
                    if ($store){
                        $store->update([
                            'business_name' => $razon_social,
                            'legal_address' => $direccion_legal,
                            'comercial_name'=> $nombre_comercial,
                            'slug' => Str::slug($nombre_comercial),
                            'phone' => $telefono,
                            'site_url' => $url,
                            'financial_entity' => $entidad,
                            'account_type' => $tipo_cuenta,
                            'account_statement_name' => $nombre_cuenta,
                            'bank_account_number' => $numero_cuenta,
                            'cci_account_number' => $cci,
                            'payme_comerce_id' => $payme_comerce,
                            'payme_wallet_id' => $payme_wallet,
                            'payme_acquirer_id' => $payme_acquirer_id,
                            'payme_wallet_password' => $payme_wallet_password,
                            'payme_gateway_password' => $payme_gateway_password,
                            'analytics_id' => $ga,
                        ]);

                        // Representante legal
                        $nombres = $val[3];
                        $dni_rl = $val[4];
                        $legal_representative = LegalRepresentative::where('store_id', $store->id)->first();
                        if($legal_representative){
                            $legal_representative->update([
                                'name' => $nombres,
                                'document_number' => $dni_rl
                            ]);
                        }

                        // Contacto comercial
                        $nombres_cc = $val[8];
                        $dni_cc = $val[9];
                        $telefono_cc = $val[10];
                        $email_cc = $val[11];
                        $comercial_contact = ComercialContact::where('store_id', $store->id)->first();
                        if($comercial_contact){
                            $comercial_contact->update([
                                'name' => $nombres_cc,
                                'document_number' => $dni_cc,
                                'phone' => $telefono_cc,
                                'email' => $email_cc
                            ]);
                        }
                    }else{
                        $store = Store::create([
                            'business_name' => $razon_social,
                            'ruc' => $ruc,
                            'legal_address' => $direccion_legal,
                            'comercial_name'=> $nombre_comercial,
                            'slug' => Str::slug($nombre_comercial),
                            'phone' => $telefono,
                            'site_url' => $url,
                            'financial_entity' => $entidad,
                            'account_type' => $tipo_cuenta,
                            'account_statement_name' => $nombre_cuenta,
                            'bank_account_number' => $numero_cuenta,
                            'cci_account_number' => $cci,
                            'payme_comerce_id' => $payme_comerce,
                            'payme_wallet_id' => $payme_wallet,
                            'payme_acquirer_id' => $payme_acquirer_id,
                            'payme_wallet_password' => $payme_wallet_password,
                            'payme_gateway_password' => $payme_gateway_password,
                            'analytics_id' => $ga
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

            }
            return response()->json(['status' => 'ok']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'El formato de archivo es incorrecto.']);
        }
    }

    public function update_access(Request $request){
        $data = $request->all();
        if(is_null($data["id"])){
            $store = Store::find($data["store_id"]);
            $comercial_contact = ComercialContact::where("store_id", $store->id)->first();
            $user = User::create([
                'name' => $comercial_contact->name,
                'email' => $data["email"],
                'password' => $data["password"],
                'type' => "S",
                'status' => 1
            ]);
            $store->update(["user_id" => $user->id]);
        }else{
            $user = User::find($data["id"]);
            if($data["password"] == "" || is_null($data["password"])){
                $user->update(["email" => $data["email"]]);
            }else{
                $user->update(["email" => $data["email"], "password" => $data["password"]]);
            }
        }
        return response()->json(['status' => 'ok', "data" => $user]);
    }

}