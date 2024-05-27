<?php

namespace NavegapComprame;


class Cart
{
    public $items = null;
    public $totalQuantity = 0;
    public $totalPrice = 0;
    public $status = false;

    public function __construct($cartExist)
    {
        if($cartExist){
         $this->items = $cartExist->items;
         $this->totalQuantity = $cartExist->totalQuantity;
         $this->totalPrice = $cartExist->totalPrice;
         $this->status = $cartExist->status;
        }
    }

    public function add($item, $id)
    {
        $storedItem = [ 'quantity' => 0,
                    'price' => ($item->discount ? $item->price_venta : $item->price),'item' => $item ];

        if($this->items){
            if(array_key_exists($id, $this->items)){
                 $storedItem = $this->items[$id];
            }
        }

        if( $storedItem['quantity'] < 155 ){

            $storedItem['quantity']++;
            $storedItem['price'] = ($item->discount ? $item->price_venta : $item->price) * $storedItem['quantity'];
            $this->items[$id] = $storedItem;
            $this->totalQuantity++;
            $this->totalPrice += ($item->discount ? $item->price_venta : $item->price);
            $this->status = false;

        }else{
            $this->status = true;
        }
    }


    public function add_with_quantity($item, $id, $quantity)
    {
        $storedItem = [ 'quantity' => 0,
            'price' => ($item->discount ? $item->price_venta : $item->price),'item' => $item ];

        if($this->items){
            if(array_key_exists($id, $this->items)){
                $storedItem = $this->items[$id];
            }
        }

        $new_quantity = $storedItem['quantity'] + $quantity;

        if( $new_quantity <= 7){

            $storedItem['quantity'] += $quantity;
            $storedItem['price'] = ($item->discount ? $item->price_venta : $item->price) * $storedItem['quantity'];
            $this->items[$id] = $storedItem;
            $this->totalQuantity += $quantity;
            $this->totalPrice += ($item->discount ? $item->price_venta : $item->price) * $quantity;
            $this->status = false;

        }else{
            $this->status = true;
        }
   }

   public function update($id, $quantity)
   {
       $quantityOld = $this->items[$id]['quantity'];

       $this->items[$id]['quantity'] = $quantity;
       $this->items[$id]['price'] = ($this->items[$id]['item']['discount'] ? $this->items[$id]['item']['price_venta'] : $this->items[$id]['item']['price']) * $quantity;

       if($quantity > $quantityOld){
           $this->totalQuantity += ($quantity- $quantityOld);
           $this->totalPrice += ($this->items[$id]['item']['discount'] ? $this->items[$id]['item']['price_venta'] : $this->items[$id]['item']['price']) * ($quantity- $quantityOld);
       }else{
           $this->totalQuantity -= ($quantityOld-$quantity);
           $this->totalPrice -= ($this->items[$id]['item']['discount'] ? $this->items[$id]['item']['price_venta'] : $this->items[$id]['item']['price']) * ($quantityOld-$quantity);
       }
   }


   public function remove($id)
   {
        $this->totalQuantity -= $this->items[$id]['quantity'];
        $this->totalPrice -= $this->items[$id]['price'];
        unset($this->items[$id]);
   }

}