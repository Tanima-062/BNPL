<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\WebhookEvent;
use App\Models\Installment;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

class WebhookService
{
    public function process(array $payload)
    {
        /**
         * Expected payload:
         * {
         *   event_id,
         *   type: payment.confirmed | payment.failed,
         *   payment_id
         * }
         */

        return DB::transaction(function () use ($payload) {

            // 1. Idempotency guard (event level)
            $event = WebhookEvent::firstOrCreate(
                ['event_id' => $payload['event_id']],
                [
                    'event_type' => $payload['type'],
                    'payment_id' => $payload['payment_id'],
                    'payload' => $payload
                ]
            );

            if ($event->processed_at) {
                return ['status' => 'ignored (duplicate event)'];
            }

            $payment = Payment::lockForUpdate()->find($payload['payment_id']);

            if (!$payment) {
                return ['status' => 'payment not found'];
            }

            $installment = Installment::lockForUpdate()->find($payment->installment_id);

            /**
             * IMPORTANT RULE:
             * Webhook is SINGLE source of truth
             */

            if ($payload['type'] === 'payment.confirmed') {

                // already confirmed → ignore
                if ($payment->status === 'confirmed') {
                    $event->update(['processed_at' => now()]);
                    return ['status' => 'already confirmed'];
                }

                // apply confirmation
                $payment->update(['status' => 'confirmed']);

                $installment->update([
                    'status' => 'paid',
                    'paid_at' => now()
                ]);

                
                Ledger::create([
                    'merchant_id' => $installment->purchase->merchant_id,
                    'purchase_id' => $installment->purchase_id,
                    'payment_id' => $payment->id,
                    'entry_type' => 'credit',
                    'amount' => $payment->amount,
                    'description' => 'Installment paid'
                ]);

     
                $purchase = $installment->purchase;

                $purchase->increment('paid_amount', $payment->amount);
                $purchase->decrement('outstanding_amount', $payment->amount);

                if ($purchase->outstanding_amount <= 0) {
                    $purchase->update(['status' => 'paid']);
                }
            }

            if ($payload['type'] === 'payment.failed') {

                if ($payment->status === 'confirmed') {
                    $event->update(['processed_at' => now()]);
                    return ['status' => 'ignored (already confirmed)'];
                }

                $payment->update(['status' => 'failed']);
            }

            $event->update(['processed_at' => now()]);

            return ['status' => 'processed'];
        });
    }
}