<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('intro')->nullable();
            $table->longText('content')->nullable();
            $table->float('price');
            $table->float('prices_sellout')->nullable();
            $table->string('new')->nullable();
            $table->string('size')->nullable();
            $table->string('weight')->nullable();
            $table->float('profit')->nullable();
            $table->text('images');
            $table->integer('count')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('available')->default(true);
            $table->string('color')->nullable();

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
        Schema::dropIfExists('products');
    }
}
