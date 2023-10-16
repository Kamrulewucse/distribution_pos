<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $guarded = [];

    public function productType(){
        return $this->belongsTo(ProductType::class,'product_type_id');
    }
    public function brand(){
        return $this->belongsTo(ProductBrand::class,'product_brand_id');
    }
    public function color(){
        return $this->belongsTo(ProductColor::class,'product_color_id','id');
    }

}
