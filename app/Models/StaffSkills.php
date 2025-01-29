<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSkills extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'staff_id';

    protected $fillable = ['staff_id','skill_id','start_time','end_time','rating'];
}
