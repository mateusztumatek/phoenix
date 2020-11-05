<?php

namespace App\Http\Controllers;

use App\Category;
use App\Gallery;
use App\Mail\ContactMail;
use App\Page;
use App\Po;
use App\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpKernel\Client;
use Illuminate\Support\Facades\Mail;
use Vinkla\Instagram;

class HomeController extends Controller
{

    public $per_page, $page=1, $offset;
    public function __construct(Request $request)
    {
        $this->page = Input::get('page', 1);
        $this->per_page = 15;
        $this->offset = ($this->page * $this->per_page) - $this->per_page;
        if(request('price_to') != null && request('price_from') == null){
            unset($request['price_to']);
        }
    }

    public function index(Request $request)
    {
        $array = [];
        $tags = DB::table('product_tags')->limit(20)->get();
        $tags = $tags->unique('tag');
        $products = Product::where('active', 1)->orderBy('created_at', 'desc')->filter()->get();
       /* if (request('materials')){
            foreach ($products as $key => $product) {
                $check = false;
                foreach (request('materials') as $material){
                    if($product->hasMaterial($material)){
                        $check = true;
                    }
                }
                if(!$check) unset($products[$key]);
            }
        }*/
        foreach ($products as $product){
            $product->init();
        }

        /*foreach ($products as $product) {
            array_push($array, $product);
        }
        $count = count($products);

        $products = new LengthAwarePaginator(array_slice($array, $this->offset, $this->per_page, true), count($products), $this->per_page, $this->page, ['path' => $request->url(), 'query' => $request->query()]);*/
        if($request->ajax()){
            return view('products.product_grid', compact('products'))->render();
        }

        return view('home', compact('products', 'tags'));

    }

    public function showCategory(Request $request, $category, $sub_category = null){
        if($sub_category){
            $category = Category::where('search', $sub_category)->first();
        } else {
            $category = Category::where('search', $category)->first();
        }
        $tags = DB::table('product_tags')->join('product_categories', 'product_tags.product_id', 'product_categories.product_id')->where('category_id', $category->id)->select('product_tags.*')->limit(20)->get();
        $tags = $tags->unique('tag');
        $products = Product::join('product_categories', 'products.id', 'product_categories.product_id')->where('category_id', $category->id)->where('products.active', 1)->orderBy('created_at', 'desc')->select('products.*')->filter()->get();
        foreach ($products as $product){
            $product->init();
        }
        if($request->ajax()){
            return view('products.product_grid', compact('products'))->render();
        }

        return view('home', compact('products', 'category', 'tags'));
    }


    public function home(Request $request){

        $page = Page::where('home', 1)->first();
        $products = Product::join('collection_items', 'products.id', 'collection_items.product_id')->join('collections', 'collection_items.collection_id', 'collections.id')->where('collections.name', 'Home')->where('products.active', 1)->select('products.*')->get();
        foreach ($products as $product){
            $product->init();
        }
        $gallery = Cache::get('gallery');
        if(!$gallery){
           $gallery = Gallery::with('prod')->take(10)->get();
           Cache::put('gallery', $gallery, 1200);
        }
        $services = Po::where('type', 'appearance_services')->get();
        $data['services'] = $services;
        if($request->header('ajax')){
            $carousel = \App\Po::orderBy('created_at', 'desc')->where('type', 'appearance_carousel')->get();

            return response()->json(['products' => $products, 'page' => $page, 'gallery' => $gallery, 'services' => $services, 'carousel' => $carousel]);

        }

        return view('new_home', compact('products', 'page', 'gallery'));
    }
    public function search(Request $request){
        $term = Input::get('search');
        if(!$term) $term = Input::get('term');
        if(!$term){
            return redirect('/produkty');
        }
        $tags = DB::table('product_tags')->limit(20)->get();
        $tags = $tags->unique('tag');
        if($request->ajax()){
            $products = Product::where("name","LIKE","%{$term}%")->orWhere("intro","LIKE","%{$term}%")->orWhere("content","LIKE","%{$term}%")->where('active', 1)->get();
            $array = [];
            foreach ($products as $product){
                array_push($array, ['label' => $product->name, 'img' => $product->getMainImage(), 'price' => $product->price, 'id' => $product->id, 'macma_id' => $product->macma_id]);
            }
            return response()->json($array);
        } else {
            $products = Product::where("name","LIKE","%{$term}%")->orWhere("intro","LIKE","%{$term}%")->orWhere("content","LIKE","%{$term}%")->where('active', 1)->filter()->get();
            if (request('materials')){
                foreach ($products as $key => $product) {
                    $check = true;
                    foreach (request('materials') as $material){
                        if($product->hasMaterial($material)){
                        }else {
                            $check = false;
                        }
                    }
                    if(!$check) unset($products[$key]);
                }
            }
            foreach ($products as $product){
                $product->init();
            }

            $count = count($products);
            return view('home', compact('products', 'count', 'tags'));

        }


    }

    public function add_to_favourite(Request $request){
        $arr = collect();
        if(!$request->session()->has('saved')){
            $arr->push(Product::findOrFail($request->product_id));
            $request->session()->put('saved', $arr);
        }else{
            $arr = $request->session()->get('saved');
            if($arr->contains('id', $request->product_id)){
               return view('saved');
            }
            $arr->push(Product::findOrFail($request->product_id));
            $request->session()->put('saved', $arr);
        }
        return view('saved');
    }

    public function remove_favourites(Request $request){
        $arr = $request->session()->get('saved');
        $key = $arr->search(function($item) use($request){
            return $item->id == $request->product_id;
        });
        $arr->forget($key);
        if(count($arr) == 0) return null;
        return view('saved');
    }
    public function gallery(Request $request){
        $gallery = Gallery::with('prod')->where(function ($q)use($request){
            if($request->product_id) $q->where('product_id', $request->product_id);
        })->get();
        if($request->header('ajax')){
            return response()->json($gallery);
        }
        return view('gallery', compact('gallery'));
    }
    public function sendEmail(Request $request){

        Mail::to('mbielak@ideashirt.pl')->send(new ContactMail($request->email, $request->name, $request->phone, $request->message));
        return back()->with(['message' => 'wiadomosc została wysłana poprawnie']);
    }

}
