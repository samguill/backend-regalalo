<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 3/03/18
 * Time: 21:15
 */

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\StoreBranch;
use Illuminate\Http\Request;
use App\Models\Store;
use Mockery\Exception;

class StoreController extends Controller
{

    public function index(){
        return view('stores.index');
    }

    public function lists(){
        $stores = Store::where('status', 0)->orWhere('status', 1)->get();
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

    // Sucursales
    public function getBranches(Request $request){
        $store_id = $request->input('id');
        return view('stores.branches', compact('store_id'));
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
        
    }

}