<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['product_id', 'name', 'image', 'facebook', 'instagram', 'site'];
    protected $table = 'galleries';

    public function product(){
        return Product::findOrFail($this->product_id);
    }
    public function prod(){
        return $this->belongsTo('App\Product', 'product_id');
    }
}
