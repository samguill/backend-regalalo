<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Service;
use App\Models\ServiceCharacteristic;
use App\Models\ServiceCharacteristicDetail;
use App\Models\ServiceImage;
use App\Models\Store;
use App\Models\StoreImage;
use App\Models\Tag;
use App\Utils\ParametersUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index(){
        $experiences = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Experience::all()->toArray()
        );

        $stores = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["comercial_name"]
                ];
            }, Store::all()->toArray()
        );
        return view('admin.services.index', compact('experiences', 'stores'));
    }

    public function lists(){
        $services = Service::where('status', 0)->orWhere('status', 1)->get();
        return response()->json($services);
    }

    public function create(Request $request){
        try{
            $data = $request->all();
            $data['slug'] = Str::slug($data["name"]);
            $data["age"] = $data["min_age"] . "," . $data["max_age"];
            $model = Service::create($data);
        }catch(Exception $e) {
            response()->json(['status'=>"ok",'message'=>$e->getMessage()]);
        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function update(Request $request) {
        $data = $request->all();
        $service = Service::find($data['id']);
        $data["age"] = $data["min_age"] . "," . $data["max_age"];
        unset($data['id']);
        $data['slug'] = Str::slug($data["name"]);
        if($service->update($data))
            return response()->json(['status'=>'ok', 'data'=>$service]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Service::find($data['id']);
        $model->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function edit(Request $request){
        $data = $request->all();

        $service = Service::with('serviceimages.store_image', 'servicecharacteristicsdetail.characteristic')->where("id", $data["id"])->first();
        $store_images = StoreImage::where('store_id', $service->store->id)->get();

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

        $experiences = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Experience::all()->toArray()
        );

        $tags = array_map(
            function($item){
                return [
                    "id" => $item["key"],
                    "value" => $item["key"]
                ];
            }, Tag::all()->toArray()
        );

        $service_characteristics = ServiceCharacteristic::with('values')->get();


        return view('admin.services.edit', compact(
                'store_images','service',
                'store_id', 'sex', 'tags',
                'ages', 'experiences',
                'service_characteristics')
        );

    }

    // Actualización de características del servicio
   /* public function characteristics_update(Request $request){

        $data = $request->all();
        $service = Service::find($data["service_id"]);
        $service->update([
            'service_characteristic_id' => $data["service_characteristic_id"],
            'service_characteristic_values' => $data["service_characteristic_values"]
        ]);
        return response()->json(['status' => 'ok', 'data' => $data]);
    }*/

    // Mantenimiento de características del servicio
    public function characteristics_store(Request $request){
        $data = $request->all();
        $detail = ServiceCharacteristicDetail::create([
            "service_id" => $data["service_id"],
            'service_characteristic_id' => $data["service_characteristic_id"],
            'service_characteristic_values' => $data["service_characteristic_values"]
        ]);
        $model = ServiceCharacteristicDetail::with('characteristic')->find($detail->id);
        return response()->json(['status' => 'ok', 'data' => $model]);
    }

    public function characteristics_update(Request $request){
        $data = $request->all();
        $product = ServiceCharacteristicDetail::find($data["id"]);
        $product->update([
            'service_characteristic_id' => $data["service_characteristic_id"],
            'service_characteristic_values' => $data["service_characteristic_values"]
        ]);
        $model = ServiceCharacteristicDetail::with('characteristic')->find($data["id"]);
        return response()->json(['status' => 'ok', 'data' => $model]);
    }

    public function characteristics_delete(Request $request){
        $data = $request->all();
        $product = ServiceCharacteristicDetail::find($data["id"]);
        $product->delete();
        $model = ServiceCharacteristicDetail::with('characteristic')->find($data["id"]);
        return response()->json(['status' => 'ok', 'data' => $model]);
    }

    // Carga masiva
    public function masive_charge(Request $request){
        $file = $request->file('excel');
        $store_id = $request->input('store_id');
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
                    $description = $val[4];
                    $age = $val[5];

                    if($name !== "" && $age !== "") {

                        $service = Service::where('sku_code', $sku_code)->where('store_id', $store_id)->first();
                        if ($service) {
                            $service->update([
                                'name' => $name,
                                'slug' => Str::slug($name),
                                'discount' => $discount,
                                'price' => $price,
                                'description' => $description,
                                'age' => $age,
                                'availability' => 'A',
                                'sex'=>  'G'
                            ]);
                        } else {

                            Service::create([
                                'name' => $name,
                                'slug' => Str::slug($name),
                                'sku_code' => $sku_code,
                                'discount' => $discount,
                                'price' => $price,
                                'description' => $description,
                                'age' => $age,
                                'availability' => 'A',
                                'sex'=>  'G',
                                'store_id' => $store_id
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


    // Asignación de imágenes
    public function add_image_service(Request $request){
        $data = $request->all();
        try {
            $model_create = ServiceImage::create([
                'store_image_id' => $data["id"],
                'service_id' => $data["service_id"]
            ]);
            $model = ServiceImage::with('store_image')->where('id', $model_create->id)->first();
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function delete_image_service(Request $request){
        $service_image_id = $request->input('id');
        $service_image = ServiceImage::find($service_image_id);
        $model = $service_image;
        $service_image->delete();
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    // Subida de imagen destacada
    public function store_featured_image(Request $request){
        $image = $request->file('file');
        $service_id = $request->input('service_id');
        $service = Service::find($service_id);
        //return response()->json($product);

        $name = $service->slug . "-" . $service_id . "." . $image->getClientOriginalExtension();

        $store = Store::find($service->store_id);
        $ruc = $store->ruc;

        $path = "uploads/stores/" . $ruc . "/";

        $image->move($path , $name);

        $model = $service->update([
            'featured_image' => $path . $name
        ]);
        return response()->json(['status'=>"ok",'data'=>$service->featured_image]);
    }

    public function update_seo(Request $request){
        $data = $request->all();
        $service = Service::find($data['id']);
        unset($data['id']);
        if($service->update($data))
            return response()->json(['status'=>'ok', 'data'=>$service]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    // Destacar servicio
    public function featured_service(Request $request){
        $data = $request->all();
        $service = Service::find($data["id"]);
        $service->update(["is_featured" => true]);
        return response()->json(['status'=>'ok', 'data'=>$service]);
    }
}
