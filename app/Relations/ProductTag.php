<?php

namespace App\Relations;

use App\Services\Help;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;


class ProductTag extends Model
{
    protected $fillable = ['product_id', 'tag'];
    protected $table = 'product_tags';
}
