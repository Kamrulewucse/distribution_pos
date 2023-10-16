<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model
{
    protected $guarded = [];


    public function productType() {
        return $this->belongsTo(ProductType::class);
    }
}
