<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id')->default(0);
            $table->unsignedInteger('service_id')->default(0);
            $table->integer('quantity');
            $table->double('price');
            $table->double('price_delivery')->default(null)->nullable();
            $table->string('tracking_url')->default(null)->nullable();
            $table->string('tracking_code')->default(null)->nullable();
            $table->unsignedInteger('store_branche_id');
            $table->double('igv')->default(null)->nullable();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('store_branche_id')->references('id')->on('store_branches');

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
        Schema::dropIfExists('order_details');
    }
}
