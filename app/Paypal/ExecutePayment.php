<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 11/09/2020
 * Time: 16:48
 */

namespace App\Paypal;


use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

class ExecutePayment extends PayPal
{
    public function execute(&$messageError)
    {

        $paymentId = request('paymentId');
        $payerId = request('PayerID');
        $token = request('token');

        if (!$paymentId || !$payerId || !$token) {
            $messageError = 'Erro ao efetuar o pagamento, dados não foram preenchidos corretamente!!!';
            return false;
        }

        $payment = $this->GetThePayment();

        $execution = $this->CreateExecution();

        /** Execute the payment **/
        $result = $payment->execute($execution, $this->apiContext);

        return $result;
    }

    /**
     * @return Payment
     */
    protected function GetThePayment(): Payment
    {
        $paymentId = request('paymentId');
        $payment = Payment::get($paymentId, $this->apiContext);
        return $payment;
    }

    /**
     * @return PaymentExecution
     */
    protected function CreateExecution(): PaymentExecution
    {
        $execution = new PaymentExecution();
        $execution->setPayerId(request('PayerID'));
        return $execution;
    }
}