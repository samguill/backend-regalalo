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
            $table->string('sku_code');
            $table->string('discount');
            $table->double('price');
            $table->enum('product_presentation', array('unidad', 'par', 'caja', 'docena'));
            $table->string('description');
            $table->string('age');
            $table->enum('availability', array('D', 'S', 'A')); //D = Delibery, S = Store, A = All
            $table->string('experience');

            $table->timestamps();
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
