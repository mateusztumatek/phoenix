<?php

namespace App\Relations;

use App\Services\Help;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;


class ProductMaterial extends Model
{
  protected $fillable = ['product_id', 'name'];
  protected $table = 'product_materials';
}
