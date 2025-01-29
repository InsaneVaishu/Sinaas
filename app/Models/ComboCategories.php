<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComboCategories extends Model
{
    use HasFactory; 

    public $timestamps = false;

    protected $fillable = ['categoryname_id','status'];
    
    public function categoryname(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }    

    

}
