<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessBilling extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "business_billing";
    
    protected $fillable = ['business_id','name','email','phone','address','tax_id','country_id']; 
}
