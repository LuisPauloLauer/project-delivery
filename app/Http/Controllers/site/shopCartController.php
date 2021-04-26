<?php

namespace App\Http\Controllers\site;

use App\Cart;
use App\CartKit;
use App\CartProduct;
use App\Http\Controllers\Controller;
use App\Library\FilesControl;
use App\mdCategoriesProduct;
use App\mdImagensKits;
use App\mdImagensProducts;
use App\mdKits;
use App\mdProducts;
use App\mdRelKitsProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class shopCartController extends Controller
{
    public function showModalProduct(Request $request)
    {
        $object = $request->product_object;
        $id = $request->product_id;
        $storeID = $request->store_id;
        $emptyCart = $request->empty_cart;
        $categoryId = null;
        $numArrayCategory = 0;

        if($object == 'kit'){

            if( mdKits::where('id', $id)->where('status', 'S')->exists() ){

                $Kit = mdKits::findOrFail($id);

                if($Kit->store <> $storeID){

                    $responseProduct['success']     = false;
                    $responseProduct['message']     = 'Kit não pertence a esta loja!!!';
                    $responseProduct['object']      = null;
                    $responseProduct['id']          = null;
                    echo json_encode($responseProduct);
                    return;
                }

                if($emptyCart){
                    $request->session()->forget('shopCartKit');
                    $request->session()->forget('shopCartProduct');
                }

                if (Session::has('shopCartKit')){

                    $oldCartKitStore = Session::get('shopCartKit');
                    $cartKitStore = new CartKit($oldCartKitStore);
                    $cartKitStoreItems = $cartKitStore->items;

                    foreach ($cartKitStoreItems as $kitItem){
                        if($kitItem['item']['store'] <> $Kit->store){

                            $responseProduct['success']     = false;
                            $responseProduct['message']     = 'diffstore';
                            $responseProduct['object']      = null;
                            $responseProduct['id']          = null;
                            echo json_encode($responseProduct);
                            return;
                        }
                    }
                }

                if (Session::has('shopCartProduct')){

                    $oldCartProductStore = Session::get('shopCartProduct');
                    $cartProductStore = new CartKit($oldCartProductStore);
                    $cartProductStoreItems = $cartProductStore->items;

                    foreach ($cartProductStoreItems as $productItem){
                        if($productItem['item']['store'] <> $Kit->store){

                            $responseProduct['success']     = false;
                            $responseProduct['message']     = 'diffstore';
                            $responseProduct['object']      = null;
                            $responseProduct['id']          = null;
                            echo json_encode($responseProduct);
                            return;
                        }
                    }
                }

                $relProductsKit = mdRelKitsProducts::where('kit', $Kit->id)->get();

                if( (count($relProductsKit) <= 0) ){

                    $responseProduct['success']     = false;
                    $responseProduct['message']     = 'Kit não existe!!!';
                    $responseProduct['object']      = null;
                    $responseProduct['id']          = null;
                    echo json_encode($responseProduct);
                    return;
                }

                for ($i = 0; $i < count($relProductsKit); $i++){
                    $inProductsKit[$i] = $relProductsKit[$i]->product;
                }

                $ProductsKit = mdProducts::where('status', 'S')->whereIn('id', $inProductsKit)->orderBy('category_product', 'asc')->get();

                $relImagensKit  = mdImagensKits::where('kit', $Kit->id)->orderby('id', 'asc')->get();

                $responseProduct['success']                 = true;
                $responseProduct['object']                  = 'kit';
                $responseProduct['id']                      = $Kit->id;
                $responseProduct['nameproduct']             = $Kit->name;
                $responseProduct['description']             = $Kit->description;
                $responseProduct['unit_price']              = $Kit->unit_price;
                $responseProduct['unit_promotion_price']    = $Kit->unit_promotion_price;
                $responseProduct['imagens']                 = $relImagensKit;
                $responseProduct['products']                = $ProductsKit;

                foreach ($ProductsKit as $Product){
                    if($categoryId <> $Product->category_product){
                        $products_category[$numArrayCategory] = array(
                            "products_category_id" => $Product->category_product,
                            "products_category_name" => mdCategoriesProduct::find($Product->category_product)->name,
                        );

                        $numArrayCategory++;
                    }

                    $categoryId = $Product->category_product;
                }

                $responseProduct['products_category']       = $products_category;

                echo json_encode($responseProduct);
                return;

            } else {

                $responseProduct['success']     = false;
                $responseProduct['message']     = 'Kit não existe!!!';
                $responseProduct['object']      = null;
                $responseProduct['id']          = null;
                echo json_encode($responseProduct);
                return;
            }
        }

        if($object == 'product'){

            if( mdProducts::where('id', $id)->where('status', 'S')->exists() ){

                $Product = mdProducts::findOrFail($id);

                if($Product->store <> $storeID){

                    $responseProduct['success']     = false;
                    $responseProduct['message']     = 'Produto não pertence a esta loja!!!';
                    $responseProduct['object']      = null;
                    $responseProduct['id']          = null;
                    echo json_encode($responseProduct);
                    return;
                }

                if (Session::has('shopCartProduct')){
                    $oldCartProductStore = Session::get('shopCartProduct');
                    $cartProductStore = new CartKit($oldCartProductStore);
                    $cartProductStoreItems = $cartProductStore->items;

                    foreach ($cartProductStoreItems as $productItem){
                        if($productItem['item']['store'] <> $Product->store){

                            $responseProduct['success']     = false;
                            $responseProduct['message']     = 'diffstore';
                            $responseProduct['object']      = null;
                            $responseProduct['id']          = null;
                            echo json_encode($responseProduct);
                            return;
                        }
                    }
                }

                if (Session::has('shopCartKit')){
                    $oldCartKitStore = Session::get('shopCartKit');
                    $cartKitStore = new CartKit($oldCartKitStore);
                    $cartKitStoreItems = $cartKitStore->items;

                    foreach ($cartKitStoreItems as $kitItem){
                        if($kitItem['item']['store'] <> $Product->store){

                            $responseProduct['success']     = false;
                            $responseProduct['message']     = 'diffstore';
                            $responseProduct['object']      = null;
                            $responseProduct['id']          = null;
                            echo json_encode($responseProduct);
                            return;
                        }
                    }
                }

                $relImagensProduct  = mdImagensProducts::where('product', $Product->id)->orderby('id', 'asc')->get();

                $responseProduct['success']                 = true;
                $responseProduct['object']                  = 'product';
                $responseProduct['id']                      = $Product->id;
                $responseProduct['nameproduct']             = $Product->name;
                $responseProduct['description']             = $Product->description;
                $responseProduct['unit_price']              = $Product->unit_price;
                $responseProduct['unit_promotion_price']    = $Product->unit_promotion_price;
                $responseProduct['imagens']                 = $relImagensProduct;

                echo json_encode($responseProduct);
                return;

            } else {

                $responseProduct['success']     = false;
                $responseProduct['message']     = 'Produto não existe!!!';
                $responseProduct['object']      = null;
                $responseProduct['id']          = null;
                echo json_encode($responseProduct);
                return;
            }
        }
    }

    public function addToCart(Request $request)
    {

        $object = $request->product_object;
        $id = $request->product_id;
        $productQnty = $request->product_qnty;
        $productObservation = $request->product_observation;

        if($object == 'kit'){
            $productsSell = $request->products_sell;
            $Kit = mdKits::findOrFail($id);

            if($Kit){

                $oldCart = Session::has('shopCartKit') ? Session::get('shopCartKit') : null;
                $cartKit = new CartKit($oldCart);
                $cartKit->add($Kit, $Kit->id, $productQnty, $productObservation, $productsSell);

                $request->session()->put('shopCartKit', $cartKit);

                $responseProduct['success'] = true;
                echo json_encode($responseProduct);
                return;

            } else {

                $responseProduct['success'] = false;
                $responseProduct['message'] = 'error';
                echo json_encode($responseProduct);
                return;

            }
        }

        if($object == 'product'){
            $Product = mdProducts::findOrFail($id);

            if($Product){
                $oldCart = Session::has('shopCartProduct') ? Session::get('shopCartProduct') : null;
                $cartProduct = new CartProduct($oldCart);
                $cartProduct->add($Product, $Product->id, $productQnty, $productObservation);

                $request->session()->put('shopCartProduct', $cartProduct);

                $responseProduct['success'] = true;
                echo json_encode($responseProduct);
                return;

            } else {

                $responseProduct['success'] = false;
                $responseProduct['message'] = 'error';
                echo json_encode($responseProduct);
                return;

            }
        }

    }

    public function getCart()
    {

        $pathImagens = FilesControl::getPathImages();

        if( (Session::has('shopCartKit')) && (Session::has('shopCartProduct')) ){

            $oldCartKit = Session::get('shopCartKit');
            $cartKit = new CartKit($oldCartKit);
            $oldCartProduct = Session::get('shopCartProduct');
            $cartProduct = new CartProduct($oldCartProduct);

        //    dd($cartKit,$cartProduct);

            return view('site.shopcart.cart',[
                'Kits'                      =>  $cartKit->items,
                'KitsTotalQnty'             =>  $cartKit->totalQty,
                'KitsTotalPrice'            =>  $cartKit->totalPrice,
                'Products'                  =>  $cartProduct->items,
                'ProductsTotalQnty'         =>  $cartProduct->totalQty,
                'ProductsTotalPrice'        =>  $cartProduct->totalPrice,
                'pathImagens'               =>  $pathImagens
            ]);

        } else if (Session::has('shopCartKit')){

            $oldCartKit = Session::get('shopCartKit');
            $cartKit = new CartKit($oldCartKit);

        //    dd($cartKit);

            return view('site.shopcart.cart',[
                'Kits'                      =>  $cartKit->items,
                'KitsTotalQnty'             =>  $cartKit->totalQty,
                'KitsTotalPrice'            =>  $cartKit->totalPrice,
                'pathImagens'               =>  $pathImagens
            ]);

        } else if (Session::has('shopCartProduct')){

            $oldCartProduct = Session::get('shopCartProduct');
            $cartProduct = new CartProduct($oldCartProduct);

       //     dd($cartProduct);

            return view('site.shopcart.cart',[
                'Products'                  =>  $cartProduct->items,
                'ProductsTotalQnty'         =>  $cartProduct->totalQty,
                'ProductsTotalPrice'        =>  $cartProduct->totalPrice,
                'pathImagens'               =>  $pathImagens
            ]);

        } else {
            die('sem carrinho');
        }

    }

    public function editQntyItemToCart(Request $request)
    {
        $object = $request->product_object;
        $id = $request->product_id;
        $sellItemsIndex = $request->product_sub_items;
        $operator = $request->product_operator;

        if($object == 'kit'){
            $Kit = mdKits::findOrFail($id);

            if($Kit){

                $oldCart = Session::has('shopCartKit') ? Session::get('shopCartKit') : null;
                $cartKit = new CartKit($oldCart);
                $cartKit->edit($Kit->id, $sellItemsIndex, $operator);

                $request->session()->put('shopCartKit', $cartKit);

                $responseProduct['success'] = true;
                echo json_encode($responseProduct);
                return;

            } else {

                $responseProduct['success'] = false;
                echo json_encode($responseProduct);
                return;

            }
        }

        if($object == 'product'){
            $Product = mdProducts::findOrFail($id);

            if($Product){
                $oldCart = Session::has('shopCartProduct') ? Session::get('shopCartProduct') : null;
                $cartProduct = new CartProduct($oldCart);
                $cartProduct->edit($Product->id, $operator);

                $request->session()->put('shopCartProduct', $cartProduct);

                $responseProduct['success'] = true;
                echo json_encode($responseProduct);
                return;

            } else {

                $responseProduct['success'] = false;
                echo json_encode($responseProduct);
                return;

            }
        }
    }

    public function dellItemToCart(Request $request)
    {
        $object = $request->product_object;
        $id = $request->product_id;
        $sellItemsIndex = $request->product_sub_items;

        if($object == 'kit'){
            $Kit = mdKits::findOrFail($id);

            if($Kit){

                $oldCart = Session::has('shopCartKit') ? Session::get('shopCartKit') : null;
                $cartKit = new CartKit($oldCart);
                $cartKit->dell($Kit->id, $sellItemsIndex);

                if($cartKit->totalQty > 0){
                    $request->session()->put('shopCartKit', $cartKit);
                }else{
                    $request->session()->forget('shopCartKit');
                }


                $responseProduct['success'] = true;
                echo json_encode($responseProduct);
                return;

            } else {

                $responseProduct['success'] = false;
                echo json_encode($responseProduct);
                return;

            }
        }

        if($object == 'product'){
            $Product = mdProducts::findOrFail($id);

            if($Product){
                $oldCart = Session::has('shopCartProduct') ? Session::get('shopCartProduct') : null;
                $cartProduct = new CartProduct($oldCart);
                $cartProduct->dell($Product->id);

                if(empty($cartProduct->items)){
                    $request->session()->forget('shopCartProduct');
                } else {
                    $request->session()->put('shopCartProduct', $cartProduct);
                }

                $responseProduct['success'] = true;
                echo json_encode($responseProduct);
                return;

            } else {

                $responseProduct['success'] = false;
                echo json_encode($responseProduct);
                return;

            }
        }
    }

    public function emptyCart(Request $request)
    {
        $request->session()->forget('shopCartKit');
        $request->session()->forget('shopCartProduct');

        return back();
    }

    public function viewPayment()
    {
        if(!Session::has('shopCartKit') && !Session::has('shopCartProduct')){
            return redirect()->route('home.index');
        } else if(!Session::has('userSiteLogged')){
            //$route = 'pedido-pagar';
            return redirect()->route('usersite.login');
        } else if (Session::has('shopCartKit') || Session::has('shopCartProduct')){
            return view('site.shopcart.demand');
        }
    }
}
