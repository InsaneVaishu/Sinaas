<?php

namespace App\Models;

use languages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ProductsNames extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','name_en','name_es'];


    public function languages(): HasMany
    {
        return $this->hasMany( Languages::class);
        //return BusinessWorking::where('day', 'Monday')->get();
    }
    
}
