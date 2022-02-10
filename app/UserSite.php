<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSite extends Authenticatable
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

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setFoneAttribute($value)
    {
        $this->attributes['fone'] = preg_replace('/\D+/', '', $value);
    }

    public function allSocialAccountsByUserSite()
    {
        return $this->hasMany(mdSocialAccount::class);
    }

    public function pesqUniversityBuilding()
    {
        return $this->belongsTo(mdUniversitybuildings::class, 'universitybuilding', 'id');
    }
}
