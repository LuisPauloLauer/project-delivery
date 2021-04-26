<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserSite extends Model
{
    protected $table = 'userssite';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setFoneAttribute($value)
    {
        $this->attributes['fone'] = preg_replace('/\D+/', '', $value);
    }

    public function allSocialAccountsByUserSite()
    {
        return $this->hasMany(mdSocialAccount::class);
    }
}
