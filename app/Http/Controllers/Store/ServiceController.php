<?php

namespace App\Http\Controllers\Store;
use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Service;
use App\Models\ServiceCharacteristic;
use App\Models\ServiceCharacteristicDetail;
use App\Models\ServiceImage;
use App\Models\Store;
use App\Models\StoreImage;
use App\Utils\ParametersUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('store.services.index', compact('experiences'));
    }

    public function lists(){
        $Services = Service::where('store_id',Auth::user()->store->id)->where('status', 0)->orWhere('status', 1)->get();
        return response()->json($Services);
    }

    public function update(Request $request) {
        $data = $request->all();
        $Service = Service::find($data['id']);
        unset($data['id']);
       // $data['age'] = json_encode(array_map(function($age){return intval($age);},explode(",",$data['age'])));
        $data['experience'] = json_encode(array_map(function($experience){return intval($experience);},explode(",",$data['experience'])));
        if($Service->update($data))
            return response()->json(['status'=>'ok', 'data'=>$Service]);
        else
            return response()->json(['status'=>'error', 'message' => "No se pudo actualizar el registro."]);
    }

    public function delete(Request $request){
        $data = $request->all();
        $model = Service::find($data['id']);
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
            $data['store_id'] = Auth::user()->store->id;
         //   $data['age'] = json_encode(array_map(function($age){return intval($age);},explode(",",$data['age'])));
            $data['experience'] = json_encode(array_map(function($experience){return intval($experience);},explode(",",$data['experience'])));
            $model = Service::create($data);
        }catch(Exception $e) {

        }
        return response()->json(['status'=>"ok",'data'=>$model]);
    }

    public function edit(Request $request){
        $data = $request->all();

        $auth = Auth::user();
        $store_id =  $auth->store["id"];

        $service = Service::with('serviceimages.store_image', 'servicecharacteristicsdetail.characteristic')->where("id", $data["id"])->first();
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

        $experiences = array_map(
            function($item){
                return [
                    "id" => $item["id"],
                    "value" => $item["name"]
                ];
            }, Experience::all()->toArray()
        );

        //$service_characteristics = ParametersUtil::getServiceCharacteristics();
        $service_characteristics = ServiceCharacteristic::with('values')->get();

        if($auth["type"] == "S"){
            if($store_id == $service->store_id){
                //return response()->json($product->productimages);
                return view('store.services.edit', compact(
                        'store_images','service',
                        'store_id', 'sex',
                        'ages', 'experiences',
                        'service_characteristics')
                );
            }else{
                return redirect('/');
            }
        }

    }

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
                    $store_id = Auth::user()->store->id;

                    if($name !== "" && $age !== "") {

                        $service = Service::where('sku_code', $sku_code)->where('store_id',$store_id)->first();
                        if ($service) {
                            $service->update([
                                'name' => $name,
                                'slug' => Str::slug($name),
                                'discount' => $discount,
                                'price' => $price,
                                'description' => $description,
                                'age' => $age,
                                'availability' => 'A',
                                'sex' => 'G'
                            ]);
                        } else {


                            Service::create([

                                'name' => $name,
                                'sku_code' => $sku_code,
                                'discount' => $discount,
                                'price' => $price,
                                'description' => $description,
                                'age' => $age,
                                'availability' => 'A',
                                'sex' => 'G',
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
        $service= Service::find($service_id);
        //return response()->json($product);

        $name = $image->getClientOriginalName();

        $store_id = Auth::user()->store->id;
        $store = Store::find($store_id);
        $ruc = $store->ruc;

        $path = "uploads/stores/" . $ruc . "/";

        $image->move($path , $image->getClientOriginalName());

        $model = $service->update([
            'featured_image' => $path . $name
        ]);
        return response()->json(['status'=>"ok",'data'=>$service->featured_image]);

    }
}
