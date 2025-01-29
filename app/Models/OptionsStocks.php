<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionsStocks extends Model
{
    use HasFactory;
 
    public $timestamps = false; 

    protected $table = 'option_stocks';

    protected $fillable = ['stock_id','option_id','stock_price','stock_deduction'];
}
