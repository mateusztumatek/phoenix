<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Payer;

class PaypalController extends Controller
{
    public function __construct()
    {

    }

    public function pay(Request $request, $cart){
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

    }
}
