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

    private $currencyPayment                = "BRL";    //Moeda de pagamento
    private $totalPayment                   = 0;        //Valor total da fatura (pedido)
    private $subTotalPayment                = 0;        //Valor total dos itens
    private $taxPricePayment                = 1;        //Valor do imposto
    private $shippingPricePayment           = 2;        //Valor do frete
    private $shippingDiscountPricePayment   = 1;        //Valor do disconto do frete
    private $insurancePricePayment          = 3;        //Valor do seguro
    private $handlingFeePricePayment        = 4;        //Valor da taxa de manuseio

    private $descriptionPayment             = "Teste Payment description"; //Descrição do pagamento

    public function create($pKits = null, $pProducts = null, &$pmessageError)
    {
        $countItens = 0;

        if($pKits){
            foreach($pKits as $Kit){
                for($i=0; $i < count( $Kit['productSellSubItems'] ); $i++){
                    if($Kit['productSellSubItems'][$i][$Kit['item']['id']]['qnty'] > 0 ){

                        $itens[$countItens] = new Item();

                        //dd('teste');

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

        /*
        $item1 = new Item();
        $item1->setName('Ground Coffee 40 oz')
            ->setCurrency('BRL')
            ->setQuantity(1)
            ->setSku("123123")// Similar to `item_number` in Classic API
            ->setPrice(15);

        $item2 = new Item();
        $item2->setName('Granola bars')
            ->setCurrency('BRL')
            ->setQuantity(5)
            ->setSku("321321")// Similar to `item_number` in Classic API
            ->setPrice(2); */

        $itemList = new ItemList();
        $itemList->setItems(
            $itens
        );

        $payment = $this->Payment($itemList);

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
        $payer = new Payer();

        $payer->setPaymentMethod('paypal');

        return $payer;
    }

    /**
     * @return Details
     */
    protected function Details(): Details
    {
        $details = new Details();

        //Valor do imposto
        $details->setTax($this->taxPricePayment)
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

        return $details;
    }

    /**
     * @return Amount
     */
    protected function Amount(): Amount
    {
        $amount = new Amount();

        $this->totalPayment = (
            $this->taxPricePayment+
            $this->shippingPricePayment+
            (-$this->shippingDiscountPricePayment)+
            $this->insurancePricePayment+
            $this->handlingFeePricePayment+
            $this->subTotalPayment
        );

        $amount->setCurrency($this->currencyPayment)

            ->setTotal($this->totalPayment)

            ->setDetails($this->Details());

        return $amount;
    }

    /**
     * @param $itemList
     * @return Transaction
     */
    protected function Transaction($itemList): Transaction
    {
        $transaction = new Transaction();

        $transaction->setAmount($this->Amount())

            ->setItemList($itemList)

            ->setDescription($this->descriptionPayment)

            ->setInvoiceNumber(uniqid());

        return $transaction; //Id da transação
    }

    /**
     * @return RedirectUrls
     */
    protected function RedirectUrls(): RedirectUrls
    {
        $redirectUrls = new RedirectUrls();

        $redirectUrls->setReturnUrl( $this->payPalConfig['url']['redirect'] )

            ->setCancelUrl( $this->payPalConfig['url']['cancel'] );

        return $redirectUrls;
    }

    /**
     * @param $itemList
     * @return Payment
     */
    protected function Payment( $itemList ): Payment
    {
        $payment = new Payment();

        $payment->setIntent("sale")

            ->setPayer($this->Payer())

            ->setRedirectUrls($this->RedirectUrls())

            ->setTransactions(array($this->Transaction($itemList)));

        return $payment;
    }
}