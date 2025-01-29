<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tags extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['tagname_id','tag_image','status'];

    
    public function tagnames(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }

    public function tagname(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }


}
