<?php

namespace App\Http\Controllers;
use App\Models\ComercialContact;
use Mockery\Exception;

use Illuminate\Http\Request;

class ComercialContactController extends Controller
{
    public function update(Request $request){
        $data = $request->all();
        $model = ComercialContact::find($data['id']);
        unset($data['id']);
        if($model->update($data)) {
            return response()->json(['status' => 'ok', 'data' => $model]);
        } else {
            return response()->json(['status' => 'error', 'message' => "No se pudo actualizar el registro."]);
        }
    }
}
