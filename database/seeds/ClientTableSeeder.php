<?php

use App\Models\Client;
use App\Models\ClientDirection;
use App\Models\ClientWishlist;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i <= 10; $i++) {
            $client = Client::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email,
                'password' => '123456'
            ]);

            ClientDirection::create([
                'name' => $faker->randomElement(array('Oficina', 'Casa')),
                'city' => $faker->city,
                'address' => $faker->address,
                'client_id' => $client->id
            ]);

            ClientWishlist::create([
                'product_id' => Product::all()->random()->id,
                'client_id' => $client->id
            ]);
        }
    }
}
