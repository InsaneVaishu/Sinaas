<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ImageNames extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
       // 'tags' => 'array'
    ];
    
    protected $fillable = ['name','tags_id','image'];



    public function tags(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }

    public function productnames(): BelongsToMany
    {
        return $this->belongsToMany(ProductsNames::class, 'image_tags', 'image_id', 'name_id');
    }
}
