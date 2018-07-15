<?php

namespace App\Http\Controllers\Api;

use App\Models\ComercialContact;
use App\Models\LegalRepresentative;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller {

    public function stores(){
        $stores = Store::where('status', 1)->get();
        return response()->json([
            'status'=>'ok',
            'stores' => $stores]);
    }

    public function products_store(Request $request){
        $store_slug = $request->input('slug');
        $store = Store::where('slug', $store_slug)->first();
        $products = Product::where('status', 0)->where('store_id', $store->id)->get();
        return response()->json([
            'status'=>'ok',
            'products' => $products,
            'store' => $store]);
    }

    public function store(Request $request){
        $legal_representative = $request->input('legal_representative');
        $comercial_contact = $request->input('comercial_contact');

        $validator = Validator::make($request->all(), [
            'comercial_name' => 'required|max:255|unique:stores',
            'business_name' => 'required|max:255|unique:stores',
            'ruc' => 'required|unique:stores',
            'legal_address' => 'required',
            'legal_representative.name' => 'required',
            'legal_representative.document_number' => 'required',
            'legal_representative.phone' => 'required',
            'business_turn'=> 'max:255',
            'comercial_contact.name' => 'required',
            'comercial_contact.document_number' => 'required',
            'comercial_contact.phone' => 'required',
            'comercial_contact.email' => 'required|email'
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }

        $store = Store::create([
            'business_name' => $request->get('business_name'),
            'comercial_name' => $request->get('comercial_name'),
            'ruc' => $request->get('ruc'),
            'legal_address' => $request->get('legal_address'),
            'business_turn' => $request->get('business_turn'),
            'site_url' => $request->has('cci_account_number')?$request->get('cci_account_number'):'',
            'phone' => $request->has('phone')?$request->get('phone'):null
        ]);

        //Creando representante legal
        LegalRepresentative::create([
            'name' => $legal_representative['name'],
            'document_number' => $legal_representative['document_number'],
            'phone' => $legal_representative["phone"],
            'store_id' => $store->id
        ]);

        //Creando contacto comercial
        ComercialContact::create([
            'name' => $comercial_contact['name'],
            'document_number' => $comercial_contact['document_number'],
            'email' => $comercial_contact['email'],
            'phone' => $comercial_contact['phone'],
            'position' => isset($comercial_contact['position'])?$comercial_contact['position']:'',
            'store_id' => $store->id
        ]);

        return response()->json(['status'=>"ok",'data'=>$store]);
    }


}
