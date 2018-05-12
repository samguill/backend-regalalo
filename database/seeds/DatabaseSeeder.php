<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(StoreTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(InterestsTableSeeder::class);
        //$this->call(ProductsTableSeeder::class);
        $this->call(ServicesTableSeeder::class);
        $this->call(ClientTableSeeder::class);
        $this->call(ExperienceTableSeeder::class);
        $this->call(ProductCharacteristicsTableSeeder::class);
        //$this->call(OrdersTableSeeder::class);
        //$this->call(OrderDetailTableSeeder::class);
        $this->call(ServiceCharacteristicsTableSeeder::class);

    }
}
