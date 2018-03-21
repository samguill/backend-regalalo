<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            "title" => "Escritorio",
            "icon" => "fa-dashboard"
        ];
        if(Auth::user()->type == 'A') {

            return view('admin.home', compact('data'));

        }else{

            return view('store.home', compact('data'));

        }
    }
}
