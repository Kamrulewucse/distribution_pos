<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInventory extends Model
{
    protected $guarded = [];


    public function purchaseQty($product,$warehouse)
    {
        $purchase = PurchaseInventoryLog::where('type',1)
            ->where('purchase_product_id',$product)
           // ->where('warehouse_id',$warehouse)
            ->sum('quantity');

        $purchaseReturn = PurchaseInventoryLog::where('type',3)
            ->where('purchase_product_id',$product)
            //->where('warehouse_id',$warehouse)
            ->sum('quantity');
        return $purchase - $purchaseReturn;
    }
    public function saleQty($product,$warehouse)
    {

        $sale = PurchaseInventoryLog::where('type',2)
            ->where('purchase_product_id',$product)
           // ->where('warehouse_id',$warehouse)
            ->sum('quantity');
        $saleReturn = PurchaseInventoryLog::where('type',4)
            ->where('purchase_product_id',$product)
            //->where('warehouse_id',$warehouse)
            ->sum('quantity');

        return $sale - $saleReturn;
    }
    public function product() {
        return $this->belongsTo(ProductModel::class, 'product_model_id', 'id');
    }
    public function productModel() {
        return $this->belongsTo(ProductModel::class, 'product_model_id', 'id');
    }
    public function color() {
        return $this->belongsTo(ProductColor::class, 'product_color_id', 'id');
    }
    public function productType() {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'id');
    }
    public function brand() {
        return $this->belongsTo(ProductBrand::class,'product_brand_id','id');
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

}
