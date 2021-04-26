<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    protected $table = 'usersadm';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setCpfAttribute($value)
    {
        $this->attributes['cpf'] = preg_replace("/\D/", '', $value);
    }

    public function setFoneAttribute($value)
    {
        $this->attributes['fone'] = preg_replace('/\D+/', '', $value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function pesqTypeUserAdm()
    {
        return $this->belongsTo(mdTpUsersAdm::class, 'tpuser', 'id');
    }

    public function allStoresByUserAdm()
    {
        return $this->belongsToMany( mdStores::class, 'rel_stores_usersadm', 'useradm', 'store');
    }

    public static function pesqUserAdmByStore($store)
    {
        //return $this->belongsToMany( mdStores::class, 'rel_stores_categoriesproduct', 'category_product', 'store');

        $usersAdm = mdRelStoresUsersAdm::where('store', $store)->get();

        return $usersAdm;
    }
}
