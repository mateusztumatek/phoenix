<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class productsControlle extends Controller
{
    public function sorteByCategory(Category $category){
        $users = DB::table('product_categories')->where('category_id', $category->macma_id)
            ->join('products', 'product_id', 'products.macma_id')->get();
        var_dump($users[0]);
        die;
    }
}
