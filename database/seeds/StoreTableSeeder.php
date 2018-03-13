<?php

use App\Models\ComercialContact;
use App\Models\LegalRepresentative;
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
        for ($i = 0; $i <= 20; $i++) {
            $store = Store::create([
                'business_name' => $faker->company,
                'ruc' => $faker->randomNumber(8),
                'legal_address' => $faker->address,
                'comercial_name'=> $faker->company,
                'phone' => $faker->randomNumber(8),
                'site_url' => $faker->domainName
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
                'store_id' => $store->id
            ]);
        }
    }
}
