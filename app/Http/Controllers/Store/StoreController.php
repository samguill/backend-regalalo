<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 3/03/18
 * Time: 21:15
 */

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\BranchOpeningHour;
use App\Models\ComercialContact;
use App\Models\LegalRepresentative;
use App\Models\StoreBranch;
use App\User;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Mockery\Exception;

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
        $store = Store::with('branches', 'comercial_contact', 'legal_representatives')->find($store_id);
        $data = [
            "title" => "Editar datos de tienda: " . $store->comercial_name,
            "icon" => "fa-building"
        ];
        //return response()->json($store->comercial_contact);
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
        //return response()->json($store);
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

        foreach ($data['branchopeninghours'] as $branchopeninghour) {
            if($branchopeninghour['id']!=''){
                $branchopeninghourmodel = BranchOpeningHour::find($branchopeninghour['id']);

                $branchopeninghourmodel->update([
                    'weekday' => $branchopeninghour['weekday'],
                    'start_hour' => $branchopeninghour['start_hour'],
                    'end_hour' => $branchopeninghour['end_hour'],
                    'store_branche_id' => $branchopeninghour['store_branche_id']
                ]);
            }else{
                BranchOpeningHour::create([
                    'weekday' => $branchopeninghour['weekday'],
                    'start_hour' => $branchopeninghour['start_hour'],
                    'end_hour' => $branchopeninghour['end_hour'],
                    'store_branche_id' => $branchopeninghour['store_branche_id']
                ]);
            }
        }
        unset($data['branchopeninghours']);
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

    public function delete_day_open(Request $request){
        $id = $request->input('id');
        $branchopeninghour = BranchOpeningHour::find($id);
        $branchopeninghour->delete();
        return response()->json(['status'=>'ok','data' => $branchopeninghour]);
    }

}