<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class VerifyPaymentID
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Session::has('payment_id')){
            $key = Session::get('payment_id');
            if(Input::get('order_id') == $key){
                return $next($request);
            }
        }
        return redirect()->route('home')->with(['message' => 'Nie możesz podejrzeć tego zamówienia']);
    }
}
