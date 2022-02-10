<?php

namespace App;


use App\Library\FilesControl;
use Illuminate\Support\Facades\Session;

class CartProduct
{
    private $firstAdd;
    public $items               = null;
    public $shipping            = 0;
    public $percDiscount        = 0;
    public $totalQty            = 0;
    public $subTotalPrice       = 0;
    public $totalPrice          = 0;
    public $userSiteId          = null;
    public $userSiteName        = null;
    public $store               = null;

    public function __construct($oldCart)
    {
        if($oldCart){
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
        $this->subTotalPrice += $item_price * $quantity;
        $this->totalPrice += $item_price * $quantity;

        if(Session::has('userSiteLogged')){
            $this->userSiteId    = Session::get('userSiteLogged')->id;
            $this->userSiteName  = Session::get('userSiteLogged')->name;
            $universityBuilding = UserSite::find(Session::get('userSiteLogged')->id)->pesqUniversityBuilding;
            $this->percDiscount = $universityBuilding->percentage_discount;
        } else {
            $this->userSiteId    = null;
            $this->userSiteName  = null;
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
                    $this->subTotalPrice += $item_price;
                    $this->totalPrice += $item_price;
                } else if ($operator == 'minus') {
                    $storedItem['qty'] += (-1);
                    $storedItem['price'] += (-$item_price);
                    $this->totalQty--;
                    $this->subTotalPrice += (-$item_price);
                    $this->totalPrice += (-$item_price);
                }

                if($this->percDiscount > 0){
                    $this->totalPrice = ($this->subTotalPrice + $this->shipping) - (($this->subTotalPrice + $this->shipping) /100*$this->percDiscount);
                }
                $storedItem['price'] = number_format($storedItem['price'],2);
                $this->subTotalPrice = number_format($this->subTotalPrice,2);
                $this->totalPrice = number_format($this->totalPrice,2);
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
                $this->subTotalPrice = $this->subTotalPrice - $this->items[$id]['price'];
                $this->totalPrice = $this->totalPrice - $this->items[$id]['price'];
                if($this->percDiscount > 0){
                    $this->totalPrice = ($this->subTotalPrice + $this->shipping) - (($this->subTotalPrice + $this->shipping) /100*$this->percDiscount);
                }

                $this->subTotalPrice = number_format($this->subTotalPrice,2);
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
            $this->items           = null;
            $this->totalQty        = 0;
            $this->subTotalPrice   = 0;
            $this->totalPrice      = 0;
            $this->userSiteId      = null;
            $this->userSiteName    = null;
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
