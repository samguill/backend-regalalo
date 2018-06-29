<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSeoToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // Meta datos
            $table->string('meta_title')->nullable()->default(null)->after('category_id');
            $table->text('meta_description')->nullable()->default(null)->after('category_id');
            $table->text('meta_keywords')->nullable()->default(null)->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table){
            $table->dropColumn("meta_title");
            $table->dropColumn("meta_description");
            $table->dropColumn("meta_keywords");
        });
    }
}
