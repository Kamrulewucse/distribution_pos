<?php

namespace App\Models;

use App\Models\ProductBrand;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class SalesRepresentative extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class,'');
    }

//    public function brandName(){
//        return $this->belongsTo(ProductBrand::class,'brand','id');
//    }

}
