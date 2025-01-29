<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Options extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['product_id','business_id','optionname_id','option_min','option_max','status'];

    public function optionname(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class,'optionname_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }


    /*public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class)->leftJoin('products_names', 'products_names.id', '=', 'products.productname_id');
    }    */

    public function products(): BelongsTo
    {
        return $this->belongsTo(Products::class)->select("products_names.name AS name")->leftJoin('products_names', 'products_names.id', '=', 'products.productname_id');
    }   

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class)->select("products_names.name AS name")->leftJoin('products_names', 'products_names.id', '=', 'products.productname_id');
    }

    public function stocks(): BelongsTo
    {
        return $this->belongsTo(Stocks::class)->leftJoin('inventories', 'inventories.id', '=', 'stocks.inventory_id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->select('inventory_names.name AS name', 'inventories.id')->orderBy('inventory_names.name'); 
    } 


    public function customizes(): BelongsTo
    {
        return $this->belongsTo(Customize::class)->leftJoin('products_names', 'products_names.id', '=', 'customize.product_id');
    }

    public function customize(): BelongsTo
    {
        return $this->belongsTo(Customize::class)->leftJoin('products', 'products.id', '=', 'customizes.customize_id')->leftJoin('products_names', 'products_names.id', '=', 'products.productname_id')->select('products_names.name', 'products.id')->orderBy('products_names.name');        
    }   
    
    public function optionsStocks(): HasMany
    {
        return $this->hasMany(OptionsStocks::class,'option_id');
        //return $this->hasMany( BusinessWorking::class)->where('day', 'Monday');
        //return BusinessWorking::where('day', 'Monday')->get();
    }


}
