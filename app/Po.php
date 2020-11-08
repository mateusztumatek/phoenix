<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Po extends Model
{
    protected $fillable = ['name', 'content', 'image', 'type', 'url'];
    protected $table = 'pos';

    public function getContentAttribute($data){
        return str_replace('&oacute;', 'ó', $data);
    }
}
