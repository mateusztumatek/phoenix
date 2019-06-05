<?php

namespace App\Http\Controllers;

use App\CreatorItem;
use App\Product;
use Composer\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DrawController extends Controller
{

    public function __construct()
    {
        if (!file_exists(public_path('/temp'))) mkdir(public_path('/temp'));
    }

    public function index(Request $request)
    {
        $hash = md5(str_random(20));
        $files = array();
        if (!Session::has('project')) {

            $project = array();
            if (!file_exists(public_path('/temp/' . $hash))) mkdir(public_path('/temp/' . $hash));
            $project['hash'] = $hash;

            $project['data_url'] = url('/temp/' . $hash);
            Session::put('project', $project, 120000);

        } else {
            $project = Session::get('project');
            if (file_exists(public_path('/temp/' . $project['hash']))) {
                foreach (scandir(public_path('/temp/' . $project['hash'])) as $key => $file) {
                    if ($key >= 2) {
                        array_push($files, url('/temp/' . $project['hash'] . '/' . $file));
                    }
                }
            }
        }
        return view('project.index', compact('project', 'files'));
    }

    public function removeImage(Request $request)
    {
        $ar = parse_url($request->src);
        unlink(public_path($ar['path']));
        return response()->json(true);
    }

    public function uploadImage(Request $request)
    {
        if ($request->creator_item) {
            if (!file_exists(public_path('/creator_items'))) mkdir(public_path('/creator_items'));

            if ($request->file != null) {
                $image = $request->file('file');
                $width = getimagesize($request->file('file'))[0];
                $height = getimagesize($request->file('file'))[1];
                if ($width != $height) return response()->json(['errors' => 'Zdjęcie musi być kwadratowe']);
                $name = time() . '.' . $image->getClientOriginalExtension();
                $h = md5(str_random(20));
                $destinationPath = public_path('/creator_items/' . $h);
                $image->move($destinationPath, $name);
                return response()->json(['image' => url('/creator_items/' . $h . '/' . $name)]);
            } else {
                return response()->json(false, 404);
            }
        }
        $project = Session::get('project');
        request()->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($request->file != null) {
            $image = $request->file('file');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/temp/' . $project['hash']);
            $image->move($destinationPath, $name);
        }
        return response()->json(['image' => $project['data_url'] . '/' . $name]);
    }

    public function adminIndex(Request $request)
    {
        $products = Product::all();
        $items = CreatorItem::all();
        return view('project.admin', compact('products', 'items'));

    }
}
