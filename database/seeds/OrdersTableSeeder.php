<?php

use App\Models\Client;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Store;
use App\Models\Product;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker\Factory::create();


        $limit = 20;
        for ($i = 0; $i < $limit; $i++) {
            $p1=$faker->numberBetween(1,50);
            $p2=$faker->numberBetween(1,50);
            if($p1+$p2<100)
                $p3=$faker->numberBetween(1,100-($p1+$p2));
            if($p1+$p2+$p3<100)
                $p4=100-($p1+$p2+$p3);

            Order::create([
                'order_code' => $faker->postcode,
                'total' => $faker->randomNumber(3).'.'.$faker->randomNumber(2),
                'store_id' => Store::all()->random()->id,
                'client_id' => Client::all()->random()->id,

            ]);

        }
    }
}
