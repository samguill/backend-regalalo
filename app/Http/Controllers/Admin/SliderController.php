<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slide;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;

class SliderController extends Controller {

    public function index(){
        $data = [
            "title" => "Slider"
        ];

        $tags = array_map(
            function($item){
                return [
                    "id" => $item["key"],
                    "value" => $item["key"]
                ];
            }, Tag::all()->toArray()
        );
        return view('admin.slider.index', compact('data', 'tags'));
    }

    public function lists(){
        $slides = Slide::all();
        return response()->json($slides);
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $slide = Slide::create($data);
            if($request->file('image')){
                $photo = $request->file('image');
                $path = "uploads/slides/";
                $name = "slide-" . $slide->id . "." . $photo->getClientOriginalExtension();
                $photo->move($path, $name);
                $slide->update([
                    'image' => $path . $name
                ]);
            }
        }catch(Exception $e) {
            return response()->json(['status'=>"error",'message'=>$e->getMessage()]);
        }
        return response()->json(['status'=>"ok",'data'=>$slide]);
    }

    public function update(Request $request) {
        $data = $request->all();
        $slide = Slide::find($data['id']);
        if($request->file('image')){
            $photo = $request->file('image');
            $path = "uploads/slides/";
            $name = "slide-" . $slide->slug . "." . $photo->getClientOriginalExtension();
            $photo->move($path, $name);
            $slide->update([
                'image' => $path . $name
            ]);
        }

        unset($data['id']);
        unset($data['image']);
        if($slide->update($data))
            return response()->json(['status'=>'ok', 'data'=>$slide]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Slide::find($data['id']);
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

}
