<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'order_no', 'supplier_id', 'warehouse_id', 'date', 'total',
    ];

    protected $dates = ['date'];

    public function products() {
        return $this->belongsToMany(ProductModel::class)
            ->withPivot('id', 'name',  'serial_no', 'quantity',
                'unit_price', 'selling_price', 'total');
    }

    public function order_products() {
        return $this->hasMany(PurchaseOrderPurchaseProduct::class,'purchase_order_id','id');
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    public function payments() {
        return $this->hasMany(PurchasePayment::class);
    }

    public function purchase_order_products(){
        return $this->hasMany(PurchaseOrderPurchaseProduct::class, 'purchase_order_id');
    }

}
