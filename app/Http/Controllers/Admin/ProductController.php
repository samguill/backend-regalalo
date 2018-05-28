<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use App\Models\Interest;
use App\Models\Product;
use App\Models\ProductCharacteristic;
use App\Models\Store;
use App\Models\StoreImage;
use App\Utils\ParametersUtil;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $products = Product::where('status', 0)->orWhere('status', 1)->get();
        return response()->json($products);
    }

    public function edit(Request $request){
        $data = $request->all();

        $product = Product::with('productimages.store_image')->where("id", $data["id"])->first();
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

        $product_characteristics = ProductCharacteristic::with('values')->get();

        return view('admin.products.edit', compact(
                'store_images','product',
                'store_id', 'sex',
                'ages', 'events',
                'interests', 'product_characteristics')
        );
    }

    public function update(Request $request) {
        $faker = Factory::create();
        $data = $request->all();
        $product = Product::find($data['id']);
        unset($data['id']);
        $data['slug'] = Str::slug($data["name"])  . $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit();
        $ages = $data['age'];
        $ages = explode(",", $ages);
        $ages = range(intval($ages[0]), intval($ages[1]));
        $ages = implode(",", $ages);
        $data['age'] = $ages;
        if($product->update($data))
            return response()->json(['status'=>'ok', 'data'=>$product]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    // Actualización de características del producto
    public function characteristics_update(Request $request){
        $data = $request->all();
        $product = Product::find($data["product_id"]);
        $product->update([
            'product_characteristic_id' => $data["product_characteristic_id"],
            'product_characteristic_values' => $data["product_characteristic_values"]
        ]);
        return response()->json(['status' => 'ok', 'data' => $data]);
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
                    $ages = explode("-", $age);
                    $availability = $val[7];

                    if($name !== "" && $ages[0] !== ""){
                        $ages = range(intval($ages[0]), intval($ages[1]));
                        $ages = implode(",", $ages);
                        $product = Product::where('sku_code', $sku_code)->where('store_id', $store_id)->first();
                        if($product){
                            $product->update([
                                'name'=>  $name,
                                'slug' => Str::slug($name) . $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit(),
                                'discount'=> $discount,
                                'price'=> $price,
                                'product_presentation'=> $product_presentation,
                                'description'=> $description,
                                'age'=> $ages,
                                'availability'=>  $availability
                            ]);
                        }else{
                            Product::create([
                                'name'=>  $name,
                                'slug' => Str::slug($name) . $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit(),
                                'sku_code'=> $sku_code,
                                'discount'=> $discount,
                                'price'=> $price,
                                'product_presentation'=> $product_presentation,
                                'description'=> $description ,
                                'age'=> $ages,
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
}