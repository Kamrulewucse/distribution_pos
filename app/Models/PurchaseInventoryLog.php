<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseInventoryLog extends Model
{
    protected $guarded = [];

    protected $dates = ['date'];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function product() {
        return $this->belongsTo(ProductModel::class, 'product_model_id', 'id');
    }

    public function order() {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'id');
    }

    public function products() {
        return $this->belongsToMany(ProductModel::class)
            ->withPivot('id', 'name','product_brand_id','product_model_id',
                'serial', 'product_color_id', 'quantity', 'unit_price', 'total');
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }

}
