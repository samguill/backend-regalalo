<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 10/03/18
 * Time: 17:03
 */

namespace App\Http\Controllers\Api;
use App\Models\Brand;
use App\Models\Event;
use App\Models\Experience;
use App\Models\FrequentQuestion;
use App\Models\Interest;
use App\Models\Offer;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller {

    public function home(){
        $stores = Store::whereNotNull('logo_store')->where('status', 1)->take(6)->get(['id', 'slug','logo_store']);
        $slides = Slide::with('elements')->orderBy('order')->get();

        $products = Product::whereNotNull('featured_image')->take(10)->get();
        $first_10_products = Product::whereNotNull('featured_image')->take(10)->get();
        $before_10_products = Product::whereNotNull('featured_image')->skip(10)->take(10)->get();
        $posts = Post::with('category')->take(3)->get();

        $brands = Brand::whereNotNull('image')->get();

        $offers = Offer::where('status', 1)->orderBy('order')->take(4)->get();

        return response()->json([
            'status'=>'ok',
            'stores' => $stores,
            'slides' => $slides,
            'products' => $products,
            'first_10_products' => $first_10_products,
            'before_10_products' => $before_10_products,
            'offers' => $offers,
            'brands' => $brands,
            'posts' => $posts
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

    public function pages(){
        $page = Page::all();
        return response()->json([
            'status'=>'ok',
            'pages' => $page
        ]);
    }

    public function page($slug){
        $page = Page::where("slug", $slug)->first();
        return response()->json([
            'status'=>'ok',
            'page' => $page
        ]);
    }




}