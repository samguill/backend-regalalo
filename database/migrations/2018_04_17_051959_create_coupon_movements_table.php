<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_movements', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('movement_type', array('I', 'E'));
            $table->integer('quantity');
            $table->unsignedInteger('coupon_id');
            $table->unsignedInteger('order_id');
            $table->timestamps();
            $table->foreign('coupon_id')->references('id')->on('coupons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_movements');
    }
}
