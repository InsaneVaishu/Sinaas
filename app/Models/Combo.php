<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Combo extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['product_id','category_id','min','max','status'];
    
    public function products(): BelongsTo
    {
        return $this->belongsTo(Products::class)->leftJoin('products_names', 'products_names.id', '=', 'products.id')->select('products_names.name AS name','products.id')->orderBy('products_names.name');
    }

    public function combosubcategories(): BelongsTo
    {
        return $this->belongsTo(ComboSubCategories::class)->leftJoin('products_names', 'products_names.id', '=', 'combo_sub_categories.subcategoryname_id')->select('products_names.name','combo_sub_categories.id')->orderBy('products_names.name');
    }

    public function combocategories(): BelongsTo
    {
        return $this->belongsTo(ComboCategories::class)->leftJoin('products_names', 'products_names.id', '=', 'combo_categories.categoryname_id')->select('products_names.name','combo_categories.id')->orderBy('products_names.name');
    }


    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class)->leftJoin('products_names', 'products_names.id', '=', 'products.id')->select('products_names.name AS name','products.id')->orderBy('products_names.name');
    }
    
    public function ProductsCombos(): HasMany
    {
        return $this->hasMany(ProductComboItems::class);
        //return $this->hasMany( BusinessWorking::class)->where('day', 'Monday');
        //return BusinessWorking::where('day', 'Monday')->get();
    }
}
