<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailCharacteristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail_characteristics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("order_detail_id");
            $table->unsignedInteger("product_id")->default(0);
            $table->unsignedInteger("service_id")->default(0);
            $table->string("characteristic")->nullable()->default(null);
            $table->string("characteristic_value")->nullable()->default(null);

            $table->foreign('order_detail_id')->references('id')->on('order_details');
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
        Schema::dropIfExists('order_detail_characteristics');
    }
}
