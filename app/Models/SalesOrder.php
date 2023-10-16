<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $guarded = [];

    protected $dates = ['date', 'next_payment'];

    public function products() {
        return $this->belongsToMany(ProductModel::class)
            ->withPivot('id', 'name','product_brand_id','product_model_id',
                'serial', 'product_color_id', 'quantity', 'unit_price', 'total');
    }

    public function payments() {
        return $this->hasMany(SalePayment::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }


    public function user(){
        return $this->belongsTo('App\Models\User', 'created_by');
    }
}
