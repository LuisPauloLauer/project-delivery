<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdDemandsItensFood extends Model
{
    protected $table = 'demands_itens_food';

    public function getAmountAttribute()
    {
        return round($this->attributes['amount'], 4);
    }
}
