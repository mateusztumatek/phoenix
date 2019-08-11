<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Vinkla\Instagram\Instagram;
use App\Gallery;
use App\Product;
use App\Page;

class ProductController extends Controller
{
    public function index($id, $slug){
        $product = Product::where('id', $id)->first();
        $product->count = $product->count + 1;
        $product->update();
        $product->init();
        $cat = DB::table('product_categories')->inRandomOrder()->where('product_id', $product->id)->first();

        $matched = DB::table('product_categories')->where('product_id', '!=', $product->id)->where('category_id', $cat->category_id)->take(10)->get();
        $featured_products = collect();
        foreach ($matched as $match){
            $prod = Product::find($match->product_id);
            $prod->init();
            $featured_products->push($prod);
        }

       return view('products.index', compact('product', 'featured_products'));
    }
    public function show($id, $slug){
        $prod = Product::where('id', $id)->first();
        $prod->count = $prod->count + 1;
        $prod->update();
        $prod->init();
        return response()->view('products.quick_view', compact('prod'));
    }
    public function getColours(Request $request, $product){
        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
        }
        $cart = $request->session()->get('cart');

        $key= $product;
        $product = $cart->getItem($key);

        $colours = $product->getProductColours();
        return view('Cart.colours_modal', compact('product', 'colours', 'key'));
    }
    public function materials(){
        return response()->json(DB::table('materials')->get());
    }
}
