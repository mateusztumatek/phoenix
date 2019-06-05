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

    public function addItem($item, $quantity, $length, $type, $data = null){

            $item->quantity = $quantity;
            $item->length = $length;
            $item->type = $type;
            $this->totalPrice = $this->totalPrice + ($item->price * $quantity);
            $this->itemsCount = $this->itemsCount + $quantity;
             array_push($this->items, $item);
    }
    public function addDesign($design, $quantity, $length){
        $item = [];
        $item->design = $design;
        $item->quantity = $quantity;
        $item->length = $length;
        $item->type = 'design';
        $this->totalPrice = $this->totalPrice + ($design->price * $quantity);
        $this->itemsCount = $this->itemsCount * $quantity;
    }
    public function deleteItem($index){
        $item = $this->items[$index];

        $this->totalPrice = $this->totalPrice - ($item->price * $item->quantity);

        $this->itemsCount = $this->itemsCount - $item->quantity;
        array_forget($this->items, $index);
        if(count($this->items) == 0){
            $this->totalPrice = 0;
        }
    }

    public function refresh(){
        $this->totalPrice = 0;
        $this->itemsCount = 0;
        foreach ($this->items as $item){
            $this->totalPrice = $this->totalPrice + ($item->price * $item->quantity);
            $this->itemsCount = $this->itemsCount + $item->quantity;
        }
    }

    public function getItem($item){
        return $this->items[$item];
    }

}
