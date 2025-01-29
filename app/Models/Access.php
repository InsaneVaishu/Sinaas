<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Access extends Model
{
    use HasFactory;

    protected $table = "access";

    public $timestamps = false;

    protected $primaryKey = 'staff_id';

    protected $fillable = ['staff_id','app_cashier','app_waiter','app_driverr','pay_payment','pay_cash_payment','pay_refund','pay_discount','das_menu','das_orders','das_staff','das_system','das_shop','das_menu_edit','das_orders_edit','das_staff_edit','das_system_edit','das_shop_edit'];


    public function staffs(): BelongsTo
    {
        return $this->belongsTo(Access::class,'staff_id','id');
    }
    
}
