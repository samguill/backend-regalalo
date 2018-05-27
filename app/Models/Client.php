<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'first_name', 'last_name' ,'email', 'password', 'status','phone'
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value) {
        $this->attributes['password'] = \Hash::make($value);
    }
    
    public function directions(){
        return $this->hasMany('App\Models\ClientDirection', 'client_id', 'id');
    }

    public function wishlist(){
        return $this->hasMany('App\Models\ClientDirection', 'client_id', 'id');
    }
}
