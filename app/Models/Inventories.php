<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventories extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['inventoryname_id','inventory_image','status'];


    public function inventorynames(): BelongsTo
    {
        return $this->belongsTo(InventoryNames::class);
    }

    public function inventoryname(): BelongsTo
    {
        return $this->belongsTo(InventoryNames::class);
    }
}
