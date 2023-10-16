<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdatePrice extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function productModel() {
        return $this->belongsTo(ProductModel::class, 'product_model_id', 'id');
    }
}
