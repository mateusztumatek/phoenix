<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Design;
use App\Mark;
use App\Product;
use Illuminate\Http\Request;
use Session;


class CartController extends Controller
{

    public function index(){
        $cart = \Illuminate\Support\Facades\Session::get('cart');
        return response()->json($cart);
    }
    public function addItem(Request $request){
        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
        }
        $cart = $request->session()->get('cart');
        if($request->type == 'product'){
            $product = Product::where('id', $request->product_id)->first();
            foreach ($cart->items as $item) if($item->id == $product->id) return response()->json(false);
            $product->init();
            $cart->addItem($product, $request->quantity, $request->length, 'product');
        }elseif($request->type == 'design'){
            $design = Design::where('id', $request->design['id'])->first();
            $cart->addDesign($design, 1, 'design');
        }

        return response()->json($cart);
    }
    public function update(Request $request, $id){
        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
        }
        $cart = $request->session()->get('cart');

        $cart->items[$id]->quantity = $request->item['quantity'];
        $cart->items[$id]->length = $request->item['length'];

        $cart->refresh();
        return response()->json($cart);
    }
    public function deleteItem(Request $request, $id){
        $old_cart = Session::has('cart') ? Session::get('cart') : null;
        $old_cart->deleteItem($id);
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

}
