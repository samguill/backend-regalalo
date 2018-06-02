<?php

use App\Models\Offer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Offer::create([
            'title' => "Aventura",
            'slug' => "aventura",
            'description' => "Prepárate para la aventura",
            'order' => 1,
            'image' => "uploads/offers/adventure-2018-06.jpg"
        ]);

        Offer::create([
            'title' => "Accesorios de moda",
            'slug' => "accesorios-de-moda",
            'description' => "El estilo siempre está en los detalles",
            'order' => 2,
            'image' => "uploads/offers/accesories-2018-06.jpg"
        ]);

        Offer::create([
            'title' => "Detalles especiales",
            'slug' => "detalles-especiales",
            'description' => "Eso que buscabas para esa persona especial...",
            'order' => 3,
            'image' => "uploads/offers/special-details-2018-06.jpg"
        ]);

        Offer::create([
            'title' => "Deporte",
            'slug' => "deporte",
            'description' => "Si quieres algo, entrena para ello.",
            'order' => 4,
            'image' => "uploads/offers/sports-2018-06.jpg"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $offers = Offer::all();
        foreach ($offers as $offer){
            $offer->delete();
        }
    }
}
