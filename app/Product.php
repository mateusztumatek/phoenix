<?php

namespace App;

use App\Services\Help;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;


class Product extends Model
{

    public $profit_price = 0;
    protected $fillable = ['name','content', 'price', 'prices_sellout', 'new', 'size', 'weight', 'profit', 'images', 'active', 'quantity', 'count', 'color'];
    protected $table = 'products';
    public $categories = array();
   public function __construct(array $attributes = array())
    {
        $elem = parent::__construct($attributes);
        $this->profit_price = $this->price + floatval(setting('admin.profit'));

     }

     public function inSubcategory(Subcategory $subcategory){
        if(is_array($this->categories)){
        }
        return false;
     }
     public function getMark(){
       return Mark::where('macma_id', $this->mark)->first();
     }
     public function getMainImage(){
         if(empty($this->getProductImages())){
             return url('/').'default/no_image.png';
         }
         foreach ($this->getProductImages() as $img){
             return $img;
         }

     }

     public function getLink(){
        return url('/produkt/'.$this->id.'/'.\App\Services\Help::slugify($this->name));
     }
     public function getProductImages(){
         $images = json_decode(($this->images), true);
         $array = [];
         if(count($images) == 0){
             array_push($array, url('/').'/default/no_image.png');
             return $array;
         }
         foreach ($images as $image){
             array_push($array,url('/str').'/'.$image);
         }
         return $array;
     }

     public function getProductColours(){
         return DB::table('product_colours')->where('product_CodeShort', $this->CodeShort)->get();
     }
     public function getMaterials(){
         return DB::table('product_materials')->where('product_id', $this->id)->get();
     }

     public function getMarks(){

         return DB::table('product_markgroups')->where('product_id', $this->macma_id)->join('marks', 'product_markgroups.name', 'marks.name')->select('marks.*')->get();

     }
     public function getStock(){
         return DB::table('stock_availability')->where('product_codeShort', $this->codeShort)->first();
     }
     public function hasProject(){
       return($this->mark_price > 0) ? true : false;
     }

     public function getRates(){
        return Rate::where('product_id', $this->macma_id)->get();
     }

     public function deleteProductProject($item){
         $path = explode('/', $item->project_url);
         $folder_path = $path[1];
         $file = $path[2];
         Storage::delete('projects/'.$folder_path.'/'.$file);
         Storage::deleteDirectory('projects/'.$folder_path);
     }
     public function init(){

       if($this->profit != NULL){
           if($this->profit > 0){
               $this->profit_price = $this->price + $this->profit;
           }
       } else {
           $this->profit_price = $this->price + setting('admin.profit');
       }
         ($this->price > $this->prices_sellout && $this->prices_sellout != null && $this->prices_sellout != '')? $this->price = $this->prices_sellout : null;
     }
    public function scopeFilter($q)
    {

        if (request('price_from')!= null && request('price_to') != null){
            $q->where('price', '>=', request('price_from'));
            $q->where('price', '<=', request('price_to'));
        }

        if(request('tags')){
            $q->join('product_tags', 'products.id', 'product_tags.product_id')->where('product_tags.tag', request('tags'));
        }
        if(request('sort_by')){
            if(request('sort_by') == 'seen'){
                $q->orderBy('count', 'desc');
            }
            if(request('sort_by') == 'price_top'){
                $q->orderBy('price', 'desc');
            }
            if(request('sort_by') == 'price_down'){
                $q->orderBy('price', 'asc');
            }
            if(request('sort_by') == 'none'){
                Request::offsetUnset('sort_by');
            }
        }

        $q->select('products.*');
        return $q;
    }

    public function hasMaterial($material){
        $temp = DB::table('product_materials')->where('product_id', $this->id)->where('name', "LIKE","%{$material}%")->first();
        return ($temp)? true : false;
    }

    public function delete(){
       DB::table('product_categories')->where('product_id', $this->id)->delete();
       DB::table('product_materials')->where('product_id', $this->id)->delete();
       DB::table('product_tags')->where('product_id', $this->id)->delete();
       return parent::delete();



    }
}
