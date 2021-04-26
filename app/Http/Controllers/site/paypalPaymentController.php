<?php

namespace App\Http\Controllers\site;

use App\CartKit;
use App\CartProduct;
use App\Http\Controllers\Controller;
use App\mdDemandsFood;
use App\Paypal\CreatePayment;
use App\Paypal\ExecutePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class paypalPaymentController extends Controller
{

    public function create()
    {
        $messageError = null;

        $payment = new CreatePayment;

        if( (Session::has('shopCartKit')) && (Session::has('shopCartProduct')) ){

            $oldCartKit = Session::get('shopCartKit');
            $cartKit = new CartKit($oldCartKit);
            $oldCartProduct = Session::get('shopCartProduct');
            $cartProduct = new CartProduct($oldCartProduct);

            $Kits = $cartKit->items;
            $Products = $cartProduct->items;

            $PaymentCreate = $payment->create($Kits, $Products, $messageError);

        } else if (Session::has('shopCartKit')){

            $oldCartKit = Session::get('shopCartKit');
            $cartKit = new CartKit($oldCartKit);

            $Kits = $cartKit->items;
            $Products = null;

            $PaymentCreate = $payment->create($Kits, $Products, $messageError);

        } else if (Session::has('shopCartProduct')){

            $oldCartProduct = Session::get('shopCartProduct');
            $cartProduct = new CartProduct($oldCartProduct);

            $Kits = null;
            $Products = $cartProduct->items;

            $PaymentCreate = $payment->create($Kits, $Products, $messageError);

        } else {
            return back();
        }

        if($PaymentCreate == false){
            return $messageError;
        }else{
            return $PaymentCreate;
        }
    }

    public function payPalStatus(Request $request)
    {
        $messageError                   = null;

        $userSite                       = null;

        $typeDelivery                   = 'Entregar';
        $invoiceNumberPayment           = null;
        $typePayment                    = null;
        $currencyPayment                = null;
        $taxPricePayment                = 0;
        $shippingPricePayment           = 0;
        $shippingDiscountPricePayment   = 0;
        $insurancePricePayment          = 0;
        $handlingFeePricePayment        = 0;
        $totalPayment                   = 0;

        $payment = new ExecutePayment();

        $PaymentExecute = $payment->execute($messageError);

        if($PaymentExecute == false){
            return $messageError;
        }else{
            if ($PaymentExecute->getState() === 'approved') {

                $jsonPaymentExecute = json_decode($PaymentExecute);

                $typePayment = $jsonPaymentExecute->payer->payment_method;

                $jsonTransactions = $jsonPaymentExecute->transactions;

                foreach($jsonTransactions as $dataTransaction){
                    if (property_exists($dataTransaction, "invoice_number")) {
                        $invoiceNumberPayment = $dataTransaction->invoice_number;
                    }

                    if (property_exists($dataTransaction, "amount")) {
                        $jsonTransactionsAmount = $dataTransaction->amount;
                    }
                }

                $totalPayment       = $jsonTransactionsAmount->total;
                $currencyPayment    = $jsonTransactionsAmount->currency;

                $jsonTransactionsDetails = $jsonTransactionsAmount->details;

                $taxPricePayment                = $jsonTransactionsDetails->tax;
                $shippingPricePayment           = $jsonTransactionsDetails->shipping;
                $shippingDiscountPricePayment   = $jsonTransactionsDetails->shipping_discount;
                $insurancePricePayment          = $jsonTransactionsDetails->insurance;
                $handlingFeePricePayment        = $jsonTransactionsDetails->handling_fee;

                /*
                foreach($jsonTransactionsDetails as $dataTransactionDetails){
                    if (property_exists($dataTransactionDetails, "tax")) {
                        $taxPricePayment = $dataTransactionDetails->tax;
                    }

                    if (property_exists($dataTransactionDetails, "shipping")) {
                        $shippingPricePayment = $dataTransactionDetails->shipping;
                    }

                    if (property_exists($dataTransactionDetails, "shipping_discount")) {
                        $shippingDiscountPricePayment = $dataTransactionDetails->shipping_discount;
                    }

                    if (property_exists($dataTransactionDetails, "insurance")) {
                        $insurancePricePayment = $dataTransactionDetails->insurance;
                    }

                    if (property_exists($dataTransactionDetails, "handling_fee")) {
                        $handlingFeePricePayment = $dataTransactionDetails->handling_fee;
                    }
                }*/

                $informationPayment = array(

                    "invoiceNumberPayment"          => $invoiceNumberPayment,
                    "typePayment"                   => $typePayment,
                    "currencyPayment"               => $currencyPayment,
                    "taxPricePayment"               => $taxPricePayment,
                    "shippingPricePayment"          => $shippingPricePayment,
                    "shippingDiscountPricePayment"  => $shippingDiscountPricePayment,
                    "insurancePricePayment"         => $insurancePricePayment,
                    "handlingFeePricePayment"       => $handlingFeePricePayment,
                    "totalPayment"                  => $totalPayment,
                );

                $DemandFood = new mdDemandsFood();

                if( (Session::has('shopCartKit')) && (Session::has('shopCartProduct')) ){

                    $oldCartKit = Session::get('shopCartKit');
                    $cartKit = new CartKit($oldCartKit);
                    $oldCartProduct = Session::get('shopCartProduct');
                    $cartProduct = new CartProduct($oldCartProduct);

                    $Kits = $cartKit->items;
                    $Products = $cartProduct->items;

                    $userSite = Session::get('userSiteLogged')->id;

                    $DemandFoodStatus = $DemandFood->addDemandOffShopCart($userSite, $typeDelivery, $informationPayment, $Kits, $Products, $messageError);

                    if($DemandFoodStatus){

                        $request->session()->forget('shopCartKit');
                        $request->session()->forget('shopCartProduct');

                        return 'Pagamento aprovado Kit e Produto';
                    }

                } else if (Session::has('shopCartKit')){

                    $oldCartKit = Session::get('shopCartKit');
                    $cartKit = new CartKit($oldCartKit);

                    $Kits = $cartKit->items;
                    $Products = null;

                    $userSite = Session::get('userSiteLogged')->id;

                    $DemandFoodStatus = $DemandFood->addDemandOffShopCart($userSite, $typeDelivery, $informationPayment, $Kits, $Products, $messageError);

                    if($DemandFoodStatus){

                        $request->session()->forget('shopCartKit');
                        $request->session()->forget('shopCartProduct');

                        return 'Pagamento aprovado Kit';
                    }

                } else if (Session::has('shopCartProduct')){

                    $oldCartProduct = Session::get('shopCartProduct');
                    $cartProduct = new CartProduct($oldCartProduct);

                    $Kits = null;
                    $Products = $cartProduct->items;

                    $userSite = Session::get('userSiteLogged')->id;

                    $DemandFoodStatus = $DemandFood->addDemandOffShopCart($userSite, $typeDelivery, $informationPayment, $Kits, $Products, $messageError);

                    if($DemandFoodStatus){

                        $request->session()->forget('shopCartKit');
                        $request->session()->forget('shopCartProduct');

                        return 'Pagamento aprovado Produto';
                    }

                } else {
                    return 'Erro ao fazer o pedido!!!';
                }

            } else {
                return 'Erro ao efetuar o pagamento!!!';
            }
        }
    }
}
