<?php
/**
 * Created by PhpStorm.
 * User: marzioperez
 * Date: 10/03/18
 * Time: 17:03
 */

namespace App\Http\Controllers\Api;
use App\Models\Event;
use App\Models\Interest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller {

    public function home(){
        $events = Event::where('status', 0)->get();
        $interests = Interest::where('status', 0)->get();

        return response()->json([
            'status'=>'ok',
            'events' => $events,
            'interests' => $interests
        ]);
    }

}