<?php

namespace App\Http\Controllers;

use App\Category;
use App\Collection;
use App\Material;
use App\Order;
use App\Po;
use App\Product;
use function Clue\StreamFilter\fun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function order(Request $request, $hash)
    {
        $order = Order::where('hash', $hash)->first();
        if (!$order) return response()->json(['message' => 'Nie ma takiego zamówienia'], 404);
        return response()->json($order);
    }

    public function info()
    {
        $data = [];
        $logo = setting('site.logo');
        $data['logo'] = $logo;
        $categories_menu = collect();
        $categories = Category::all();
        foreach ($categories as $c){
            $c->setAttribute('path', '/kategoria/'.$c->search);
        }
        $menu = menu('top_menu', '_json');
        foreach ($menu as $item) {
            if ($item->title == 'Produkty') {
                $item->setAttribute('url', '/produkty');
                $item->childrens = collect();
                $item->childrens = $item->childrens->merge($categories);
                $item->childrens->prepend((object)['name' => 'Wszystkie produkty', 'path' => '/produkty']);
            }
            if($item->title == 'Kolekcje'){
                $item->childrens = collect();
                $collections = Collection::where('display_on_home', false)->get();
                foreach ($collections as $c){
                    $c->setAttribute('path', '/kolekcje/'.$c->id);
                }
                $item->childrens = $item->childrens->merge($collections);
            }
        }
        $data['menu'] = $menu;
        return response()->json($data);
    }

    public function products(Request $request){
        $products = Product::where('active', 1)->with('categories');
        if($request->category){
            $category = Category::where('search', $request->category)->first();
        }else{
            $category = null;
        }
        if($request->collection){
            $collection = Collection::find($request->collection);
        }else{
            $collection = null;
        }
        if($collection){
            $products = $products->whereHas('collections', function($q)use($collection){
               $q->where('collection_id', $collection->id);
            });
        }
        if($category){
            $products = $products->whereHas('categories', function($q)use($category){
                $q->where('category_id', $category->id);
            });
        }
        if($request->materials && is_array($request->materials) && count($request->materials) > 0){
            $products = $products->whereHas('materials', function($q)use($request){
                foreach ($request->materials as $key => $m){
                    if($key == 0) $q->where('name', $m);
                    else $q->orWhere('name', $m);
                }
            });
        }
        if($request->tags && is_array($request->tags) && count($request->tags) > 0){
            $products = $products->whereHas('tags', function($q)use($request){
                foreach ($request->tags as $key => $t){
                    if($key == 0) $q->where('tag', $t);
                    else $q->orWhere('tag', $t);
                }
            });
        }
        if($request->available){
            $products = $products->where('availability', 1)->orWhere('availability', 2);
        }
        if($request->sellout){
            $products = $products->where('prices_sellout', '!=', null);
        }
        if($request->prices && is_array($request->prices) && count($request->prices) == 2){
            $products = $products->where('price', '>=', $request->prices[0])->where('price', '<=', $request->prices[1]);
        }
        if($request->search){
            $products = $products->where('name', 'LIKE', '%'.$request->search.'%')->orWhere('content', 'LIKE', '%'.$request->search.'%');
        }
        if($request->sort_attr && $request->sort_type){
            $products = $products->orderBy($request->sort_attr, $request->sort_type);
        }
        $products = $products->paginate(($request->limi)? $request->limit : 20);
        return response()->json($products);
    }
    public function product(Request $request, $id){
        $product = Product::find($id);
        $product->count = $product->count + 1;
        $product->update();
        return response()->json($product->load('rates', 'tags', 'materials'));
    }
    public function getFilters(){
        $data['materials']= Material::all();
        $data['tags'] = DB::table('product_tags')->get();
        $data['tags'] = $data['tags']->unique('tag')->values();
        return response()->json($data);
    }
}