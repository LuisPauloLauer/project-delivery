<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 11/09/2020
 * Time: 15:26
 */

namespace App\Paypal;

use Illuminate\Support\Facades\Config;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PayPal
{
    protected $apiContext;
    protected $payPalConfig;

    public function __construct()
    {
        $this->payPalConfig = Config::get('paypal');

        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->payPalConfig['client_id'], //client_id
                $this->payPalConfig['secret']     //client_secret
            )
        );

        $this->apiContext->setConfig($this->payPalConfig['settings']);

    }
}