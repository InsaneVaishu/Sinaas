<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['order_id','product_id','name_id','stock_id','kitchen_id','price','quantity','total','tax_id','tax'];
}
