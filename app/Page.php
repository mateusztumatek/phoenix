<?php

namespace App;

use http\Env\Request;
use Illuminate\Database\Eloquent\Model;


class Page extends Model
{
    protected $fillable = ['name', 'description', 'content', 'url', 'top_menu_active', 'bottom_menu_active'];
    protected $table = 'pages';
}
