<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Notifications\Notifiable;


class Customer extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    
    protected $fillable = ['first_name','last_name','email','phone','image','address','date_of_birth','password','latitude','longitude','country_id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFirstNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getLastNameAttribute($value)
    {
        return $value ?? '';
    }
    public function getAddressAttribute($value)
    {
        return $value ?? '';
    }

    
}
