<?php

use App\Models\ComercialContact;
use App\Models\Inventory;
use App\Models\LegalRepresentative;
use App\Models\Product;
use App\Models\StoreImage;
use App\User;
use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\StoreBranch;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $user_precio = User::create([
            'name'=>'Precio Peru',
            'email'=>'admin@precio.pe',
            'password'=>'123456',
            'type' => 'S'
        ]);

        $store_precio = Store::create([
            'business_name' => "Precio SAC",
            'ruc' => 92760388,
            'legal_address' => $faker->address,
            'comercial_name'=> "Precio Perú",
            'slug'=> \Illuminate\Support\Str::slug("Precio Perú"),
            'phone' => $faker->randomNumber(8),
            'site_url' => 'http://precio.pe',
            'status' => 1,
            'business_turn' => 'Ventas',
            'monthly_transactions' => 100,
            'average_amount' => 100,
            'maximum_amount' => 1000,
            'payme_comerce_id' => 9092,
            'payme_wallet_id' => 641,
            'payme_acquirer_id' => 144,
            'payme_process_status' => 1,
            'user_id' => $user_precio->id
        ]);

        ComercialContact::create([
            'name' => "Marzio Perez",
            'document_number' => "444444",
            'phone' => "77777",
            'email' => 'admin@precio.pe',
            'position' => 'Gerente General',
            'store_id' => $store_precio->id
        ]);

        $user_regalalo = User::create([
            'name' => 'Arturo García',
            'email' => 'arturo.garcia@regalalo.pe',
            'password' => '612607',
            'type' => 'S',
            'status' => 1
        ]);

        $store_regalalo = Store::create([
            'business_name' => "Regalalo SAC",
            'ruc' => 92760388,
            'legal_address' => $faker->address,
            'comercial_name'=> "Regalalo Perú",
            'slug'=> \Illuminate\Support\Str::slug("Regalalo Perú"),
            'phone' => $faker->randomNumber(8),
            'site_url' => 'http://regalalo.pe',
            'status' => 1,
            'business_turn' => 'Ventas',
            'monthly_transactions' => 100,
            'average_amount' => 100,
            'maximum_amount' => 1000,
            'payme_comerce_id' => 420,
            'payme_wallet_id' => 641,
            'payme_acquirer_id' => 144,
            'payme_wallet_password' => "bnjNhRABweZCFdx=33744339",
            'payme_gateway_password' => "jRHMrxZHBZxKbRDVu@3738666942",
            'payme_process_status' => 1,
            'user_id' => $user_regalalo->id
        ]);

        ComercialContact::create([
            'name' => "Arturo García",
            'document_number' => "444444",
            'phone' => "77777",
            'email' => 'arturo.garcia@regalalo.pe',
            'position' => 'Gerente General',
            'store_id' => $store_regalalo->id
        ]);

        $branch = StoreBranch::create([
            'name' => 'Local de la Venta de Garaje',
            'latitude' => -12.085937,
            'longitude' => -76.9934232,
            'address' => 'Calle Alejandro Moreno Alcala No 241, San Borja',
            'phone' => '44105595',
            'branch_email' => 'edward.white@hegmann.com',
            'business_hour_1' => '10:00 - 20:00',
            'business_hour_2' => '13:00 - 20:00',
            'store_id' => $store_regalalo->id
        ]);

        $product_1 = Product::create([
            'name' => 'Maceta Miniatura de Mono y Panda',
            'slug' => 'maceta-miniatura-de-mono-y-panda',
            'sku_code' => 'P01',
            'discount' => 0,
            'price' => 35,
            'product_presentation' => 'unidad',
            'description' => '<p>Ahora tu plantita<strong>&nbsp;&iexcl;se alimenta sola!&nbsp;</strong>Solo coloca el&nbsp;<strong>agua&nbsp;</strong>en el vaso&nbsp;y tu Panda&nbsp;la absorver&aacute;.</p><p><strong>Tu Panda literalmente toma agua a trav&eacute;s de su ca&ntilde;ita&nbsp;<img alt="surprise" src="http://regalalo.pe/sites/all/libraries/ckeditor/plugins/smiley/images/omg_smile.gif" style="height:20px; width:20px" />&nbsp;</strong>&iquest;No es genial?&nbsp;<strong><img alt="wink" src="http://regalalo.pe/sites/all/libraries/ckeditor/plugins/smiley/images/wink_smile.gif" style="height:20px; width:20px" /></strong></p><p>Ya no tienes que preocuparte por regar tu macetita, ella sola consume el agua que necesita.</p><p>&iexcl;Regala este detallazo&nbsp;<img alt="heart" src="http://regalalo.pe/sites/all/libraries/ckeditor/plugins/smiley/images/heart.gif" style="height:20px; width:20px" />!</p><p>&nbsp;</p><p><strong>Tip.</strong>&nbsp;Una vez que tu plantita cumpla su ciclo, puedes hacer germinar lentejitas o frijolitos.</p>',
            'age'=> '14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50',
            'availability' => 'S',
            'event' => '1,6',
            'interest' => '9,4',
            'store_id' => $store_regalalo->id,
            'featured_image' => 'uploads/stores/92760388/Mono01.jpg',
            'status' => 0
        ]);

        Inventory::create([
            'product_id' => $product_1->id,
            'quantity' => 100,
            'store_branche_id' => $branch->id
        ]);

        $product_2 = Product::create([
            'name' => 'Macetas de Modelos Variados',
            'slug' => 'macetas-de-modelos-variados',
            'sku_code' => 'P02',
            'discount' => 0,
            'price' => 40,
            'product_presentation' => 'unidad',
            'description' => '<p>Ahora tu plantita<strong>&nbsp;&iexcl;se alimenta sola!&nbsp;</strong>Solo coloca el&nbsp;<strong>agua&nbsp;</strong>en el vaso&nbsp;y tu Panda&nbsp;la absorver&aacute;.</p>',
            'age'=> '14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50',
            'availability' => 'S',
            'event' => '1,6',
            'interest' => '9,4',
            'store_id' => $store_regalalo->id,
            'featured_image' => 'uploads/stores/92760388/Maceta01.png',
            'status' => 0
        ]);

        Inventory::create([
            'product_id' => $product_2->id,
            'quantity' => 100,
            'store_branche_id' => $branch->id
        ]);

        $product_3 = Product::create([
            'name' => 'Macetas de Modelos Variados',
            'slug' => 'macetas-de-modelos-variados' . $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit(),
            'sku_code' => 'P02',
            'discount' => 0,
            'price' => 45,
            'product_presentation' => 'unidad',
            'description' => '<p>Ahora tu plantita<strong>&nbsp;&iexcl;se alimenta sola!&nbsp;</strong>Solo coloca el&nbsp;<strong>agua&nbsp;</strong>en el vaso&nbsp;y tu Panda&nbsp;la absorver&aacute;.</p>',
            'age'=> '14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50',
            'availability' => 'S',
            'event' => '1,6',
            'interest' => '9,4',
            'store_id' => $store_regalalo->id,
            'featured_image' => 'uploads/stores/92760388/Maceta01.png',
            'status' => 0
        ]);

        Inventory::create([
            'product_id' => $product_3->id,
            'quantity' => 100,
            'store_branche_id' => $branch->id
        ]);

        $product_4 = Product::create([
            'name' => 'Macetas de Modelos Variados',
            'slug' => 'macetas-de-modelos-variados' . $faker->randomDigit() . $faker->randomDigit() . $faker->randomDigit(),
            'sku_code' => 'P02',
            'discount' => 0,
            'price' => 50,
            'product_presentation' => 'unidad',
            'description' => '<p>Ahora tu plantita<strong>&nbsp;&iexcl;se alimenta sola!&nbsp;</strong>Solo coloca el&nbsp;<strong>agua&nbsp;</strong>en el vaso&nbsp;y tu Panda&nbsp;la absorver&aacute;.</p>',
            'age'=> '14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50',
            'availability' => 'S',
            'event' => '1,6',
            'interest' => '9,4',
            'store_id' => $store_regalalo->id,
            'featured_image' => 'uploads/stores/92760388/Maceta01.png',
            'status' => 0
        ]);

        Inventory::create([
            'product_id' => $product_4->id,
            'quantity' => 100,
            'store_branche_id' => $branch->id
        ]);

        $user_hp = User::create([
            'name' => 'Miguel Falcón',
            'email' => 'admin@hp.com',
            'password' => '011227',
            'type' => 'S',
            'status' => 1
        ]);

        $store_hp = Store::create([
            'business_name' => "HP SAC",
            'ruc' => $faker->randomNumber(8),
            'legal_address' => $faker->address,
            'comercial_name'=> "HP",
            'slug'=> \Illuminate\Support\Str::slug("HP"),
            'phone' => $faker->randomNumber(8),
            'site_url' => 'http://hp.pe',
            'status' => 1,
            'business_turn' => 'Ventas',
            'monthly_transactions' => 100,
            'average_amount' => 100,
            'maximum_amount' => 1000,
            'payme_comerce_id' => 9092,
            'payme_wallet_id' => 641,
            'payme_acquirer_id' => 144,
            'payme_process_status' => 1,
            'user_id' => $user_hp->id
        ]);

        ComercialContact::create([
            'name' => "Miguel Falcón",
            'document_number' => "444444",
            'phone' => "77777",
            'email' => 'admin@hp.com',
            'position' => 'Gerente General',
            'store_id' => $store_hp->id
        ]);

        /*for ($i = 0; $i <= 20; $i++) {
            $name = $faker->company;
            $store = Store::create([
                'business_name' => $faker->company,
                'ruc' => $faker->randomNumber(8),
                'legal_address' => $faker->address,
                'comercial_name'=> $name,
                'slug'=> \Illuminate\Support\Str::slug($name),
                'phone' => $faker->randomNumber(8),
                'site_url' => $faker->domainName,
                'status' => $faker->randomElement([0,1])
            ]);

            StoreBranch::create([
                'name' => $faker->streetName,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'address' => $faker->address,
                'phone' => $faker->randomNumber(8),
                'branch_email' => $faker->companyEmail,
                'business_hour_1' => '10:00 - 20:00',
                'business_hour_2'=> '13:00 - 20:00',
                'store_id' => $store->id
            ]);

            LegalRepresentative::create([
                'name' => $faker->firstName . " " . $faker->lastName,
                'document_number' => $faker->randomNumber(8),
                'phone' => $faker->randomNumber(8),
                'position' => "Representante Legal",
                'store_id' => $store->id
            ]);

            ComercialContact::create([
                'name' => $faker->firstName . " " . $faker->lastName,
                'document_number' => $faker->randomNumber(8),
                'phone' => $faker->randomNumber(8),
                'email' => $faker->companyEmail,
                'position' => "Ventas",
                'store_id' => $store->id
            ]);

            StoreImage::create([
                'store_id' => $store->id,
                'image_path' => 'img/logo-regalalo.png'
            ]);
        }

        $store->update(['user_id'=>3, 'status'=> 1]); */
    }
}
