<?php

namespace App;

use App\Services\Help;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;


class Product extends Model
{

    public $profit_price = 0;
    protected $fillable = ['name','content', 'price', 'prices_sellout', 'new', 'size', 'weight', 'profit', 'images', 'active', 'quantity', 'count', 'color', 'availability'];
    public $appends = ['calculated_price', 'is_sellout', 'slug', 'imgs'];
    protected $table = 'products';
    public $categories = array();
   public function __construct(array $attributes = array())
    {
        $elem = parent::__construct($attributes);
        $this->profit_price = $this->price + floatval(setting('admin.profit'));

     }
     public function getSlugAttribute(){
       return \App\Services\Help::slugify($this->name);
     }
    public function getCalculatedPriceAttribute(){
       if($this->prices_sellout && $this->price > $this->prices_sellout) return $this->prices_sellout;
       return $this->price;
    }
    public function getImgsAttribute(){
        $images = json_decode(($this->images), true);
        $array = [];
        if(count($images) == 0){
            array_push($images, '/default/no_image.png');
        }
       return $images;
    }
    public function getIsSelloutAttribute(){
        return $this->prices_sellout && $this->price > $this->prices_sellout;
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
     public function getCollections(){
       return Collection::join('collection_items', 'collections.id', 'collection_items.collection_id')->where('product_id', $this->id)->select('collections.*')->get();
     }
     public function getProductColours(){
         return DB::table('product_colours')->where('product_CodeShort', $this->CodeShort)->get();
     }
     public function getTags(){
       return DB::table('product_tags')->where('product_id', $this->id)->get();
     }
     public function getMaterials(){
         return DB::table('product_materials')->where('product_id', $this->id)->get();
     }
     public function materials(){
       return $this->hasMany('App\Relations\ProductMaterial', 'product_id');
     }
    public function tags(){
        return $this->hasMany('App\Relations\ProductTag', 'product_id');
    }
    public function categories(){
       return $this->belongsToMany('App\Category', 'product_categories');
    }
    public function collections(){
       return $this->belongsToMany('App\Collection', 'collection_items');
    }
     public function getStock(){
         return DB::table('stock_availability')->where('product_codeShort', $this->codeShort)->first();
     }
    public function rates(){
       return $this->hasMany('App\Rate', 'product_id');
    }
     public function getRate(){
       $rates= Rate::where('product_id', $this->id)->get();
       if(count($rates) == 0) return 5;
       $temp = 0;
       foreach ($rates as $rate){
          $temp = $temp + $rate->rate;
       }
       $rate = $temp / count($rates);
        return $rate;
     }

     public function deleteProductProject($item){
         $path = explode('/', $item->project_url);
         $folder_path = $path[1];
         $file = $path[2];
         Storage::delete('projects/'.$folder_path.'/'.$file);
         Storage::deleteDirectory('projects/'.$folder_path);
     }
     public function init(){
       $this->setAttribute('materials', $this->getMaterials());
       $this->setAttribute('tags', $this->getTags());
       $this->setAttribute('link', $this->id.'/'.Help::slugify($this->name));
       /*if($this->profit != NULL){
           if($this->profit > 0){
               $this->profit_price = $this->price + $this->profit;
           }
       } else {
           $this->profit_price = $this->price + setting('admin.profit');
       }
         ($this->price > $this->prices_sellout && $this->prices_sellout != null && $this->prices_sellout != '')? $this->price = $this->prices_sellout : null;*/
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
