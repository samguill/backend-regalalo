<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable()->default(null);
            $table->string('sku_code');
            $table->string('discount')->default(0);
            $table->double('price');
            $table->string('description');
            $table->string('age');
            $table->enum('availability', array('D', 'S', 'A')); //D = Delivery, S = Store, A = All
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('service_characteristic_id')->nullable()->default(null);;
            $table->string('experience')->nullable()->default(null);
            $table->enum('sex', array('G', 'F', 'M')); //G = General, F = Mujer , M = Hombre
            $table->string('featured_image')->nullable()->default(null);

            // Meta datos
            $table->string('meta_title')->nullable()->default(null);
            $table->text('meta_description')->nullable()->default(null);

            // 0: pendiente - 1: activo - 2: inactivo
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->foreign('store_id')->references('id')->on('stores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
