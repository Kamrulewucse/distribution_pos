<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    protected $fillable = [
        'name','purchase_product_category_id','purchase_product_sub_category_id', 'code', 'description', 'status'
    ];
    public function productType(){
        return $this->belongsTo(ProductType::class,'product_type_id');
    }
    public function category(){
        return $this->belongsTo(PurchaseProductCategory::class,'purchase_product_category_id');
    }
    public function subcategory(){
        return $this->belongsTo(PurchaseProductSubCategory::class,'purchase_product_sub_category_id');
    }

    public function inventory()
    {
        return $this->hasOne(PurchaseInventory::class,'purchase_product_id','id');
    }
}
