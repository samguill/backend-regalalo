<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 10/03/18
 * Time: 17:03
 */

namespace App\Http\Controllers\Api;
use App\Models\Event;
use App\Models\Experience;
use App\Models\FrequentQuestion;
use App\Models\Interest;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller {

    public function home(){
        $stores = Store::where('status', 1)->take(6)->get(['id', 'slug','logo_store']);
        $slides = Slide::with('elements')->orderBy('order')->get();

        $products = Product::take(15)->get();

        $offers = Offer::where('status', 1)->orderBy('order')->take(4)->get();

        return response()->json([
            'status'=>'ok',
            'stores' => $stores,
            'slides' => $slides,
            'products' => $products,
            'offers' => $offers
        ]);
    }

    public function search_params(){
        $events = Event::where('status', 0)->get();
        $interests = Interest::where('status', 0)->get();
        $experiences = Experience::where('status', 0)->get();

        return response()->json([
            'status'=>'ok',
            'events' => $events,
            'interests' => $interests,
            'experiences' => $experiences
        ]);
    }

    public function faq(){
        $faq = FrequentQuestion::all();
        return response()->json([
            'status'=>'ok',
            'faq' => $faq
        ]);
    }

}