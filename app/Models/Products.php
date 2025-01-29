<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Products extends Model
{
    use HasFactory;

    protected $fillable = ['productname_id','business_id','price','tax_id','image','status','description_id'];

    /*protected $casts = [
        'inventories' => 'array',
        'kitchens' => 'array',
        'categories' => 'array',
    ];*/
    
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class); 
    }

    public function productname(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Taxes::class);
    }


    

    public function description(): BelongsTo
    {
        return $this->belongsTo(Description::class);
    }


    public function productnames(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }

    public function taxes(): BelongsTo
    {
        return $this->belongsTo(Taxes::class);
    }


    public function businesses(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class)->leftJoin('products_names', 'products_names.id', '=', 'categories.categoryname_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categories::class, 'product_categories', 'product_id', 'category_id');
    }


    public function kitchen(): BelongsTo
    {
        return $this->belongsTo(Kitchens::class)->leftJoin('Kitchen_names', 'Kitchen_names.id', '=', 'Kitchens.Kitchenname_id');
    }

    public function kitchens(): BelongsToMany
    {
        return $this->belongsToMany(Kitchens::class, 'product_kitchens', 'product_id', 'kitchen_id');
    }


    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tags::class)->leftJoin('products_names', 'products_names.id', '=', 'tags.tagname_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tags::class, 'product_tags', 'product_id', 'tag_id');
    }


    public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(Stocks::class, 'product_stocks', 'product_id', 'stock_id');
    }
    


    /*public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(Inventories::class, 'product_inventories', 'product_id', 'inventory_id');
    }*/


   /* public function stocks(): BelongsToMany
    {
        return $this->belongsToMany(Stocks::class)->leftJoin('inventory_names', 'inventory_names.id', '=', 'stocks.inventory_id');
    }*/    


    public function productpriceAll(): HasMany
    {
        return $this->hasMany( ProductPrice::class,'product_id','id')->where('day', 'All');
    }
    public function productpriceMonday(): HasMany
    {
        return $this->hasMany( ProductPrice::class,'product_id','id')->where('day', 'Monday');
    }
    public function productpriceTuesday(): HasMany
    {
        return $this->hasMany( ProductPrice::class,'product_id','id')->where('day', 'Tuesday');
    }
    public function productpriceWednesday(): HasMany
    {
        return $this->hasMany( ProductPrice::class,'product_id','id')->where('day', 'Wednesday');
    }
    public function productpriceThursday(): HasMany
    {
        return $this->hasMany( ProductPrice::class,'product_id','id')->where('day', 'Thursday');
    }
    public function productpriceFriday(): HasMany
    {
        return $this->hasMany( ProductPrice::class,'product_id','id')->where('day', 'Friday');
    }
    public function productpriceSaturday(): HasMany
    {
        return $this->hasMany( ProductPrice::class,'product_id','id')->where('day', 'Saturday');
    }
    public function productpriceSunday(): HasMany
    {
        return $this->hasMany( ProductPrice::class,'product_id','id')->where('day', 'Sunday');
    }


    public function productstatusAll(): HasMany
    {
        return $this->hasMany( ProductStatus::class,'product_id','id')->where('day', 'All');
    }
    public function productstatusMonday(): HasMany
    {
        return $this->hasMany( ProductStatus::class,'product_id','id')->where('day', 'Monday');
    }
    public function productstatusTuesday(): HasMany
    {
        return $this->hasMany( ProductStatus::class,'product_id','id')->where('day', 'Tuesday');
    }
    public function productstatusWednesday(): HasMany
    {
        return $this->hasMany( ProductStatus::class,'product_id','id')->where('day', 'Wednesday');
    }
    public function productstatusThursday(): HasMany
    {
        return $this->hasMany( ProductStatus::class,'product_id','id')->where('day', 'Thursday');
    }
    public function productstatusFriday(): HasMany
    {
        return $this->hasMany( ProductStatus::class,'product_id','id')->where('day', 'Friday');
    }
    public function productstatusSaturday(): HasMany
    {
        return $this->hasMany( ProductStatus::class,'product_id','id')->where('day', 'Saturday');
    }
    public function productstatusSunday(): HasMany
    {
        return $this->hasMany( ProductStatus::class,'product_id','id')->where('day', 'Sunday');
    }


}
