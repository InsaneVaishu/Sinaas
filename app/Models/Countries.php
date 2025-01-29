<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Countries extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','iso_code_2','iso_code_3','address_format_id','postcode_required','status'];  


}
