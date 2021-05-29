<?php

namespace Mateusztumatek\Monitor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MonitorController extends Controller{
    public function index(Request $request){
        dd($request->all());
    }
}