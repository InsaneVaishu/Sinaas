<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = ['categoryname_id','business_id','category_image','categorybg_image','status'];


    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function categoryname(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }


    public function productnames(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }


    public function businesses(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
    
}
