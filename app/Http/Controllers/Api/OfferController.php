<?php

namespace App\Http\Controllers\Api;

use App\Models\Offer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function get(Request $request){
        $offer_slug = $request->input('slug');
        $offer = Offer::with('offerdetails.product', 'offerdetails.service')
            ->where('slug', $offer_slug)->first();
        return response()->json([
            'status'=>'ok',
            'offer' => $offer]);
    }
}
