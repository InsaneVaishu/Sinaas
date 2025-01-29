<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Staffs extends Model
{
    use HasFactory;
 
    public $timestamps = false;

    protected $fillable = ['first_name','last_name','email','phone','image','address','date_of_birth','password','latitude','longitude','country_id'];

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skills::class, 'staff_skills', 'staff_id', 'skill_id');
    }

    public function access(): BelongsTo
    {
        return $this->belongsTo(Access::class,'access_id','id');
    }
   /* public function payaccess(): BelongsTo
    {
        return $this->belongsTo(Access::class, 'id', 'staff_id');
    }
    public function dasaccess(): BelongsTo
    {
        return $this->belongsTo(Access::class, 'id', 'staff_id');
    }*/
    
}
