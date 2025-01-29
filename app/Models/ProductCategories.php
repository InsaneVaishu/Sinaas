<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'product_id';

    protected $fillable = ['product_id','category_id'];
}
