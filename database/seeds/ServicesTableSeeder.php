<?php

use App\Models\Service;
use App\Models\Store;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $limit = 100;
        for ($i = 0; $i < $limit; $i++) {
            $name = $faker->realText($faker->numberBetween(10,20));
            Service::create([
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name),
                'sku_code'=>$faker->randomNumber(3).$faker->realText(10),
                'discount' => $faker->randomNumber(2).'.'.$faker->randomNumber(2),
                'price' => $faker->randomNumber(3).'.'.$faker->randomNumber(2),
                'product_presentation' =>  $faker->randomElement(['unidad', 'par', 'caja', 'docena']),
                'description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
                'age' => $faker->numberBetween(10,20).','.$faker->numberBetween(21,50),
                'availability' =>  $faker->randomElement(['D', 'S', 'A']), //D = Delivery, S = Store, A = All
                'store_id' => Store::all()->random()->id
            ]);

        }
    }
}
