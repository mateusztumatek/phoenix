<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Category extends Model
{
    protected $table = 'pro_categories';
    protected $fillable = ['parent_id', 'name', 'intro', 'content', 'search'];

    public $subcategories = array();
        /*$table->increments('id');
        $table->integer('macma_id')->unique();
        $table->integer('parent_id')->nullable();
        $table->string('name');
        $table->string('intro')->nullable();
        $table->string('content')->nullable();*/


    /*public function hasSubmenu(){
        var_dump($this);
        die;
        if($this[4] == null) return false;
        else return true;
    }*/
    public function isParent(){

        return ($this->parent_id == 0)? true : false;
    }
    public function getParent(){
        return Category::where('macma_id', $this->parent_id)->first();
    }
    public function hasSubmenu(){
        return (empty(Category::where('parent_id', $this->id)->first()))? false : true;
    }

    public function getSubmenu(){
        return Category::where('parent_id', $this->id)->get();
    }

    public function hasProducts(){
        $products = DB::table('product_categories')->where('category_id', $this->macma_id)->get();
        return ($products->isEmpty())? false : true;
    }

    public function hasParent(Category $category){
        if($this->parent_id == $category->id){
            return true;
        } else return false;

    }
    public function delete()
    {
        DB::table('product_categories')->where('category_id', $this->id)->delete();
        return parent::delete();
    }
}
