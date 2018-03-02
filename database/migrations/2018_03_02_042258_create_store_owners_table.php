<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_owners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('phone');
            $table->integer('dni');
            $table->unsignedInteger('store_id');
            $table->unsignedInteger('user_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('store_owners');
    }
}
