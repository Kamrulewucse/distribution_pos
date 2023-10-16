<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountHeadType extends Model
{
    protected $fillable = [
        'name', 'transaction_type', 'status'
    ];
}
