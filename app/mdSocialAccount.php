<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class mdSocialAccount extends Model
{
    protected $table = 'socialmediasuserssite';

    protected $fillable = [
        'user_site_id',
        'provider_name',
        'provider_id',
        'email'
    ];

    public function pesqUserSite()
    {
        return $this->belongsTo(UserSite::class, 'user_site_id', 'id');
    }
}
