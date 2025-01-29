<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStatus extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['product_id','day','status_type','status','start_time','end_time'];
}
