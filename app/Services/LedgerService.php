<?php

namespace App\Services;
use App\Models\Ledger;
class LedgerService
{
    public function credit($purchaseId, $amount)
    {
        Ledger::create([
            'purchase_id' => $purchaseId,
            'amount' => $amount,
            'type' => 'CREDIT'
        ]);
    }
}