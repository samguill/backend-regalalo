<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_branches', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->string('address');
            $table->integer('phone')->nullable();
            $table->string('branch_email')->nullable();
            $table->string('business_hour_1')->nullable();
            $table->string('business_hour_2')->nullable();
            $table->unsignedInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
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
        Schema::dropIfExists('store_branches');
    }
}
