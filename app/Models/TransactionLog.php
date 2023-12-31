<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    protected $fillable = [
        'date', 'particular', 'transaction_type', 'transaction_method', 'account_head_type_id',
        'account_head_sub_type_id', 'bank_id', 'branch_id', 'bank_account_id', 'cheque_no',
        'cheque_image', 'amount', 'note', 'purchase_payment_id', 'sales_order_id',
        'transaction_id', 'balance_transfer_id'
    ];

    protected $dates = ['date'];

    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function account() {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }

    public function accountHead() {
        return $this->belongsTo(AccountHeadType::class, 'account_head_type_id', 'id');
    }

    public function accountSubHead() {
        return $this->belongsTo(AccountHeadSubType::class, 'account_head_sub_type_id', 'id');
    }
}
