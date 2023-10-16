<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransferOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['date'];

    public function PurchaseInventory() {
        return $this->hasMany(PurchaseInventory::class,'stock_transfer_order_id','id');
    }

    public function shop() {
        return $this->belongsTo(Shop::class);
    }

}
