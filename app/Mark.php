<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
/*$table->integer('macma_id')->unsigned();
$table->string('name');
$table->smallInteger('colorsMin');
$table->smallInteger('colorsMax');
$table->integer('price_max');*/
    protected $fillable = ['macma_id', 'name', 'colorsMin', 'colorsMax', 'min_quantity', 'price_max'];
}
