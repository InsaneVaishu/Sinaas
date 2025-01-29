<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kitchens extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['kitchenname_id','status'];


    public function kitchennames(): BelongsTo
    {
        return $this->belongsTo(KitchenNames::class);
    }

    public function kitchenname(): BelongsTo
    {
        return $this->belongsTo(KitchenNames::class);
    }
}
