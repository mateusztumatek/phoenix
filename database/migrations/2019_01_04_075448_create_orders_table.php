<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('name');
            $table->string('street');
            $table->string('street_number');
            $table->string('flat_number')->nullable();
            $table->string('city');
            $table->string('phone_number');
            $table->string('email');
            $table->text('comments')->nullable();
            $table->integer('delivery_id');
            $table->string('postal_code');
            $table->text('cart');
            $table->float('price');
            $table->float('total_price');
            $table->string('payment_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_state')->nullable();
            $table->string('payment_create_time')->nullable();
            $table->string('payment_update_time')->nullable();

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
        Schema::dropIfExists('orders');
    }
}
