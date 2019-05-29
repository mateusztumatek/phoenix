<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    public $id, $parent_id, $name;
    public function __construct($data, $parent_id)
    {

        $this->id = $data[0];
        $this->name = $data[1];
        $this->parent_id  = $parent_id;

    }

}
