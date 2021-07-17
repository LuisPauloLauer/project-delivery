<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdDeliveryStoreTimes extends Model
{
    protected $table = 'deliverystoretimes';

    public function pesqDayOfWeek()
    {
        return $this->belongsTo(mdDaysOfWeek::class, 'day', 'id');
    }
}
