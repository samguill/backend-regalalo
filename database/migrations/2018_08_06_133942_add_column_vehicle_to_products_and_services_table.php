<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnVehicleToProductsAndServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('products', function (Blueprint $table) {
            $table->string('urbaner_vehicle')->nullable()->default(null)->after('store_id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('urbaner_vehicle')->nullable()->default(null)->after('store_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('products', function (Blueprint $table){
            $table->dropColumn("urbaner_vehicle");
        });

        Schema::table('services', function (Blueprint $table){
            $table->dropColumn("urbaner_vehicle");
        });
    }
}
