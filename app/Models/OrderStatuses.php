<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatuses extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','language_id','status'];
}
