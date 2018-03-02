<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('ruc');
            // Razón social
            $table->string('business_name');
            // Domicilio Legal
            $table->string('legal_address');
            // Dirección de la Tienda
            $table->string('store_address');
            $table->integer('phone');
            $table->string('payme_user')->nullable();
            $table->string('payme_password')->nullable();
            $table->string('payme_integration_key')->nullable();
            $table->string('payme_production_key')->nullable();
            // 0: pendiente - 1: integración - 2: producción
            $table->integer('payme_process_status')->default(0);
            // 0: pendiente - 1: activo - 2: inactivo
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('stores');
    }
}
