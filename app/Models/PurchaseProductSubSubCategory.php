<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseProductSubSubCategory extends Model
{
    protected $fillable = [
        'name','purchase_product_category_id','purchase_product_sub_category_id','status'
    ];

    public function category()
    {
        return $this->belongsTo(PurchaseProductCategory::class,'purchase_product_category_id','id');
    }
    public function subcategory()
    {
        return $this->belongsTo(PurchaseProductSubCategory::class,'purchase_product_sub_category_id','id');
    }
}
