<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTax extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['product_id','tax_percentage','tax_rate','type_name','tax_type'];
}
