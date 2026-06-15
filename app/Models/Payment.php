<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'installment_id',
        'idempotency_key',
        'provider_reference',
        'amount',
        'status'
    ];

    protected $casts = [
        'amount' => 'integer'
    ];

    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }
}