<?php
/**
 * Created by PhpStorm.
 * User: Mateusz
 * Date: 25.10.2018
 * Time: 20:41
 */
namespace App\Services;
use App\Category;
use http\Env\Request;

class Help
{
    public static function getCategories(){
        $i = 0;

        $categories_query = json_decode(file_get_contents('https://www.macma.pl/data/webapi/pl/json/categories.json', true));
        \Illuminate\Support\Facades\Cache::put('categories', $categories_query, 10);
        unset($categories_query[0]);
        foreach ($categories_query as $category){
            $categories[$i] = new Category($category);
            $i++;
        }
        return $categories;
    }
    public static function hasInputs(){
        $inputs = \Illuminate\Support\Facades\Request::all();
        if($inputs){
            if(count($inputs) == 3){
                if(\Illuminate\Support\Facades\Request::has('tags') && \Illuminate\Support\Facades\Request::get('price_to') == null){
                    if(\Illuminate\Support\Facades\Request::get('tags') == null){
                        return false;
                    }
                }
            }
            if(count($inputs) == 2){
                if(\request()->has('price_from') && \request()->has('price_to')){
                    if(\request()->get('price_from') == null && \request()->get('price_to') == null) return false;
                }
            }
            if(count($inputs) == 1){
                if(\request()->has('tags')){
                    if(\request()->get('tags') == null) return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
    public static function checkRemoteFile($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        if($result !== FALSE)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function priceToFloat($s)
    {
        // convert "," to "."
        $s = str_replace(',', '.', $s);

        // remove everything except numbers and dot "."
        $s = preg_replace("/[^0-9\.]/", "", $s);

        // remove all seperators from first part and keep the end
        $s = str_replace('.', '',substr($s, 0, -3)) . substr($s, -3);

        // return float
        return (float) $s;
    }

    public static function slugify($text) {
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