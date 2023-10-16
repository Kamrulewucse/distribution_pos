<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrProductAssignOrderItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function srAssignOrder()
    {
        return $this->belongsTo(SrProductAssignOrder::class);
    }
    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class);
    }
    public function productModel()
    {
        return $this->belongsTo(ProductModel::class);
    }
}
