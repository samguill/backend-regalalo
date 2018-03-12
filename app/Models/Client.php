<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'first_name', 'last_name' ,'email', 'password', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
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
