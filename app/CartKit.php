<?php

namespace App;


use Illuminate\Support\Facades\Session;

class CartKit
{
    private $firstAdd;
    public $items                       = null;
    public $shipping                    = 0;
    public $percDiscount                = 0;
    public $totalQty                    = 0;
    public $subTotalPrice               = 0;
    public $totalPrice                  = 0;
    public $userSiteId                  = null;
    public $userSiteName                = null;
    public $store                       = null;

    public function __construct($oldCart)
    {
        if ($oldCart) {
            $this->firstAdd             = false;
            $this->items                = $oldCart->items;
            $this->shipping             = $oldCart->shipping;
            $this->percDiscount         = $oldCart->percDiscount;
            $this->totalQty             = $oldCart->totalQty;
            $this->subTotalPrice        = $oldCart->subTotalPrice;
            $this->totalPrice           = $oldCart->totalPrice;
            $this->userSiteId           = $oldCart->userSiteId;
            $this->userSiteName         = $oldCart->userSiteName;
            $this->store                = $oldCart->store;
        } else {
            $this->firstAdd             = true;
        }
    }

    public function add(mdKits $item, $id, $quantity, $observation, $productSellSubItems = null)
    {
        $Store = mdKits::find($id)->pesqStore;
        $isNotDiffSellItem = false;

        if ($item->unit_promotion_price > 0) {
            $item_price = $item->unit_promotion_price;
        } else {
            $item_price = $item->unit_price;
        }

        if ($productSellSubItems) {
            $storedItem = ['qty' => $quantity, 'price' => $item_price, 'observation' => null, 'item' => $item, 'prodSellSubItemsIndex' => 0, 'productSellSubItems' => []];
        } else {
            $storedItem = ['qty' => $quantity, 'price' => $item_price, 'observation' => $observation, 'item' => $item];
        }

        if ($this->items) {
            if (array_key_exists($id, $this->items)) {

                $storedItem = $this->items[$id];
                $storedItem['qty'] += $quantity;

                for ($ni = 0; $ni < count($this->items[$id]['productSellSubItems']); $ni++) {
                    $diffSellItem = array_diff($productSellSubItems, $this->items[$id]['productSellSubItems'][$ni][$id]['productSellItems']);

                    if (!$diffSellItem) {
                        $isNotDiffSellItem = true;
                        $storedItem['productSellSubItems'][$ni][$id]['qnty'] = $this->items[$id]['productSellSubItems'][$ni][$id]['qnty'] + $quantity;

                        if( (!empty($observation)) && ($observation != $this->items[$id]['productSellSubItems'][$ni][$id]['observation']) ){
                            $storedItem['productSellSubItems'][$ni][$id]['observation'] = $observation;
                        }
                    }
                }

                if (!$isNotDiffSellItem) {
                    $storedItem['prodSellSubItemsIndex']++;
                    $storedItem['productSellSubItems'][$storedItem['prodSellSubItemsIndex']][$id]['qnty'] = $quantity;
                    $storedItem['productSellSubItems'][$storedItem['prodSellSubItemsIndex']][$id]['observation'] = $observation;
                    $storedItem['productSellSubItems'][$storedItem['prodSellSubItemsIndex']][$id]['productSellItems'] = $productSellSubItems;
                }

            } else {
                $storedItem['productSellSubItems'][$storedItem['prodSellSubItemsIndex']][$id]['qnty'] = $quantity;
                $storedItem['productSellSubItems'][$storedItem['prodSellSubItemsIndex']][$id]['observation'] = $observation;
                $storedItem['productSellSubItems'][$storedItem['prodSellSubItemsIndex']][$id]['productSellItems'] = $productSellSubItems;
            }
        } else {
            $storedItem['productSellSubItems'][$storedItem['prodSellSubItemsIndex']][$id]['qnty'] = $quantity;
            $storedItem['productSellSubItems'][$storedItem['prodSellSubItemsIndex']][$id]['observation'] = $observation;
            $storedItem['productSellSubItems'][$storedItem['prodSellSubItemsIndex']][$id]['productSellItems'] = $productSellSubItems;
        }

        $storedItem['price'] = ($item_price * $storedItem['qty']);
        $this->items[$id] = $storedItem;
        $this->totalQty += $quantity;
        $this->subTotalPrice += ($item_price * $quantity);
        $this->totalPrice += ($item_price * $quantity);

        if (Session::has('userSiteLogged')) {
            $this->userSiteId = Session::get('userSiteLogged')->id;
            $this->userSiteName = Session::get('userSiteLogged')->name;
            $universityBuilding = UserSite::find(Session::get('userSiteLogged')->id)->pesqUniversityBuilding;
            $this->percDiscount = $universityBuilding->percentage_discount;
        } else {
            $this->userSiteId   = null;
            $this->userSiteName = null;
            $this->percDiscount = 0;
        }

        if($this->firstAdd){
            $this->totalPrice += $Store->minimum_shipping;
        }

        $this->shipping = $Store->minimum_shipping;
        $this->store = $Store->id;

        if($this->percDiscount > 0){
            $this->totalPrice = ($this->subTotalPrice + $this->shipping) - (($this->subTotalPrice + $this->shipping) /100*$this->percDiscount);
        }

        $this->subTotalPrice = number_format($this->subTotalPrice,2);
        $this->totalPrice = number_format($this->totalPrice,2);
    }

