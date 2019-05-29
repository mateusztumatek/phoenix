<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreatorItem extends Model
{
    protected $fillable = ['product_id', 'name', 'project_photo', 'project_mask', 'price', 'active', 'data'];
}
