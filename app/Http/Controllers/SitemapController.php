<?php

namespace App\Http\Controllers;

use App\Category;
use App\Page;
use App\Product;
use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index(){
        $categories = Category::all();
        $products = Product::all();
        $pages = Page::where('home', 0)->where('url', '!=', null)->get();
        return response()->view('Sitemap.sitemap', [
            'products' => $products,
            'pages' => $pages,
            'categories' => $categories,
        ])->header('Content-Type', 'text/xml');
    }
}
