<?php

namespace App\Http\Middleware;

use App\Cart;
use Closure;
use Illuminate\Support\Facades\Session;

class CartMiddleware
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
/*        Session::forget('cart');*/
        if(!Session::has('cart')) Session::put('cart', new Cart());
        return $next($request);
    }
}
