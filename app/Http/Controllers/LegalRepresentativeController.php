<?php

namespace App\Http\Controllers;

use App\Models\LegalRepresentative;
use Illuminate\Http\Request;
use Mockery\Exception;

class LegalRepresentativeController extends Controller
{
    public function create(Request $request){
        try{
            $model = LegalRepresentative::create($request->all());
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function update(Request $request){
        $data = $request->all();
        $model = LegalRepresentative::find($data['id']);
        unset($data['id']);
        if($model->update($data)) {
            return response()->json(['status' => 'ok', 'data' => $model]);
        } else {
            return response()->json(['status' => 'error', 'message' => "No se pudo actualizar el registro."]);
        }
    }
}
