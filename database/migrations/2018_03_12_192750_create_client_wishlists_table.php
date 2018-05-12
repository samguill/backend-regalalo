<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientWishlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_wishlists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('product_id')->nullable()->default(null);
            $table->unsignedInteger('service_id')->nullable()->default(null);
            $table->unsignedInteger('client_id');
            //$table->foreign('product_id')->references('id')->on('products');
            $table->foreign('client_id')->references('id')->on('clients');
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
        Schema::dropIfExists('client_wishlists');
    }
}
