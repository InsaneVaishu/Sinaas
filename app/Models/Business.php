<?php

namespace App\Models;

use App\Models\BusinessWorking;
use App\Models\BusinessDelivery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Business extends Model
{
    use HasFactory;

    protected $fillable = ['name','email','phone','website','address','billing_address','shipping_address','status','image','currency','opening_hours','country_id','delivery_hours','time_zone','latitude','longitude'];

   /* protected $casts = [ 
        'backorder' => 'boolean',
    ];*/ 
      
    

    public function businessWorkingMonday(): HasMany
    {
        return $this->hasMany( BusinessWorking::class)->where('day', 'Monday');
        //return BusinessWorking::where('day', 'Monday')->get();
    }
    public function businessWorkingTuesday(): HasMany
    {
        return $this->hasMany( BusinessWorking::class)->where('day', 'Tuesday');
    }
    public function businessWorkingWednesday(): HasMany
    {
        return $this->hasMany( BusinessWorking::class)->where('day', 'Wednesday');
    }
    public function businessWorkingThursday(): HasMany
    {
        return $this->hasMany( BusinessWorking::class)->where('day', 'Thursday');
    }
    public function businessWorkingFriday(): HasMany
    {
        return $this->hasMany( BusinessWorking::class)->where('day', 'Friday');
    }
    public function businessWorkingSaturday(): HasMany
    {
        return $this->hasMany( BusinessWorking::class)->where('day', 'Saturday');
    }
    public function businessWorkingSunday(): HasMany
    {
        return $this->hasMany( BusinessWorking::class)->where('day', 'Sunday');
    }

    // DELIVERY HOUR

    public function businessDeliveryMonday(): HasMany
    {
        return $this->hasMany( BusinessDelivery::class)->where('day', 'Monday');
    }
    public function businessDeliveryTuesday(): HasMany
    {
        return $this->hasMany( BusinessDelivery::class)->where('day', 'Tuesday');
    }
    public function businessDeliveryWednesday(): HasMany
    {
        return $this->hasMany( BusinessDelivery::class)->where('day', 'Wednesday');
    }
    public function businessDeliveryThursday(): HasMany
    {
        return $this->hasMany( BusinessDelivery::class)->where('day', 'Thursday');
    }
    public function businessDeliveryFriday(): HasMany
    {
        return $this->hasMany( BusinessDelivery::class)->where('day', 'Friday');
    }
    public function businessDeliverySaturday(): HasMany
    {
        return $this->hasMany( BusinessDelivery::class)->where('day', 'Saturday');
    }
    public function businessDeliverySunday(): HasMany
    {
        return $this->hasMany( BusinessDelivery::class)->where('day', 'Sunday');
    }


    public function getAddressAttribute($value)
    {
        return $value ?? '';
    }
    public function getWebsiteAttribute($value)
    {
        return $value ?? '';
    }
    public function getBillingAddressAttribute($value)
    {
        return $value ?? '';
    }
    public function getShippingAddressAttribute($value)
    {
        return $value ?? '';
    }
    public function getTimeZoneAttribute($value)
    {
        return $value ?? '';
    }
    public function getOpeningHoursAttribute($value)
    {
        return $value ?? [];
    }
    public function getDeliveryHoursAttribute($value)
    {
        return $value ?? [];
    }
    public function getTaxIdAttribute($value)
    {
        return $value ?? '';
    }
    public function getBusinessDefaultAttribute($value)
    {
        return $value ?? '';
    }
}
