<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_availability', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->string('product_CodeShort');
            $table->integer('quantity_24h');
            $table->integer('quantity_37days');
            $table->integer('quantity_delivery');
            $table->integer('quantity_next_delivery');
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
        //
    }
}
