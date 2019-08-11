<?php

namespace App\Http\Controllers;

use App\Collection;
use App\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CollectionController extends Controller
{

    public $per_page, $page=1, $offset;
    public function __construct()
    {
        $this->page = Input::get('page', 1);
        $this->per_page = 15;
        $this->offset = ($this->page * $this->per_page) - $this->per_page;

    }
    public function index(Request $request){
        /*if($collections = Input::get('collections')){
            $col = $collections;
            $col= Collection::where(function ($query) use ($collections){
                foreach ($collections as $collection){
                    $query->orWhere('name', $collection);
                }
            })->where('display_on_home', 0)->get();
        }*/
        $tags = [];
      /*  if(isset($col)){
            $products = Product::join('collection_items', 'products.id', 'collection_items.product_id')->where('products.active', 1)->where(function ($query) use ($col){
                foreach ($col as $collection){
                    $query->orWhere('collection_items.collection_id', $collection->id);
                }
            })->select('products.*')->filter()->get();
        } else {


        }*/
        $products = Product::join('collection_items', 'products.id', 'collection_items.product_id')
            ->where('products.active', 1)
            ->join('collections', 'collection_items.collection_id', 'collections.id')
            ->where('collections.name', '!=', 'Home')
            ->where('collections.display_on_home', 0)
            ->select('products.*')->get();


        foreach ($products as $pr){
            $pr->init();
            $pr->setAttribute('collections', $pr->getCollections());
        }
      /*  if (request('materials')){
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
        }*/
        /*foreach ($products as $product){
            $product->init();
        }*/
        /*$count = count($products);*/

       /* $array = [];
        foreach ($products as $product) {
            array_push($array, $product);
        }
        $products = new LengthAwarePaginator(array_slice($array, $this->offset, $this->per_page, true), count($products), $this->per_page, $this->page, ['path' => $request->url(), 'query' => $request->query()]);*/
        $collections = Collection::where('name', '!=', 'home')->where('display_on_home', 0)->where('name', '!=', 'navbar')->get();
        if($request->ajax()){
            return view('products.product_grid', compact('products'))->render();
        }
        return view('collections.index', compact('collections', 'products'));
    }
}
