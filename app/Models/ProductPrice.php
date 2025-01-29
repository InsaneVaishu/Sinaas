<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = ['product_id','day','price_type','price','start_time','end_time'];
}
