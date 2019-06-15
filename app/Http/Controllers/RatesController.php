<?php

namespace App\Http\Controllers;

use App\Product;
use App\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RatesController extends Controller
{
    public function store(Request $request, Product $product){

        $validatedData = Validator::make($request->all(), [
            'rate' => 'required',
        ]);
        if($validatedData->fails()) return response()->json(['errors' => ['Musisz wybrać ocenę']]);
        $rate = Rate::create([
            'product_id' => $product->id,
            'rate' => $request->rate,
            'description' => $request->comment,
        ]);

        return response()->json($rate);
    }
    public function productRates($product){
        $product = Product::find($product);
        return response()->json(['rate' => $product->getRate(), 'comments' => Rate::where('product_id', $product->id)->get()]);
    }
}
