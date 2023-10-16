<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wastage extends Model
{
    protected $guarded = [];
    protected $dates = ['created_at','date'];

    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function products(){
        return $this->hasMany(WastageProduct::class);
    }

    public function product(){
        return $this->belongsTo(PurchaseProduct::class);
    }

}
