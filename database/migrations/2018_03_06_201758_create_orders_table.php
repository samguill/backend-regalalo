<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_code');
            $table->enum('status',['P', 'C', 'A','R','D'])->default('P'); //P: Pending, A: Atended, R: Rejected payment, D: Delivery pendiente
            $table->double('total');
            $table->double('sub_total');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('client_direction_id')->default(null)->nullable();

            $table->unsignedInteger('store_id');
            $table->boolean('delivery')->default(false);
            $table->timestamps();
            $table->foreign('client_id')->references('id')->on('clients');
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
        Schema::dropIfExists('orders');
    }
}
