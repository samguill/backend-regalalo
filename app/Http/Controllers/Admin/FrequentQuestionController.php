<?php

namespace App\Http\Controllers\Admin;

use App\Models\FrequentQuestion;
use Aws\WafRegional\Exception\WafRegionalException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;

class FrequentQuestionController extends Controller
{
    public function index(){
        $data = [
            "title" => "Preguntas frecuentes",
            "icon" => "fa-question-circle"
        ];
        return view('admin.faq.index', compact('data'));
    }

    public function lists(){
        $stores = FrequentQuestion::get();
        return response()->json($stores);
    }

    public function update(Request $request) {
        $data = $request->all();
        $store = FrequentQuestion::find($data['id']);
        unset($data['id']);
        if($store->update($data)){
            return response()->json(['status'=>'ok', 'data'=>$store]);
        }else{
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
        }

    }

    public function delete(Request $request){
        $data = $request->all();
        $model = FrequentQuestion::find($data['id']);
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function create(Request $request){
        try{
            $model = FrequentQuestion::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }
}
