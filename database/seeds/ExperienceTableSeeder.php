<?php

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Experience::create(['name'=>'Aventura','description'=>'Aventura']);
        Experience::create(['name'=>'Belleza y Salud','description'=>'Belleza y Salud']);
        Experience::create(['name'=>'Cursos y Talleres','description'=>'Cursos y Talleres']);
        Experience::create(['name'=>'Deporte','description'=>'Deporte']);
        Experience::create(['name'=>'Entretenimiento','description'=>'Entretenimiento']);
        Experience::create(['name'=>'Fiesta y Juerga','description'=>'Fiesta y Juerga']);
        Experience::create(['name'=>'Fotografía y Video','description'=>'Fotografía y Video']);
        Experience::create(['name'=>'Gastronomía','description'=>'Gastronomía']);

    }
}
