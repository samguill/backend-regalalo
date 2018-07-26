<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceCharacteristicDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_characteristic_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("service_id");
            $table->unsignedInteger('service_characteristic_id');
            $table->string('service_characteristic_values')->nullable()->default(null);

            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('service_characteristic_id')->references('id')->on('service_characteristics');
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
        Schema::dropIfExists('service_characteristic_details');
    }
}
