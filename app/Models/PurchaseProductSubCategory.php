<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseProductSubCategory extends Model
{
    protected $fillable = [
        'name','purchase_product_category_id','status'
    ];

    public function category()
    {
        return $this->belongsTo(PurchaseProductCategory::class,'purchase_product_category_id','id');
    }
}
