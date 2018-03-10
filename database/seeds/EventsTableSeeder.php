<?php

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::create(['name'=>'Amigo Secreto','description'=>'Amigo Secreto']);
        Event::create(['name'=>'Aniversarios','description'=>'Aniversarios']);
        Event::create(['name'=>'Año Nuevo','description'=>'Año Nuevo']);
        Event::create(['name'=>'Baby Shower','description'=>'Baby Shower']);
        Event::create(['name'=>'Bodas','description'=>'Bodas']);
        Event::create(['name'=>'Cumpleaños','description'=>'Cumpleaños']);
        Event::create(['name'=>'Graduación','description'=>'Graduación']);
        Event::create(['name'=>'Navidad','description'=>'Navidad']);
        Event::create(['name'=>'Quinceañeros','description'=>'Quinceañeros']);
        Event::create(['name'=>'San Valentín','description'=>'San Valentín']);
    }
}
