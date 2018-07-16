<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteColumnsToStoreBranches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Eliminando campos de las tablas
        Schema::table('store_branches', function (Blueprint $table) {
            $table->dropColumn('business_hour_1');
            $table->dropColumn('business_hour_2');
        });

        Schema::create('branch_opening_hours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('weekday')->default(null)->nullable();
            $table->time('start_hour')->default(null)->nullable();
            $table->time('end_hour')->default(null)->nullable();
            $table->unsignedInteger('store_branche_id');
            $table->foreign('store_branche_id')->references('id')->on('store_branches');
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

        Schema::table('store_branches', function (Blueprint $table) {
            $table->string('business_hour_1')->nullable();
            $table->string('business_hour_2')->nullable();
        });

        Schema::dropIfExists('branch_opening_hours');

    }
}
