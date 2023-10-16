<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountHeadSubType extends Model
{
    protected $fillable = [
        'account_head_type_id', 'name', 'status'
    ];

    public function accountHeadType() {
        return $this->belongsTo(AccountHeadType::class);
    }
}
