<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class EarlySettlementController extends Controller
{
    public function initiate(Purchase $purchase)
{
    return DB::transaction(function () use ($purchase) {

        $installments = $purchase->installments()
            ->where('status', 'pending')
            ->lockForUpdate()
            ->get();

        if ($installments->isEmpty()) {
            return response()->json([
                'message' => 'Nothing to settle'
            ]);
        }

        $payments = [];

        foreach ($installments as $installment) {

            $payment = Payment::firstOrCreate(
                [
                    'idempotency_key' => 'settle-' . $installment->id
                ],
                [
                    'installment_id' => $installment->id,
                    'amount' => $installment->amount,
                    'status' => 'pending',
                    'provider_reference' => (string) Str::uuid()
                ]
            );

            $payments[] = $payment;
        }

        return response()->json([
            'message' => 'Early settlement initiated',
            'payments_created' => count($payments),
            'installments_count' => $installments->count()
        ]);
    });
}
}