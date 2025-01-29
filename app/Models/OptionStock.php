<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OptionStock extends Model
{
    use HasFactory;


    public $timestamps = false;
    
    protected $fillable = ['option_id','stock_id','stock_price','stock_deduction','status'];


    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stocks::class)->leftJoin('inventories', 'stocks.inventory_id', '=', 'inventories.id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id');
    }

}
