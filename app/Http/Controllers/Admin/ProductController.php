<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Event;
use App\Models\Interest;
use App\Models\Product;
use App\Models\ProductCharacteristic;
use App\Models\ProductCharacteristicDetail;
use App\Models\ProductImage;
use App\Models\Store;
use App\Models\StoreImage;
use App\Models\Tag;
use App\Utils\ParametersUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mockery\Exception;
use Faker\Factory;

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

        $stores = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["comercial_name"]
                ];
            }, Store::all()->toArray()
        );
        return view('admin.products.index', compact('events', 'interests', 'stores'));
    }

    public function lists(){
        $products = DB::select('call products');
        return response()->json($products);
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $data['slug'] = Str::slug($data["name"]);
            $data["age"] = $data["min_age"] . "," . $data["max_age"];
            $model = Product::create($data);
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function edit(Request $request){
        $data = $request->all();

        $product = Product::with('productimages.store_image', 'productcharacteristicsdetail.characteristic')
            ->where("id", $data["id"])->first();
        $store = Store::find($product->store_id);
        $store_id = $store->id;
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

        $brands = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Brand::all()->toArray()
        );

        $tags = array_map(
            function($item){
                return [
                    "id" => $item["key"],
                    "value" => $item["key"]
                ];
            }, Tag::all()->toArray()
        );

        $product_characteristics = ProductCharacteristic::with('values')->get();

        return view('admin.products.edit', compact(
                'store_images','product',
                'store_id', 'sex', 'brands',
                'ages', 'events', 'tags',
                'interests', 'product_characteristics')
        );
    }

    public function update(Request $request) {
        $data = $request->all();
        $product = Product::find($data['id']);
        unset($data['id']);
        $data["age"] = $data["min_age"] . "," . $data["max_age"];
        $data['slug'] = Str::slug($data["name"]);
        if($product->update($data))
            return response()->json(['status'=>'ok', 'data'=>$product]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
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

    public function update_seo(Request $request){
        $data = $request->all();
        $product = Product::find($data['id']);
        unset($data['id']);
        if($product->update($data))
            return response()->json(['status'=>'ok', 'data'=>$product]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    // Mantenimiento de características del producto
    public function characteristics_store(Request $request){
        $data = $request->all();
        $detail = ProductCharacteristicDetail::create([
            "product_id" => $data["product_id"],
            'product_characteristic_id' => $data["product_characteristic_id"],
            'product_characteristic_values' => $data["product_characteristic_values"]
        ]);
        $model = ProductCharacteristicDetail::with('characteristic')->find($detail->id);
        return response()->json(['status' => 'ok', 'data' => $model]);
    }

    public function characteristics_update(Request $request){
        $data = $request->all();
        $product = ProductCharacteristicDetail::find($data["id"]);
        $product->update([
            'product_characteristic_id' => $data["product_characteristic_id"],
            'product_characteristic_values' => $data["product_characteristic_values"]
        ]);
        $model = ProductCharacteristicDetail::with('characteristic')->find($data["id"]);
        return response()->json(['status' => 'ok', 'data' => $model]);
    }

    public function characteristics_delete(Request $request){
        $data = $request->all();
        $product = ProductCharacteristicDetail::find($data["id"]);
        $product->delete();
        $model = ProductCharacteristicDetail::with('characteristic')->find($data["id"]);
        return response()->json(['status' => 'ok', 'data' => $model]);
    }

    // Carga masiva
    public function masive_charge(Request $request){
        $file = $request->file('excel');
        $store_id = $request->input('store_id');
        $faker = Factory::create();
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
                    //$ages = explode("-", $age);
                    $availability = $val[7];

                    if($name !== "" && $age !== ""){
                        //$ages = range(intval($ages[0]), intval($ages[1]));
                        //$ages = implode(",", $ages);
                        $product = Product::where('sku_code', $sku_code)->where('store_id', $store_id)->first();
                        if($product){
                            $product->update([
                                'name'=>  $name,
                                'slug' => Str::slug($name),
                                'discount'=> $discount,
                                'price'=> $price,
                                'product_presentation'=> $product_presentation,
                                'description'=> $description,
                                'age'=> $age,
                                'availability'=>  $availability
                            ]);
                        }else{
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
                                'store_id'=> $store_id
                            ]);
                        }
                    }

                }

            }
            return response()->json(['status' => 'ok']);
        }else{
            return response()->json(['status' => 'error', 'message' => 'El formato de archivo es incorrecto.']);
        }
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Product::find($data['id']);
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    // Subida de imagen destacada
    public function store_featured_image(Request $request){
        $image = $request->file('file');
        $product_id = $request->input('product_id');
        $product = Product::find($product_id);

        $name = $product->slug . "-" . $product_id . "." . $image->getClientOriginalExtension();
        $store = Store::find($product->store_id);
        $ruc = $store->ruc;

        $path = "uploads/stores/" . $ruc . "/";

        $image->move($path , $name);

        $model = $product->update([
            'featured_image' => $path . $name
        ]);
        return response()->json(['status'=>"ok",'data'=>$product->featured_image]);

    }

    // Destacar producto
    public function featured_product(Request $request){
        $data = $request->all();
        $product = Product::find($data["id"]);
        if ($product->is_featured){
            $product->update(["is_featured" => false]);
        }else{
            $product->update(["is_featured" => true]);
        }
        return response()->json(['status'=>'ok', 'data'=>$product]);
    }
}
