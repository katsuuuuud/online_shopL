<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'order_id',
        'invoice_id',
        'epay_transaction_id',
        'reference',
        'approval_code',
        'amount',
        'currency',
        'status',
        'card_mask',
        'card_type',
        'card_id',
        'phone',
        'email',
        'amount_bonus',
        'paid_at',
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'amount'       => 'decimal:2',
        'amount_bonus' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'orderId');
    }

    public function logs()
    {
        return $this->hasMany(TransactionLog::class);
    }
}
