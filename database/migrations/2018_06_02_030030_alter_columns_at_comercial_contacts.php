<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsAtComercialContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comercial_contacts', function (Blueprint $table){
            $table->string('name')->nullable()->default(null)->change();
            $table->integer('document_number')->nullable()->default(null)->change();
            $table->integer('phone')->nullable()->default(null)->change();
            $table->string('email')->nullable()->default(null)->change();
            $table->string('position')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comercial_contacts', function (Blueprint $table){
            $table->string('name')->change();
            $table->integer('document_number')->change();
            $table->integer('phone')->change();
            $table->string('email')->change();
            $table->string('position')->change();
        });
    }
}
