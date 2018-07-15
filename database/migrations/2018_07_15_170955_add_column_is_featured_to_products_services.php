<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsFeaturedToProductsServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_featured')->nullable()->default(false)->after('featured_image');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_featured')->nullable()->default(false)->after('featured_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table){
            $table->dropColumn("is_featured");
        });

        Schema::table('services', function (Blueprint $table){
            $table->dropColumn("is_featured");
        });
    }
}
