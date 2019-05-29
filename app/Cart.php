<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cart
{
    public $totalPrice, $itemsCount=0, $items = array();
    public function __construct()
    {

    }

    public function addItem($item, $quanity, $colour){
        $item->quanity = $quanity;
        $item->colour = $colour;
        $this->totalPrice = $this->totalPrice + ($item->profit_price * $quanity);
        $this->itemsCount = $this->itemsCount + $quanity;
        array_push($this->items, $item);
    }

    public function deleteItem($index){
        $item = $this->items[$index];
        if($item->mark_price > 0){
            $this->totalPrice = $this->totalPrice - ($item->quanity * $item->mark_price);
        }
        $this->totalPrice = $this->totalPrice - ($item->profit_price * $item->quanity);

        $this->itemsCount = $this->itemsCount - $item->quanity;
        array_forget($this->items, $index);
        if(count($this->items) == 0){
            $this->totalPrice = 0;
        }
    }

    public function refresh(){
        $this->totalPrice = 0;
        $this->itemsCount = 0;
        foreach ($this->items as $item){
            $this->totalPrice = $this->totalPrice + ($item->profit_price * $item->quanity);
            if($item->mark_price > 0){
                $this->totalPrice = $this->totalPrice + ($item->quanity * $item->mark_price);
            }
            $this->itemsCount = $this->itemsCount + $item->quanity;
        }
    }

    public function getItem($item){
        return $this->items[$item];
    }
    public function addMark($item, $filename, $mark_id){
       $item = $this->items[$item];
       $mark = Mark::where('macma_id', $mark_id)->first();
       $item->mark = $mark_id;
        $item->project_url = $filename;
        $item->mark_price = $mark->price_max;
       $this->totalPrice = $this->totalPrice + ($item->mark_price * $item->quanity);
    }
    public function deleteItemImage($item){

    }
    public function deleteItemMark($item){
        $item = $this->items[$item];
        $this->totalPrice = $this->totalPrice - ($item->mark_price * $item->quanity);
        $item->mark = null;
        $item->project_url = null;
        $item->mark_price = null;
    }
}
