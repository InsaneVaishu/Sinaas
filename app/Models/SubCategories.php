<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCategories extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['category_id','subcategoryname_id','subcategory_image','status'];



    public function subcategoryname(): BelongsTo
    {
        return $this->belongsTo(ProductsNames::class);
    }   


    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class)->leftJoin('products_names', 'products_names.id', '=', 'categories.categoryname_id');

        //->select('products_names.name AS name')

        /*$query
        ->join('periods', 'periods.id', '=', 'students.period_id')
        ->orderBy('period.year', $direction)
        ->orderBy('period.month', $direction);*/

    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Categories::class)->select('products_names.name AS name')->join('products_names', 'products_names.id', '=', 'categories.categoryname_id');


        

        //->select('products_names.name AS name')

        /*$query
        ->join('periods', 'periods.id', '=', 'students.period_id')
        ->orderBy('period.year', $direction)
        ->orderBy('period.month', $direction);*/

    }

}
