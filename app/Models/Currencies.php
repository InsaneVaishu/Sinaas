<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Currencies extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['country_id','name','code','right_symbol','left_symbol','default','value','status'];


    


}
