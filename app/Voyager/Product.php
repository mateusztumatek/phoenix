<?php

namespace App\Voyager;

use App\Services\Help;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable = ['name','content', 'price', 'prices_sellout', 'new', 'size', 'weight', 'profit', 'images', 'active', 'available'];
    protected $table = 'products';

}
