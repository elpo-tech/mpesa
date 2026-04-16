<?php
// app/Models/MpesaTransaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'phone',
        'amount',
        'merchant_request_id',
        'checkout_request_id',
        'mpesa_receipt',
        'status',
        'result_code',
        'result_desc',
    ];
}
