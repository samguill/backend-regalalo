<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateProductsStoredProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        $query = 'SELECT products.id, products.store_id, products.name, products.sku_code, products.price, products.is_featured ,stores.comercial_name FROM products INNER JOIN stores ON stores.id = products.store_id WHERE products.deleted_at IS NULL';
        DB::unprepared('DROP PROCEDURE IF EXISTS products; CREATE PROCEDURE products() BEGIN '. $query .'; END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
