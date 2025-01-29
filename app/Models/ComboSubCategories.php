<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ComboSubCategories extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['subcategoryname_id','category_id','status'];
    
    public function subcategoryname(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }    

    public function category(): BelongsTo
    {
        return $this->belongsTo(ComboCategories::class);
    }





    public function subcategorynames(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }

    public function categories(): BelongsTo
    {
        return $this->belongsTo(ComboCategories::class)->leftJoin('products_names', 'products_names.id', '=', 'combo_categories.id')->select("products_names.name AS name");
    }


}
