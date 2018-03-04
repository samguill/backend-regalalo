<?php

use Illuminate\Database\Seeder;
use App\Models\Store;

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
        for ($i = 0; $i <= 20; $i++) {
            Store::create([
                'business_name' => $faker->company,
                'ruc' => $faker->randomNumber(8),
                'legal_address' => $faker->address,
                'comercial_name'=> $faker->company,
                'address' => $faker->address,
                'phone' => $faker->randomNumber(8),
                'store_email' => $faker->companyEmail,
                'site_url' => $faker->domainName
            ]);
        }
    }
}
