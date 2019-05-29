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
        $page = Page::where('home', 1)->first();
        $products = Product::take(8)->get();
        foreach ($products as $product){
            $product->init();
        }
        $prod = Product::where('id', $id)->first();
        if(!Cache::has('gallery')){
            $gallery = Gallery::all();
            Cache::put('gallery', $gallery, 1200);
        }else{
            $gallery = \Illuminate\Support\Facades\Cache::get('gallery');
        }

        $prod->init();
       return view('new_home', compact('products','prod', 'gallery', 'page'));
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
}
