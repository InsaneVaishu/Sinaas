<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesNames extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name','name_en','name_es'];
}
