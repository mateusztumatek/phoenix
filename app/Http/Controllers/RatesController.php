<?php

namespace App\Http\Controllers;

use App\Product;
use App\Rate;
use Illuminate\Http\Request;

class RatesController extends Controller
{
    public function store(Request $request, Product $product){

        $validatedData = $this->validate($request, [
            'rating' => 'required',
        ]);

        Rate::create([
            'product_id' => $product->macma_id,
            'author' => $request->name,
            'rate' => $request->rating,
            'description' => $request->comment,
        ]);

        return back()->with(['message' => 'Ocena została dodana']);


    }
}
