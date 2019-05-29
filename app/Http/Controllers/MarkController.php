<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Session;


class MarkController extends Controller
{


    public function index(Request $request, $item){
        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
            $cart = Session::get('cart');
        }else {
            $cart = $request->session()->get('cart');
        }

        $key= $item;
        if(!$this->check($request, $item)){
            $product = $cart->getItem($item);

            return response()->json(['errors' => 'Dla tej ilości produktu, nie ma dostępnych technik znakowania ']);
        }


        $product = $cart->getItem($item);
        if($product->mark > 0){
            return back()->with(['message' => 'nie możesz dodać znakowania']);
        }
        $marks = DB::table('product_markgroups')->where('product_id', $product->macma_id)->join('marks', 'product_markgroups.name', 'marks.name')->select('marks.*')->get();
        return view('products.marks_add_modal', compact('product', 'marks', 'key'))->render();
    }

    public function store(Request $request, $item){
        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
        }
        $cart = $request->session()->get('cart');
        $rand = rand(0, 20000000);
        $hash = md5($rand);
        if($request->hasFile('file')){
            $file = $request->file('file');
            $path = 'storage/projects/'.$hash;
            $filename = $request->file('file')->getClientOriginalName();
            $extension = $request->file('file')->getClientOriginalExtension();
            $image = $filename;
            $file->move($path, $filename);
        } else {
            $filename = null;
            $path = null;
        }

        $path_to_file = $path.'/'.$filename;
        $cart->addMark($item, $path_to_file, $request->mark_id);
        return back()->with(['message' => 'projekt dodany poprawnie']);
    }

    public function delete(Request $request, $item){

        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
        }
        $cart = $request->session()->get('cart');
        $id = $item;
        $item = $cart->getItem($item);

        $cart->deleteItemMark($id);
        return back()->with(['message' => 'projekt został usunięty']);
    }

    public function check(Request $request, $item){
        if(!$request->session()->exists('cart')){
            $request->session()->put('cart',new Cart());
        }
        $cart = $request->session()->get('cart');
        $product = $cart->getItem($item);
        foreach ($product->getMarks() as $mark){
            if(intval($product->quanity) >= $mark->min_quantity){
                return true;
            }
        }
        return false;
    }
}
