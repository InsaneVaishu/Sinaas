<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','business_id','role_id','status'];
 
    
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function roles(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /*public function username(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->leftJoin('products_names', 'products_names.id', '=', 'tags.tagname_id');
    }*/
    
}
