<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('sku_code');
            $table->string('discount');
            $table->double('price');
            $table->enum('product_presentation', array('unidad', 'par', 'caja', 'docena'));
            $table->text('description');
            $table->string('age');
            $table->enum('availability', array('D', 'S', 'A')); //D = Delivery, S = Store, A = All
            $table->string('event')->nullable()->default(null);
            $table->string('interest')->nullable()->default(null);
            $table->enum('sex', array('G', 'F', 'M'))->default('G'); //G = General, F = Mujer , M = Hombre
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('product_characteristic_id')->nullable()->default(null);
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
        Schema::dropIfExists('products');
    }
}
