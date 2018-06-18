<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ComercialContact;
use Mockery\Exception;

use Illuminate\Http\Request;

class ComercialContactController extends Controller
{
    public function update(Request $request){
        $data = $request->all();
        if(is_null($data["id"])){
            $model = ComercialContact::create($data);
        }else{
            $model = ComercialContact::find($data['id']);
            unset($data['id']);
            $model->update($data);
        }
        return response()->json(['status' => 'ok', 'data' => $model]);
    }
}
