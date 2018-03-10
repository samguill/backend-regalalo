<?php

use App\Models\Interest;
use Illuminate\Database\Seeder;

class InterestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Interest::create(['name'=>'Arte/Antigüedad ','description'=>'Arte/Antigüedad ']);
        Interest::create(['name'=>'Automovilismo','description'=>'Automovilismo']);
        Interest::create(['name'=>'Adultos (+18)','description'=>'Adultos (+18)']);
        Interest::create(['name'=>'Coleccionables','description'=>'Coleccionables']);
        Interest::create(['name'=>'Deporte/Outdoor','description'=>'Deporte/Outdoor']);
        Interest::create(['name'=>'Entretenimiento','description'=>'Entretenimiento']);
        Interest::create(['name'=>'Fiesta/Juerga','description'=>'Fiesta/Juerga']);
        Interest::create(['name'=>'Gastronomía','description'=>'Gastronomía']);
        Interest::create(['name'=>'Hogar','description'=>'Hogar']);
        Interest::create(['name'=>'Juegos','description'=>'Juegos']);
    }
}
