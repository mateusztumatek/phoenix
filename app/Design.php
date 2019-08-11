<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $fillable = ['material', 'length', 'previewImage', 'selectedItem', 'design'];
    public function getPrice(){
        return json_decode($this->selectedItem)->price;
    }
}
