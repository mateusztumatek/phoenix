<?php

namespace App\Http\Controllers;

use App\Design;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->file){
            $date = Carbon::now();
            $image = $request->file;
            $filename = md5(Str::random(60)).'.jpg';
            $path = 'str/designs/'.$date->format('F').$date->format('Y').'/';
            if(!file_exists(public_path($path))) mkdir(public_path($path));
            $image->move(public_path($path), $filename);
            $to_save = 'designs/'.$date->format('F').$date->format('Y').'/'.$filename;
        }else{
            return response()->json(['message' => 'Brak obrazka']);
        }
        $design = Design::create([
            'material' => $request->material,
            'length' => $request->length,
            'previewImage' => $to_save,
            'selectedItem' => $request->sItem,
            'design' => $request->design,
        ]);

        return response()->json($design);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Design  $design
     * @return \Illuminate\Http\Response
     */
    public function show(Design $design)
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
        return view('project.index', compact('project', 'files', 'design'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Design  $design
     * @return \Illuminate\Http\Response
     */
    public function edit(Design $design)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Design  $design
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Design $design)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Design  $design
     * @return \Illuminate\Http\Response
     */
    public function destroy(Design $design)
    {
        //
    }
}
