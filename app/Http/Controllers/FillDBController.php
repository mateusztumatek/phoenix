<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 320);

use App\Category;
use App\Mark;
use App\Product;
use App\Services\Help;
use Illuminate\Support\Facades\File;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;
class FillDBController extends Controller
{
    public function index(){

       $this->FillStockAvailability();
    }

    public function FillProductsTable(){
        $all_products = json_decode(file_get_contents('https://www.macma.pl/data/webapi/pl/json/offer.json'), true);
        unset($all_products[0]);
        /*protected $fillable = ['macma_id', 'name', 'intro', 'content', 'CodeShort', 'CodeFull', 'price', 'prices_sellout', 'color', 'color_code', 'color_hex', 'OriginCountry', 'brand', 'new', 'size', 'weight'];*/

        foreach($all_products as  $key => $product){


             $pr = DB::table('products')->where('CodeShort', $product[4])->first();
                if($pr == null){
                    $pr = Product::create([
                        'macma_id' => $product[0],
                        'name' => $product[1],
                        'intro' => $product[2],
                        'content' => $product[3],
                        'CodeShort' => $product[4],
                        'CodeFull' => $product[5],
                        'price' => floatval($product[6]),
                        'prices_sellout' => $product[7],
                        'color' => $product[11],
                        'color_code' => $product[12],
                        'color_hex' => $product[13],
                        'OriginCountry' => $product[14],
                        'brand' => $product[15],
                        'new' => $product[16],
                        'size' => $product[17],
                        'weight' => floatval($product[18]),
                    ]);
/*                      $this->FillImagesTable($product);*/

                      $this->FillProduct_categoriesTable($product);
                       $this->FillMaterialsTable($product);
                    $this->FillMarkGroupsTable($product);

                }

                $this->FillColoursTable($product);
            /*   $this->FillImagesTable($product);*/
/*            $this->FillCategoriesTable();*/


        }
/*        $this->FillMarksTable();*/
    }


    public function FillStockAvailability(){
        $source = 'https://www.macma.pl/data/webapi/pl/xml/stocks.xml';
        $xmlstr = file_get_contents($source);
        $xmlcont = new SimpleXMLElement($xmlstr);
        foreach($xmlcont as $url){
            if(isset($url->quantity_next_delivery)){
                DB::table('stock_availability')->insert([
                    'product_id' => $url->id,
                    'product_CodeShort' => $url->code_short,
                    'quantity_24h' => $url->quantity_24h,
                    'quantity_37days' => $url->quantity_37days,
                    'quantity_delivery' => $url->quantity_delivery,
                    'quantity_next_delivery' => $url->quantity_next_delivery,
                ]);
            } else {
                DB::table('stock_availability')->insert([
                    'product_id' => $url->id,
                    'product_CodeShort' => $url->code_short,
                    'quantity_24h' => $url->quantity_24h,
                    'quantity_37days' => $url->quantity_37days,
                    'quantity_delivery' => $url->quantity_delivery,
                    'quantity_next_delivery' => 0,
                ]);
            }

        }
    }

    public function FillCategoriesTable(){
        $categories = json_decode(file_get_contents('https://www.macma.pl/data/webapi/pl/json/categories.json'), true);
        unset($categories[0]);
        /*protected $fillable = ['macma_id', 'parent_id', 'name', 'intro', 'content'];*/
        foreach ($categories as $category) {

            $term = $this->slugify($category[1]);
            Category::create([
                'macma_id' => $category[0],
                'name' => $category[1],
                'intro' => $category[2],
                'content' => $category[3],
                'parent_id' => null,
                'search' => $term,
            ]);
            if(!empty($category[4])){
                foreach ($category[4] as $subcategory){
                    $term = $this->slugify($subcategory[1]);
                    Category::create([
                       'macma_id' => $subcategory[0],
                       'name' => $subcategory[1],
                       'intro' => $subcategory[2],
                       'content' => $subcategory[3],
                       'parent_id' => $category[0],
                        'search' => $term,
                    ]);
                }
            }
        }
    }

    public function FillMatTable(){
        $materials = json_decode(file_get_contents('https://www.macma.pl/data/webapi/pl/json/materials.json'), true);
        unset($materials[0]);
        /*protected $fillable = ['macma_id', 'parent_id', 'name', 'intro', 'content'];*/
        foreach ($materials as $material) {
            DB::table('materials')->insert([
               'name'=>$material[1],
            ]);
        }
    }

