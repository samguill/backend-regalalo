<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientDirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_directions', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name")->default("Casa");
            $table->string('city');
            $table->string('address');
            $table->double('latitude')->nullable()->default(null);
            $table->double('longitude')->nullable()->default(null);
            $table->unsignedInteger('client_id');
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
        Schema::dropIfExists('client_directions');
    }
}
