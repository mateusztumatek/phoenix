<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
/*$table->string('payment_id')->nullable();
$table->string('payment_method')->nullable();
$table->string('payment_state')->nullable();
$table->dateTime('payment_create_time')->nullable();
$table->dateTime('payment_update_time')->nullable();*/

    protected $fillable = ['user_id', 'name','is_paid', 'street', 'street_number', 'flat_number', 'city', 'email', 'comments', 'images', 'price', 'postal_code', 'hash'];
}
