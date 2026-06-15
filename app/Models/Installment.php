<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Installment extends Model
{
    protected $fillable = [
        'purchase_id',
        'sequence',
        'amount',
        'due_date',
        'status',
        'paid_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime'
    ];

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}