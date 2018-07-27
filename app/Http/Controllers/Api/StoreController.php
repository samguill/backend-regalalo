<?php

namespace App\Http\Controllers\Api;

use App\Mail\StoreRegistration;
use App\Models\ComercialContact;
use App\Models\LegalRepresentative;
use App\Models\Product;
use App\Models\Service;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
        $products = Product::where('store_id', $store->id)->get();
        $products = $products->map(function ($item){
            $item["type"] = "products";
        });
        $services = Service::where('store_id', $store->id)->get();
        return response()->json([
            //'status'=>'ok',
            'products' => $products,
            //'services' => $services,
            'store' => $store]);
    }

    public function store(Request $request){
        $legal_representative = $request->input('legal_representative');
        $comercial_contact = $request->input('comercial_contact');
        $store = $request->input("store");
        //dd($comercial_contact['phone']);
        /*$validator = Validator::make($request->all(), [
            'stores.comercial_name' => 'required|max:255|unique:stores',
            'stores.business_name' => 'required|max:255|unique:stores',
            'stores.ruc' => 'required|unique:stores',
            'stores.legal_address' => 'required',
            'stores.business_turn'=> 'max:255',
            'legal_representatives.name' => 'required',
            'legal_representatives.document_number' => 'required',
            'legal_representatives.phone' => 'required',
            'comercial_contacts.name' => 'required',
            'comercial_contacts.document_number' => 'required',
            'comercial_contacts.phone' => 'required',
            'comercial_contacts.email' => 'required|email'
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error', 'message' => $validator->errors()]);
        }*/

        $store = Store::create([
            'business_name' => $store['business_name'],
            'comercial_name' => $store['comercial_name'],
            'ruc' => $store['ruc'],
            'legal_address' => $store['legal_address'],
            'business_turn' => $store['business_turn'],
            'site_url' => isset($store['cci_account_number']) ? $store['cci_account_number'] : '',
            'phone' => isset($store['phone']) ? $store['phone'] : null
        ]);

        //Creando representante legal
        LegalRepresentative::create([
            'name' => $legal_representative['name'],
            'document_number' => $legal_representative['document_number'],
            'phone' => $legal_representative["phone"],
            'store_id' => $store->id
        ]);

        //Creando contacto comercial

        $comercialcontact=   ComercialContact::create([
            'name' => $comercial_contact['name'],
            'document_number' => $comercial_contact['document_number'],
            'email' => $comercial_contact['email'],
            'phone' => $comercial_contact['phone'],
            'position' => isset($comercial_contact['position'])?$comercial_contact['position']:'',
            'store_id' => $store->id
        ]);

        $data = [

            'comercial_name'=>$store->comercial_name,
            'business_name'=>$store->business_name,
            'ruc'=>$store->ruc,
            'name'=>$comercialcontact->name

            ];

        //Enviando correo de notificacion a administradores
        $admin_emails = ['arturo.garcia@regalalo.pe','juan.saavedra@regalalo.pe','recursos@regalalo.pe'];

        foreach ($admin_emails as $admin_email) {

            Mail::to($admin_email)->send(new StoreRegistration($data));
        }


        return response()->json(['status'=>"ok",'data'=>$store]);
    }


}
