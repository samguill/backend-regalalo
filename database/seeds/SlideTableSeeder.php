<?php

use App\Models\Slide;
use App\Models\SlideElement;
use Illuminate\Database\Seeder;

class SlideTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $slide_1 = Slide::create([
            'name' => "Slide 1",
            'image' => "uploads/slides/slide-1.jpg"
        ]);

        SlideElement::create([
            "content" => "ROPA DE MUJER",
            "type" => "subtitle",
            "position_x" => 60,
            "position_y" => 10,
            "animation" => "fadeIn",
            'slide_id' => $slide_1->id
        ]);

        SlideElement::create([
            "content" => "Short jean",
            "type" => "title",
            "position_x" => 60,
            "position_y" => 25,
            "animation" => "fadeIn",
            'slide_id' => $slide_1->id
        ]);

        SlideElement::create([
            "content" => "DETALLES",
            "type" => "button",
            "position_x" => 60,
            "position_y" => 40,
            "animation" => "fadeIn",
            'slide_id' => $slide_1->id
        ]);
    }
}
