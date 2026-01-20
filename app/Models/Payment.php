<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'midtrans_order_id',
        'amount',
        'status',
        'qris_url',
        'transaction_time',
        'settlement_time',
        'refund_proof',
        'refund_reason',
        'refund_time',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
