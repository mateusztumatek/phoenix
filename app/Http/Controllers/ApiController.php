<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function order(Request $request, $hash){
        $order= Order::where('hash', $hash)->first();
        if(!$order) return response()->json(['message' => 'Nie ma takiego zamówienia'], 404);
        return response()->json($order);
    }
}