    public function edit($id, $SubItemsIndex, $operator)
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
                    $storedItem['productSellSubItems'][$SubItemsIndex][$id]['qnty'] += 1;
                    $this->totalQty++;
                    $this->subTotalPrice += $item_price;
                    $this->totalPrice += $item_price;
                } else if ($operator == 'minus') {
                    $storedItem['qty'] += (-1);
                    $storedItem['price'] += (-$item_price);
                    $storedItem['productSellSubItems'][$SubItemsIndex][$id]['qnty'] += (-1);
                    $this->totalQty--;
                    $this->subTotalPrice += (-$item_price);
                    $this->totalPrice += (-$item_price);
                }

                if($this->percDiscount > 0) {
                    $this->totalPrice = ($this->subTotalPrice + $this->shipping) - (($this->subTotalPrice + $this->shipping) /100*$this->percDiscount);
                }

                $this->items[$id] = $storedItem;
            }
        }
    }

    public function dell($id, $SubItemsIndex)
    {
        if ($this->items) {
            if (array_key_exists($id, $this->items)) {

                if($this->items[$id]['item']['unit_promotion_price'] > 0){
                    $item_price = $this->items[$id]['item']['unit_promotion_price'];
                } else {
                    $item_price = $this->items[$id]['item']['unit_price'];
                }

                $storedItem = $this->items[$id];

                $storedItem['qty'] = $storedItem['qty'] - $this->items[$id]['productSellSubItems'][$SubItemsIndex][$id]['qnty'];
                $storedItem['price'] = $storedItem['price'] - ($item_price * $this->items[$id]['productSellSubItems'][$SubItemsIndex][$id]['qnty']);
                $storedItem['productSellSubItems'][$SubItemsIndex][$id]['qnty'] = 0;
                $this->totalQty = $this->totalQty - $this->items[$id]['productSellSubItems'][$SubItemsIndex][$id]['qnty'];
                $this->subTotalPrice = $this->subTotalPrice - ($item_price * $this->items[$id]['productSellSubItems'][$SubItemsIndex][$id]['qnty']);
                $this->totalPrice = $this->totalPrice - ($item_price * $this->items[$id]['productSellSubItems'][$SubItemsIndex][$id]['qnty']);
                $this->items[$id] = $storedItem;

                if($this->percDiscount > 0){
                    $this->totalPrice = ($this->subTotalPrice + $this->shipping) - (($this->subTotalPrice + $this->shipping) /100*$this->percDiscount);
                }
            }
        }
    }

    public function updateValues()
    {
        if(Session::has('userSiteLogged') && $this->items && $this->subTotalPrice > 0){
            $universityBuilding = UserSite::find(Session::get('userSiteLogged')->id)->pesqUniversityBuilding;
            $this->percDiscount = $universityBuilding->percentage_discount;
            if($this->percDiscount > 0){
                $this->totalPrice = ($this->subTotalPrice + $this->shipping) - (($this->subTotalPrice + $this->shipping) /100*$this->percDiscount);
            }
        }
    }
}
