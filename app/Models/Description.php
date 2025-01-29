<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['description','product_id','description_en','description_es'];
}
