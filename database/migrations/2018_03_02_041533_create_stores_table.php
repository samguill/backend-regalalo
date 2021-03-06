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
            // Datos legales
            // Razón social
            $table->string('business_name');
            $table->bigInteger('ruc');
            $table->string('legal_address');
            $table->string('comercial_name')->nullable();
            $table->string('slug')->nullable()->default(null);
            $table->integer('phone')->nullable();
            $table->string('site_url')->nullable();
            // Financiero
            $table->enum('financial_entity', array('','BCP','BBVA','INTERBANK','SCOTIABANK','BANBIF'))->nullable()->default('');
            $table->enum('account_type', array('', 'Cuenta de Ahorros','Cuenta Corriente'))->nullable()->default('');
            $table->string('account_statement_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('cci_account_number')->nullable();

            // Monitoreo PayMe
            $table->string('business_turn')->nullable();
            $table->string('monthly_transactions')->nullable();
            $table->string('average_amount')->nullable();
            $table->string('maximum_amount')->nullable();

            // Payme
            $table->string('payme_comerce_id')->nullable();
            $table->string('payme_wallet_id')->nullable();
            $table->string('payme_acquirer_id')->nullable();
            $table->string('payme_wallet_password')->nullable();
            $table->string('payme_gateway_password')->nullable();
            // 0: pendiente - 1: integración - 2: producción
            $table->integer('payme_process_status')->default(0);

            $table->string('analytics_id')->nullable();
            $table->string('meta_title')->nullable()->default(null);
            $table->text('meta_description')->nullable()->default(null);

            $table->string('logo_store')->nullable()->default(null);

            // 0: pendiente - 1: activo - 2: inactivo
            $table->integer('status')->default(0);
            $table->unsignedInteger('user_id')->nullable();
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
        Schema::dropIfExists('stores');
    }
}
