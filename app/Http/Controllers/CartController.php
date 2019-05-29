<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Mark;
use App\Product;
use Illuminate\Http\Request;
use Session;


class CartController extends Controller
{

    public function show(Request $request){
/*        $request->session()->forget('cart');*/
        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
            $cart = Session::get('cart');
        }else {
            $cart = $request->session()->get('cart');
        }
        return view('Cart.index', compact('cart'));



    }
    public function addItem(Request $request){
        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
        }

        $cart = $request->session()->get('cart');
        $product = Product::where('macma_id', $request->product_id)->first();
        $product->init();
        $cart->addItem($product, $request->quanity, $request->colour);
        return response()->json($cart);
    }

    public function deleteItem(Request $request){
        $old_cart = Session::has('cart') ? Session::get('cart') : null;
        $old_cart->deleteItem($request->item_id);
        return response()->json($old_cart);
    }
    public function refresh(Request $request){
        $old_cart = Session::has('cart') ? Session::get('cart') : null;
        foreach ($old_cart->items as $key => $item){

            if(isset($request[$key])){
                if($request[$key] <= 0){
                   unset($old_cart->items[$key]);
                } else{

                    $i = $old_cart->items[$key];
                    if($i->mark_price > 0){
                        $mark = $i->getMark();
                        if($mark->min_quantity > $request[$key]) {
                            return back()->with(['message' => 'żeby zmniejszyć ilość produktów musisz usunąć znakowanie']);
                        }
                    }

                    $item->quanity = $request[$key];
                }

            }
        }


        $old_cart->refresh();
        $cart = $old_cart;
        return back()->with('Cart.index', compact('cart'));
    }

    public function changeColour(Request $request, $item){
        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
        }

        $cart = $request->session()->get('cart');
        $i = $cart->getItem($item);
        $i->colour = $request->colour_hex;
        $i->color = $request->colour;
        $i->color_hex = $request->colour_hex;

        $cart->items[$item] = $i;


        return back()->with(['message' => 'kolor zostal zmieniony']);
    }
}
