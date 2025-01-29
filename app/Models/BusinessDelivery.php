<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessDelivery extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['business_id','open_from','open_to','day'];
}
