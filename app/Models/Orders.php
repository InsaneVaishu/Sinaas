<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;

    //protected $table = 'my_flights';

    //public $timestamps = false;

    protected $fillable = ['language_id','business_id','user_id','order_time','order_type','order_total','order_currency','order_address','order_geo','order_note','order_alert','payment_type','payment_status','order_status_id','status','order_added'];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Languages::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currencies::class,'order_currency');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatuses::class,'order_status_id');
    }

    public function OrderProducts(): HasMany
    {
        return $this->hasMany(OrderProducts::class,'order_id');
        //return BusinessWorking::where('day', 'Monday')->get();
    }

    public function products(): BelongsTo
    {
        return $this->belongsTo(Products::class)->leftJoin('products_names', 'products_names.id', '=', 'products.productname_id')->select('"products_names.name AS name"', 'products.id')->orderBy('products_names.name');
    }   

}
