<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
/*$table->increments('id');
$table->integer('product_id')->unsigned();
$table->integer('rate');
$table->string('description');*/
    protected $fillable = ['product_id', 'author', 'rate', 'description'];
    protected $table = 'rates';

    public function getProduct(){
        return Product::where('macma_id', $this->product_id)->first();
    }
}
