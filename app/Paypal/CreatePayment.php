<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 11/09/2020
 * Time: 15:34
 */

namespace App\Paypal;


use App\mdKits;
use App\mdProducts;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Exception\PayPalConnectionException;

class CreatePayment extends PayPal
{
    private $methodPayment                  = 'paypal'; //Méthodo de pagamento
    private $currencyPayment                = 'BRL';    //Moeda de pagamento
    //private $totalPayment                 = 0;        //Valor total da fatura (pedido)
    private $subTotalPayment                = 0;        //Valor total dos itens (pedido)
    //second values of services
    private $taxPricePayment                = 0;        //Valor do imposto
    private $shippingPricePayment           = 0;        //Valor do frete
    private $shippingDiscountPricePayment   = 0;        //Valor do disconto do frete
    private $insurancePricePayment          = 0;        //Valor do seguro
    private $handlingFeePricePayment        = 0;        //Valor da taxa de manuseio
    private $descriptionPayment             = 'Pagamento por paypal';   //Descrição do pagamento

    public function __construct($pInfoPayment)
    {
        if ($pInfoPayment['deliveryTypePayment']) {
            parent::__construct();
            $this->methodPayment                    = $pInfoPayment['deliveryTypePayment'];
            /*
            $this->currencyPayment                  = $pInfoPayment['currencyPayment'];
            $this->totalPayment                     = $pInfoPayment['totalPayment'];
            $this->subTotalPayment                  = $pInfoPayment['subTotalPayment'];
            //second values of services
            $this->taxPricePayment                  = $pInfoPayment['currencyPayment'];
            $this->shippingPricePayment             = $pInfoPayment['totalPayment'];
            $this->shippingDiscountPricePayment     = $pInfoPayment['subTotalPayment'];
            $this->insurancePricePayment            = $pInfoPayment['subTotalPayment'];
            $this->handlingFeePricePayment          = $pInfoPayment['subTotalPayment'];
            $this->descriptionPayment               = $pInfoPayment['subTotalPayment'];
            */
        }
    }

    public function create($pKits = null, $pProducts = null, &$pmessageError)
    {
        $countItens = 0;

        if($pKits){
            foreach($pKits as $Kit){
                for($i=0; $i < count( $Kit['productSellSubItems'] ); $i++){
                    if($Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'] > 0 ){

                        $itens[$countItens] = new Item();

                        $ObjKit = mdKits::where('id', $Kit['item']['id'])->where('status', 'S')->first();

                        $itemName = $ObjKit->name;
                        $itemQnty = (int)$Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'];
                        $itemCodeNumber = strval($ObjKit->id);

                        $itens[$countItens]->setName($itemName)
                            ->setCurrency('BRL')
                            ->setQuantity($itemQnty)
                            ->setSku($itemCodeNumber) // Similar to `item_number` in Classic API
                            ->setPrice($ObjKit->pesqPrice());

                        $this->subTotalPayment = $this->subTotalPayment + ($ObjKit->pesqPrice() * $Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty']);

                        $countItens++;

                    }
                }
            }
        }

        if($pProducts){
            foreach($pProducts as $Product){
                if($Product['qty'] > 0 ){

                    $itens[$countItens] = new Item();

                    $ObjProduct = mdProducts::where('id', $Product['item']['id'])->where('status', 'S')->first();

                    $itemName = $ObjProduct->name;
                    $itemQnty = (int)$Product['qty'];
                    $itemCodeNumber = strval($ObjProduct->id);

                    $itens[$countItens]->setName($itemName)
                        ->setCurrency('BRL')
                        ->setQuantity($itemQnty)
                        ->setSku($itemCodeNumber) // Similar to `item_number` in Classic API
                        ->setPrice($ObjProduct->pesqPrice());

                    $this->subTotalPayment = $this->subTotalPayment + ($ObjProduct->pesqPrice() * $Product['qty']);

                    $countItens++;

                }
            }
        }

        $objItemList = new ItemList();
        $objItemList->setItems($itens);
        $payment = $this->Payment($objItemList);

        try {
            $payment->create($this->apiContext);
            return redirect()->away($payment->getApprovalLink());
        } catch (PayPalConnectionException $ex) {
            //$ex->getData();
            $pmessageError = 'Erro ao criar o pagamento entre em contato com o suporte!!!';
            return $ex->getData();
        }
    }

    /**
     * @return Payer
     */
    protected function Payer(): Payer
    {
        $objPayer = new Payer();

        $objPayer->setPaymentMethod('paypal');
        //$objPayer->setPaymentMethod('credit_card');

        return $objPayer;
    }

    /**
     * @return Details
     */
    protected function Details(): Details
    {
        $objDetails = new Details();

        //Valor do imposto
        $objDetails->setTax($this->taxPricePayment)
            //Valor do frete
            ->setShipping($this->shippingPricePayment)
            //Valor do disconto do frete
            ->setShippingDiscount($this->shippingDiscountPricePayment)
            //Valor do seguro
            ->setInsurance($this->insurancePricePayment)
            //Valor da taxa de manuseio
            ->setHandlingFee($this->handlingFeePricePayment)
            //Valor total dos itens
            ->setSubtotal($this->subTotalPayment);

        return $objDetails;
    }

    /**
     * @return Amount
     */
    protected function Amount(): Amount
    {
        $objAmount = new Amount();

        $totalPayment = (
            $this->taxPricePayment+
            $this->shippingPricePayment+
            (-$this->shippingDiscountPricePayment)+
            $this->insurancePricePayment+
            $this->handlingFeePricePayment+
            $this->subTotalPayment
        );

        $objAmount->setCurrency($this->currencyPayment)

            ->setTotal($totalPayment)

            ->setDetails($this->Details());

        return $objAmount;
    }

    /**
     * @param $pItemList
     * @return Transaction
     */
    protected function Transaction($pItemList): Transaction
    {
        $objTransaction = new Transaction();

        $objTransaction->setAmount($this->Amount())

            ->setItemList($pItemList)

            ->setDescription($this->descriptionPayment)

            ->setInvoiceNumber(uniqid());

        return $objTransaction; //Id da transação
    }

    /**
     * @return RedirectUrls
     */
    protected function RedirectUrls(): RedirectUrls
    {
        $objRedirectUrls = new RedirectUrls();

        $objRedirectUrls->setReturnUrl( $this->payPalConfig['url']['redirect'] )

            ->setCancelUrl( $this->payPalConfig['url']['cancel'] );

        return $objRedirectUrls;
    }

    /**
     * @param $pItemList
     * @return Payment
     */
    protected function Payment( $pItemList ): Payment
    {
        $objPayment = new Payment();

        $objPayment->setIntent("sale")

            ->setPayer($this->Payer())

            ->setRedirectUrls($this->RedirectUrls())

            ->setTransactions(array($this->Transaction($pItemList)));

        return $objPayment;
    }
}
