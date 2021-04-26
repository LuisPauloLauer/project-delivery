<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;

class mdDemandsFood extends Model
{
    protected $table = 'demands_food';

    public function getTotalAmountAttribute()
    {
        return round($this->attributes['total_amount'], 4);
    }

    public function addDemandOffShopCart($pUserSite, $pTypeDelivery, $pInformationPayment, $pKits = null, $pProducts = null, &$pmessageError)
    {
        $subTotalPrice = 0;
        $totalAmount = 0;

        $statusDemand = mdStatusDemandsFood::select('id')->where('type', 'included')->first();

       // dd($statusDemand->id);

        if($pKits){
            foreach($pKits as $Kit){
                $store = $Kit['item']['store'];
            }
        } else if ($pProducts) {
            foreach($pProducts as $Product){
                $store = $Product['item']['store'];
            }
        }

        $this->attributes['status']             = $statusDemand->id;
        $this->attributes['store']              = $store;
        $this->attributes['user_site']          = $pUserSite;
        $this->attributes['type_deliver']       = $pTypeDelivery;
        $this->attributes['type_payment']       = $pInformationPayment['typePayment'];
        $this->attributes['invoice_number']     = $pInformationPayment['invoiceNumberPayment'];
        $this->attributes['currency_payment']   = $pInformationPayment['currencyPayment'];

        if($pKits){
            foreach($pKits as $Kit){
                for($i=0; $i < count( $Kit['productSellSubItems'] ); $i++){
                    if($Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'] > 0 ){

                        $ObjKit = mdKits::where('id', $Kit['item']['id'])->where('status', 'S')->first();

                        $subTotalPrice = $subTotalPrice + ($ObjKit->pesqPrice() * $Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty']);
                        $totalAmount = $totalAmount + $Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'];
                    }
                }
            }
        }

        if($pProducts){
            foreach($pProducts as $Product){
                if($Product['qty'] > 0 ){

                    $ObjProduct = mdProducts::where('id', $Product['item']['id'])->where('status', 'S')->first();

                    $subTotalPrice = $subTotalPrice + ($ObjProduct->pesqPrice() * $Product['qty']);
                    $totalAmount = $totalAmount + $Product['qty'];
                }
            }
        }

        $this->attributes['total_amount']               = $totalAmount;
        $this->attributes['sub_total_price']            = $subTotalPrice;
        $this->attributes['tax_price']                  = floatval ($pInformationPayment['taxPricePayment']);
        $this->attributes['shipping_price']             = floatval ($pInformationPayment['shippingPricePayment']);
        $this->attributes['shipping_discount_price']    = floatval ($pInformationPayment['shippingDiscountPricePayment']);
        $this->attributes['insurance_price']            = floatval ($pInformationPayment['insurancePricePayment']);
        $this->attributes['handling_fee_price']         = floatval ($pInformationPayment['handlingFeePricePayment']);
        $this->attributes['total_price']                = floatval ($pInformationPayment['totalPayment']);

        try {
            if($this->save()){
                $saveItensStatus = $this->CreateDemandItensFood($this->attributes['id'], $pKits, $pProducts);

                return $saveItensStatus;
            } else {
                return false;
            }
        } catch (\Exception $exception) {
            throw new \ErrorException('Erro ao incluir o pedido!!! message: '.$exception->getMessage());
        }
    }

    protected function CreateDemandItensFood($pDemand, $pKits, $pProducts)
    {
        $errorSaveItem = true;

        if($pKits){
            foreach($pKits as $Kit){
                for($i=0; $i < count( $Kit['productSellSubItems'] ); $i++){
                    if($Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'] > 0 ){

                        $ObjKit = mdKits::where('id', $Kit['item']['id'])->where('status', 'S')->first();

                        $demandItens = new mdDemandsItensFood();

                        $demandItens->demand = $pDemand;
                        $demandItens->kit_id = $ObjKit->id;
                        $demandItens->amount = $Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'];
                        $demandItens->observation = $Kit['productSellSubItems'][$i][$Kit['item']['id']]['observation'];

                        $kitSubItens = null;

                        for($j=0; $j < count( $Kit['productSellSubItems'][$i][$Kit['item']['id']]['productSellItems'] ); $j++){

                            $ObjProduct = mdProducts::where('id', $Kit['productSellSubItems'][$i][$Kit['item']['id']]['productSellItems'][$j])->where('status', 'S')->first();

                            if($j == (count( $Kit['productSellSubItems'][$i][$Kit['item']['id']]['productSellItems'] )-1) ){
                                $kitSubItens = $kitSubItens . $ObjProduct->id;
                            } else {
                                $kitSubItens = $kitSubItens . $ObjProduct->id . ',';
                            }

                        }

                        $demandItens->kit_sub_itens = $kitSubItens;

                        try {
                            if(!$demandItens->save()){
                                $errorSaveItem = false;
                            }
                        } catch (\Exception $exception) {
                            throw new \ErrorException('Erro ao incluir itens do pedido: ('.$pDemand.')');
                        }

                        unset($demandItens);

                    }
                }
            }
        }

        if($pProducts){
            foreach($pProducts as $Product){
                if($Product['qty'] > 0 ){

                    $ObjProduct = mdProducts::where('id', $Product['item']['id'])->where('status', 'S')->first();

                    $demandItens = new mdDemandsItensFood();

                    $demandItens->demand = $pDemand;
                    $demandItens->product_id = $ObjProduct->id;
                    $demandItens->amount = $Product['qty'];
                    $demandItens->observation = $Product['observation'];

                    $kitSubItens = null;

                    $demandItens->kit_sub_itens = $kitSubItens;

                    try {
                        if(!$demandItens->save()){
                            $errorSaveItem = false;
                        }
                    } catch (\Exception $exception) {
                        throw new \ErrorException('Erro ao incluir itens do pedido: ('.$pDemand.')');
                    }

                    unset($demandItens);

                }
            }
        }

        return $errorSaveItem;

    }
}
