<?php

namespace App\Http\Controllers;

use App\CreatorItem;
use Illuminate\Http\Request;

class CreatorItemController extends Controller
{
    public function index(){
        return response()->json(CreatorItem::all());
    }
    public function store(Request $request){
        $photo = explode('/', $request->photo);
        $photo_name = $photo[4].'/'.$photo[5];
        $mask = explode('/', $request->mask);
        $mask_name = $mask[4].'/'.$mask[5];
        $item = CreatorItem::create([
            'project_photo' => $photo_name,
            'project_mask' => $mask_name,
            'name' => $request->name,
            'price' => $request->price,
            'active' => true,
            'data' => json_encode($request->data)
        ]);
        return response()->json($item);
    }

    public function update(Request $request, $id){
        $photo = explode('/', $request->photo);
        $photo_name = $photo[4].'/'.$photo[5];
        $mask = explode('/', $request->mask);
        $mask_name = $mask[4].'/'.$mask[5];
        $item = CreatorItem::find($id);
        if($request->name) $item->name = $request->name;
        if($request->price) $item->price = $request->price;
        if($request->photo) $item->project_photo = $photo_name;
        if($request->mask) $item->project_mask= $mask_name;
        if($request->data){
            $item->data= json_encode($request->data);
        }
        $item->update();
        return response()->json($item);
    }

    public function delete(Request $request){
        $item = CreatorItem::where('id', $request->id)->first();
        if(file_exists(public_path('/creator_items/'.$item->project_photo))){
            unlink(public_path('/creator_items/'.$item->project_photo));
        }
        if(file_exists(public_path('/creator_items/'.$item->project_mask))) {
            unlink(public_path('/creator_items/'.$item->project_mask));

        }
        $item->delete();
        return response()->json(true);
    }
}
