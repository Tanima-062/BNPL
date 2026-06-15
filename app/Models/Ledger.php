<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $fillable = [
        'merchant_id',
        'purchase_id',
        'payment_id',
        'entry_type',
        'amount',
        'description'
    ];
}