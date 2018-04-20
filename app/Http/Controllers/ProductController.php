<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Interest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Store;
use App\Models\StoreImage;
use App\Utils\ParametersUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(){
        $events = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Event::all()->toArray()
        );

        $interests = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Interest::all()->toArray()
        );
        return view('store.products.index', compact('events', 'interests'));
    }

    public function lists(){
        $Products = Product::where('store_id',Auth::user()->store->id)->where('status', 0)->orWhere('status', 1)->get();
        return response()->json($Products);
    }

    public function edit(Request $request){
        $data = $request->all();

        $auth = Auth::user();
        $store_id =  $auth->store["id"];

        $product = Product::with('productimages.store_image')->where("id", $data["id"])->first();
        $store_images = StoreImage::where('store_id', $store_id)->get();

        $sex = array_map(
            function($item){
                return [
                    "id" => $item['id'],
                    "value" => $item['value']
                ];
            }, ParametersUtil::sex
        );

        $ages = array_map(
            function($item){
                return [
                    "id" => $item,
                    "value" => $item
                ];
            }, range(1,80)
        );

        $events = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Event::all()->toArray()
        );

        $interests = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Interest::all()->toArray()
        );

        $product_characteristics = ParametersUtil::getProductCharacteristics();

        if($auth["type"] == "S"){
            if($store_id == $product->store_id){
                //return response()->json($product->productimages);
                return view('store.products.edit', compact(
                    'store_images','product',
                    'store_id', 'sex',
                    'ages', 'events',
                    'interests', 'product_characteristics')
                );
            }else{
                return redirect('/');
            }
        }

    }

    public function update(Request $request) {
        $data = $request->all();
        $Product = Product::find($data['id']);
        unset($data['id']);
        $data['age'] = implode(',',$data["age"]);
        $data['event'] = implode(',',$data["event"]);
        $data['interest'] = implode(',',$data["interest"]);
        $data['slug'] = Str::slug($data["name"]);
        if($Product->update($data))
            return response()->json(['status'=>'ok', 'data'=>$Product]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Product::find($data['id']);
        $model->status = 2;
        if($model->save()) {
            return response()->json(['status'=>'ok','data'=>$model]);
        }else{
            return response()->json(['status'=>'error', "message" => "No se ha podido eliminar el registro, intente más tarde."]);
        }
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $data['slug'] = Str::slug($data["name"]);
            $data['store_id'] = Auth::user()->store->id;
            $data['age'] = json_encode(array_map(function($age){return intval($age);},explode(",",$data['age'])));
            $data['event'] = json_encode(array_map(function($event){return intval($event);},explode(",",$data['event'])));
            $data['interest'] = json_encode(array_map(function($interest){return intval($interest);},explode(",",$data['interest'])));

            $model = Product::create($data);
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    // Carga masiva
    public function masive_charge(Request $request){
        $file = $request->file('excel');
        ini_set('max_execution_time', 300);
        if ($file->extension() == "xls" || $file->extension() == "xlsx") {
            $objPHPExcel = \PHPExcel_IOFactory::load($file);
            foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = \PHPExcel_Cell::columnIndexFromString($highestColumn);

                for ($row = 2; $row <= $highestRow; ++$row) {
                    $val = array();
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                        $val[] = $cell->getValue();
                    }

                    // Tienda
                    $name = $val[0];
                    $sku_code = $val[1];
                    $discount = $val[2];
                    $price = $val[3];
                    $product_presentation = $val[4];
                    $description = $val[5];
                    $age = $val[6];
                    $availability = $val[7];


                    Product::create([

                        'name'=>  $name,
                        'slug' => Str::slug($name),
                        'sku_code'=> $sku_code,
                        'discount'=> $discount,
                        'price'=> $price,
                        'product_presentation'=> $product_presentation,
                        'description'=> $description ,
                        'age'=> $age,
                        'availability'=>  $availability,
                        'sex' => '',
                        'store_id'=> Auth::user()->store->id

                    ]);

                }

            }
            return response()->json(['status' => 'ok']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'El formato de archivo es incorrecto.']);
        }
    }

    // Asignación de imágenes
    public function add_image_product(Request $request){
        $data = $request->all();
        try {
            $model_create = ProductImage::create([
                'store_image_id' => $data["id"],
                'product_id' => $data["product_id"]
            ]);
            $model = ProductImage::with('store_image')->where('id', $model_create->id)->first();
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function delete_image_product(Request $request){
        $product_image_id = $request->input('id');
        $product_image = ProductImage::find($product_image_id);
        $model = $product_image;
        $product_image->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    // Subida de imagen destacada
    public function store_featured_image(Request $request){
        $image = $request->file('file');
        $product_id = $request->input('product_id');
        $product = Product::find($product_id);
        //return response()->json($product);

        $name = $image->getClientOriginalName();

        $store_id = Auth::user()->store->id;
        $store = Store::find($store_id);
        $ruc = $store->ruc;

        $path = "uploads/stores/" . $ruc . "/";

        $image->move($path , $image->getClientOriginalName());

        $model = $product->update([
            'featured_image' => $path . $name
        ]);
        return response()->json(['status'=>"ok",'data'=>$product->featured_image]);

    }
}
