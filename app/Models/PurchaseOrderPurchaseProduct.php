<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderPurchaseProduct extends Model
{
    protected $guarded = [];
    protected $table = 'purchase_order_purchase_product';

//    public function prod(){
//        return $this->belongsTo(PurchaseProduct::class,'purchase_product_id','id');
//    }
//    public function category(){
//        return $this->belongsTo(PurchaseProductCategory::class,'purchase_product_category_id','id');
//    }
//    public function subcategory(){
//        return $this->belongsTo(PurchaseProductSubCategory::class,'purchase_product_sub_category_id','id');
//    }

    public function productType() {
        return $this->belongsTo(ProductType::class);
    }

    public function productColor() {
        return $this->belongsTo(ProductColor::class);
    }
    public function productBrand() {
        return $this->belongsTo(ProductBrand::class);
    }
    public function productModel() {
        return $this->belongsTo(ProductModel::class);
    }

    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }


}
