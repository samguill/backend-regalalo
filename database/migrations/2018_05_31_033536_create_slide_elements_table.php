<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlideElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slide_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->string("content")->nullable()->default(null);
            $table->enum("type", ["link", "button", "text", "title", "subtitle"])->nullable()->default(null);
            $table->string("url")->nullable()->default(null);
            $table->integer("position_x")->nullable()->default(null);
            $table->integer("position_y")->nullable()->default(null);
            $table->string("animation")->nullable()->default(null);
            $table->unsignedInteger('slide_id');
            $table->foreign('slide_id')->references('id')->on('slides');
            $table->softDeletes();
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
        Schema::dropIfExists('slide_elements');
    }
}
