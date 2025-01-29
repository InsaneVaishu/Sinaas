<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComboItems extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['combo_id','combo_product_id','price','included'];     
}
