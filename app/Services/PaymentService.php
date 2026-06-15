<?php

namespace App\Services;
use App\Models\Payment;
use App\Models\Installment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function initiate($installmentId, $key)
    {
        return DB::transaction(function () use ($installmentId, $key) {

            $existing = Payment::where('idempotency_key', $key)->first();
            if ($existing) return $existing;

            return Payment::create([
                'installment_id' => $installmentId,
                'idempotency_key' => $key,
                'amount' => Installment::find($installmentId)->amount,
                'status' => 'pending',
            ]);
        });
    }
}