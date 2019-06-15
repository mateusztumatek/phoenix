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
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('status')->default('nowe');
            $table->string('name');
            $table->string('street');
            $table->integer('street_number');
            $table->integer('flat_number')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->string('email')->nullable();
            $table->text('comments')->nullable();
            $table->text('images')->nullable();
            $table->float('price');
            $table->string('hash')->nullable();
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
