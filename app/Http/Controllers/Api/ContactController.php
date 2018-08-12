<?php

namespace App\Http\Controllers\Api;

use App\Mail\ContactForm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller {

    public function sendMessage(Request $request){
        $data = $request->all();
        $admin_emails = ['arturo.garcia@regalalo.pe','juan.saavedra@regalalo.pe','recursos@regalalo.pe'];
        //$admin_emails = ['marzioperez@gmail.com'];
        try{
            Mail::to($admin_emails)->send(new ContactForm($data));
            return response()->json(['status' => 'ok']);
        }catch (\Exception $exception){
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()]);
        }
    }
}
