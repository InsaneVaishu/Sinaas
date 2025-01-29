<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderOptions extends Model
{
    use HasFactory;    

    public $timestamps = false;

    protected $fillable = ['order_id','order_product_id','product_option_id','name_id','product_option_value_id','value','status'];

}
