<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request, $page){
        $page = Page::where('url', $page)->first();
        if($request->header('ajax')){
            return response()->json($page);
        }
        return view('page', compact('page'));
    }
}
