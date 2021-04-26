<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdStoresPayment extends Model
{
    protected $table = 'stores_payment';

    public function pesqPaymentKeys()
    {
        return $this->hasMany(mdStoresPaymentKeys::class, 'type_payment_system', 'id');
    }
}
