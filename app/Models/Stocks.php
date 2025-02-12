<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stocks extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name_id','inventory_id','business_id','image','unit_id', 'buy_price', 'quantity', 'quantity_alert' ,'status'];

    public function businesses(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventories::class)->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id');
    }


    public function inventorynames(): BelongsTo
    {
        return $this->belongsTo(Inventories::class)->select("inventory_names.name AS name")->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id');
    }
    
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
    
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Units::class);
    }

}
