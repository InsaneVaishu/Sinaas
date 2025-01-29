<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customize extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $fillable = ['product_id','business_id','stock_id','price','stock_deduction','default_extra','max','status'];


    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stocks::class)->leftJoin('inventories', 'stocks.inventory_id', '=', 'inventories.id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Products::class)->leftJoin('products_names', 'products_names.id', '=', 'products.productname_id');
    }
    //->select('products_names.name AS name') 
    public function products(): BelongsTo
    {
        return $this->belongsTo(Products::class,'product_id')->leftJoin('products_names', 'products_names.id', '=', 'products.productname_id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }



    public function stocksname(): BelongsTo
    {
        return $this->belongsTo(Stocks::class)->leftJoin('customize', 'customize.stock_id', '=', 'inventories.id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id');
    }
    
    
}
