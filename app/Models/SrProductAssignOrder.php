<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrProductAssignOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $dates = ['date'];

    public function sr()
    {
        return $this->belongsTo(SalesRepresentative::class,'sales_representative_id');
    }
    public function srAssignProductItem()
    {
        return $this->hasMany(SrProductAssignOrderItem::class);
    }
}
