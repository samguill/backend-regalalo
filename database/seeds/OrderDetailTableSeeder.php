<?php

use Illuminate\Database\Seeder;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;

class OrderDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $limit = 300;
        for ($i = 0; $i < $limit; $i++) {
            OrderDetail::create([
                'order_id' => Order::all()->random()->id,
                'product_id' => Product::all()->random()->id,
                'quantity' => $faker->numberBetween(10,20),
                'price' => $faker->randomNumber(3).'.'.$faker->randomNumber(2),
                'igv' => $faker->randomNumber(3).'.'.$faker->randomNumber(2),

            ]);

        }

    }
}
