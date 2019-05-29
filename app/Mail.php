<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $fillable = ['mail', 'content', 'title'];
    protected $table = 'Mails';
}
