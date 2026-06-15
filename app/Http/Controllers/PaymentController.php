<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Initiate a payment (NOT final)
     */
    public function initiate(Request $request)
    {
        $data = $request->validate([
            'installment_id' => ['required', 'exists:installments,id'],
            'idempotency_key' => ['required', 'string']
        ]);

    
        $payment = Payment::firstOrCreate(
            [
                'idempotency_key' => $data['idempotency_key']
            ],
            [
                'installment_id' => $data['installment_id'],
                'amount' => 0, 
                'status' => 'pending',
                'provider_reference' => Str::uuid()->toString()
            ]
        );

        return response()->json([
            'payment_id' => $payment->id,
            'status' => $payment->status,
            'provider_reference' => $payment->provider_reference
        ]);
    }
}