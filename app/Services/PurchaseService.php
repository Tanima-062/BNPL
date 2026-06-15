<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\Installment;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function __construct(
        private ScheduleGeneratorService $generator
    ) {}

    public function create(array $data): Purchase
    {
        return DB::transaction(function () use ($data) {

            $totalCents = Money::toCents($data['total_amount']);

            $schedule = $this->generator->generate(
                $totalCents,
                $data['installments_count']
            );

            $purchase = Purchase::create([
                'merchant_id' => $data['merchant_id'],
                'total_amount' => $totalCents,
                'currency' => $data['currency'],
                'installments_count' => $data['installments_count'],
                'paid_amount' => 0,
                'outstanding_amount' => $totalCents,
                'status' => 'active',
            ]);

            foreach ($schedule as $item) {
                Installment::create([
                    'purchase_id' => $purchase->id,
                    'sequence' => $item['sequence'],
                    'amount' => $item['amount'],
                    'due_date' => $item['due_date'],
                    'status' => 'pending',
                ]);
            }

            return $purchase->load('installments');
        });
    }
}