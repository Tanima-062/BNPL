<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $fillable = [
        'merchant_id',
        'total_amount',
        'currency',
        'installments_count',
        'paid_amount',
        'outstanding_amount',
        'status'
    ];

    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(Ledger::class);
    }
}