    public function FillBrandsTable(){
        $materials = json_decode(file_get_contents('https://www.macma.pl/data/webapi/pl/json/brands.json'), true);
        unset($materials[0]);
        /*protected $fillable = ['macma_id', 'parent_id', 'name', 'intro', 'content'];*/
        foreach ($materials as $material) {
            DB::table('brands')->insert([
                'name'=>$material[1],
            ]);
        }
    }
    public function FillMarksTable(){
        $marks = json_decode(file_get_contents('https://www.macma.pl/data/webapi/pl/json/markgroups.json'), true);
        /*protected $fillable = ['macma_id', 'parent_id', 'name', 'intro', 'content'];*/
        foreach ($marks as $key => $mark) {
            if ($key > 0){
                $q = 0;
                $p=0;
                foreach ($mark[5] as $key => $price){
                    if(Help::priceToFloat($price) > 0) {
                        $p = Help::priceToFloat($price);
                        $q = (int) filter_var($marks[0][5][$key], FILTER_SANITIZE_NUMBER_INT);
                        break;
                    }
                }
                Mark::create([
                    'macma_id' => $mark[0],
                    'name' => $mark[1],
                    'colorsMin' => $mark[3],
                    'colorsMax' => $mark[4],
                    'min_quantity' => $q,
                    'price_max' => number_format($p, 2),
                ]);
            }
        }
    }
    public function FillColoursTable($product){
        DB::table('product_colours')->insert([
            'product_id' => $product[0],
            'product_CodeShort' => $product[4],
            'colour' => $product[11],
            'colourHex' => $product[13],
        ]);
    }
    public function FillMarkGroupsTable($product){
        foreach ($product[29] as $mark){

            DB::table('product_markgroups')->insert([
                'product_id' => $product[0],
                'product_codeShort' => $product[4],
                'name' => substr($mark, 0, strpos($mark, ")") +1),
            ]);
        }

    }
    public function FillMaterialsTable($product){
        foreach ($product[30] as $material){
            DB::table('product_materials')->insert([
                'product_id' => $product[0],
                'product_codeShort' => $product[4],
                'name' => $material,
            ]);
        }
    }


    public  function FillImagesTable($product){
            foreach ($product[25] as $key => $image){
                if($product[16] != 1){
                    $url=$image;
                    if($key > 1){
                        break;
                    }
                    if(substr(get_headers($url)[0], 9, 3) != "200"){
                        echo "error";
                    }else{
                        $contents=file_get_contents($url);
                        if(!file_exists(public_path()."/products/".$product[0])){
                            File::makeDirectory(public_path()."/products/".$product[0], $mode = 0777, true, true);
                        }
                        $save_path=public_path()."/products/".$product[0]."/".hash('md5', $product[0].$key).".jpg";
                        file_put_contents($save_path,$contents);

                        DB::table('images')->insert([
                            'product_id' => $product[0],
                            'product_CodeShort' => $product[4],
                            'url' => hash('md5', $product[0].$key),
                            'colour_name' => $product[11],
                        ]);
                    }
                }
            }
    }

    public function FillProduct_categoriesTable($product){
        foreach ($product[27] as $key => $product_category){
            $category = Category::where('name', key($product_category))->first();

                DB::table('product_categories')->insert([
                    'product_id' => $product[0],
                    'category_id' => $category->macma_id,
                ]);



                foreach ($product_category as $sub){

                    if(!empty($sub)){
                        foreach ($sub as $cat){
                            $subcategory = Category::where('name', $cat)->first();

                                DB::table('product_categories')->insert([
                                    'product_id' => $product[0],
                                    'category_id' => $subcategory->macma_id,
                                ]);




                        }




                    }

                }



        }

    }

    public function RemoveOldProducts(){

        $source = 'https://www.macma.pl/data/webapi/pl/xml/offer.xml';
        $xmlstr = file_get_contents($source);
        $xmlcont = new SimpleXMLElement($xmlstr);


        $my_products = Product::orderBy('macma_id')->get();


        foreach ($my_products as $product){

            $check = false;
            foreach ($xmlcont as $i){
                if($i->baseinfo->id == $product->macma_id){
                    $check = true;
                }
            }

            if(!$check){
                $product->delete();
            }

        }
    }

    function slugify($text) {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}
