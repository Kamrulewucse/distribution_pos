<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseProductCategory extends Model
{
    protected $fillable = [
        'color_name', 'status'
    ];

    public function productType() {
        return $this->belongsTo(ProductType::class);
    }
}
