<?php

namespace App;


use Illuminate\Support\Facades\Session;

class CartProduct
{
    public $items               = null;
    public $totalQty            = 0;
    public $totalPrice          = 0;
    public $userSiteId          = null;
    public $userSiteName        = null;
    public $store               = null;

    public function __construct($oldCart)
    {
        if($oldCart){
            $this->items        = $oldCart->items;
            $this->totalQty     = $oldCart->totalQty;
            $this->totalPrice   = $oldCart->totalPrice;
            $this->userSiteId   = $oldCart->userSiteId;
            $this->userSiteName = $oldCart->userSiteName;
            $this->store        = $oldCart->store;
        }
    }

    public function add(mdProducts $item, $id, $quantity, $observation)
    {

        if($item->unit_promotion_price > 0){
            $item_price = $item->unit_promotion_price;
        } else {
            $item_price = $item->unit_price;
        }

        $storedItem = ['qty' => $quantity, 'price' => $item_price, 'observation' => $observation, 'item' => $item];

        if($this->items){
            if(array_key_exists($id, $this->items)){
                $storedItem = $this->items[$id];
                $storedItem['qty'] += $quantity;
            }
        }

        $storedItem['price'] = $item_price * $storedItem['qty'];
        $this->items[$id] = $storedItem;
        $this->totalQty += $quantity;
        $this->totalPrice += $item_price * $quantity;

        if(Session::has('userSiteLogged')){
            $this->userSiteId    = Session::get('userSiteLogged')->id;
            $this->userSiteName  = Session::get('userSiteLogged')->name;
        } else {
            $this->userSiteId    = null;
            $this->userSiteName  = null;
        }

        $this->store = $this->items[$id]['item']['store'];

    }

    public function edit($id, $operator)
    {
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {

                if($this->items[$id]['item']['unit_promotion_price'] > 0){
                    $item_price = $this->items[$id]['item']['unit_promotion_price'];
                } else {
                    $item_price = $this->items[$id]['item']['unit_price'];
                }

                $storedItem = $this->items[$id];

                if ($operator == 'plus') {

                    $storedItem['qty'] += 1;
                    $storedItem['price'] += $item_price;
                    $this->totalQty++;
                    $this->totalPrice += $item_price;

                } else if ($operator == 'minus') {

                    $storedItem['qty'] += (-1);
                    $storedItem['price'] += (-$item_price);
                    $this->totalQty--;
                    $this->totalPrice += (-$item_price);

                }

                $this->items[$id] = $storedItem;

            }

            if(Session::has('userSiteLogged')){
                $this->userSiteId    = Session::get('userSiteLogged')->id;
                $this->userSiteName  = Session::get('userSiteLogged')->name;
            } else {
                $this->userSiteId    = null;
                $this->userSiteName  = null;
            }
        }
    }

    public function dell($id)
    {
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {

                $this->totalQty = $this->totalQty - $this->items[$id]['qty'];
                $this->totalPrice = $this->totalPrice - $this->items[$id]['price'];

                unset($this->items[$id]);
            }

            if(Session::has('userSiteLogged')){
                $this->userSiteId    = Session::get('userSiteLogged')->id;
                $this->userSiteName  = Session::get('userSiteLogged')->name;
            } else {
                $this->userSiteId    = null;
                $this->userSiteName  = null;
            }
        }

        if(empty($this->items)){
             $this->items            = null;
             $this->totalQty         = 0;
             $this->totalPrice       = 0;
             $this->userSiteId       = null;
             $this->userSiteName     = null;
        }
    }

}
