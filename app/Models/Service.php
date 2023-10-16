<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'sales_order_id', 'name', 'quantity', 'unit_price', 'total'
    ];
}
