<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCharacteristicDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_characteristic_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("product_id");
            $table->unsignedInteger('product_characteristic_id');
            $table->string('product_characteristic_values')->nullable()->default(null);

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_characteristic_id')->references('id')->on('product_characteristics');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_characteristic_details');
    }
}
