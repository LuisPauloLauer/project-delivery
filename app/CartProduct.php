<?php

namespace App;


use App\Library\FilesControl;
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
            $this->items                = $oldCart->items;
            $this->totalQty             = $oldCart->totalQty;
            $this->totalPrice           = $oldCart->totalPrice;
            $this->userSiteId           = $oldCart->userSiteId;
            $this->userSiteName         = $oldCart->userSiteName;
            $this->store                = $oldCart->store;
        }
    }

    public function add(mdProducts $item, $id, $quantity, $observation)
    {
        $pathImagens = FilesControl::getPathImages();
        $Store = mdProducts::find($id)->pesqStore;

        if($item->unit_promotion_price > 0){
            $item_price = $item->unit_promotion_price;
        } else {
            $item_price = $item->unit_price;
        }

        $storedItem = [
            'qty' => $quantity,
            'price' => $item_price,
            'observation' => $observation,
            'url_image' => (
                (mdProducts::find($id)->pesqFirstImageProduct) ?
                    $pathImagens.'/products/store_id_'.$Store->id.'/'.$id.'/small/'.mdProducts::find($id)->pesqFirstImageProduct->path_image :
                    $pathImagens.'/../../files/images/no_photo.png'
                ),
            'item' => $item
        ];

        if($this->items){
            if(array_key_exists($id, $this->items)){
                $storedItem = $this->items[$id];
                $storedItem['qty'] += $quantity;
            }
        }

        $storedItem['price'] = number_format($item_price * $storedItem['qty'],2);
        $this->items[$id] = $storedItem;
        $this->totalQty += $quantity;
        $this->totalPrice += $item_price * $quantity;

        $this->totalPrice = number_format($this->totalPrice,2);

        if(Session::has('userSiteLogged')){
            $this->userSiteId    = Session::get('userSiteLogged')->id;
            $this->userSiteName  = Session::get('userSiteLogged')->name;
        } else {
            $this->userSiteId    = null;
            $this->userSiteName  = null;
        }

        $this->store = $Store->id;

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
                    $storedItem['price'] = number_format( $storedItem['price'],2);
                    $this->totalQty++;
                    $this->totalPrice += $item_price;
                    $this->totalPrice = number_format($this->totalPrice,2);

                } else if ($operator == 'minus') {

                    $storedItem['qty'] += (-1);
                    $storedItem['price'] += (-$item_price);
                    $storedItem['price'] = number_format($storedItem['price'],2);
                    $this->totalQty--;
                    $this->totalPrice += (-$item_price);
                    $this->totalPrice = number_format($this->totalPrice,2);

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
                $this->totalPrice = number_format($this->totalPrice,2);

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
