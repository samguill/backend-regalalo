<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 9/03/18
 * Time: 7:02 PM
 */

namespace App\Http\Controllers;
use App\Models\Client;
use Mockery\Exception;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index(){
        $data = [
            "title" => "Clientes",
            "icon" => "fa-users"
        ];
        return view('admin.clients.index', compact('data'));
    }

    public function lists(){
        $stores = Client::with('directions','wishlist')->where('status', 1)->get();
        return response()->json($stores);
    }
    
    public function update(Request $request) {
        $data = $request->all();
        $store = Client::find($data['id']);
        unset($data['id']);
        if($store->update($data)){
            return response()->json(['status'=>'ok', 'data'=>$store]);
        }else{
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
        }

    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Client::find($data['id']);
        $model->status = 0;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $model = Client::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function detail(Request $request){
        $client_id = $request->input('id');
        return view('admin.clients.detail', compact('client_id'));
    }

}