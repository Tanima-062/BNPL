<?php

namespace App\Services;

use Carbon\Carbon;

class InstallmentService
{
    public function generate(int $total, int $count): array
    {
        $base = intdiv($total, $count);
        $remainder = $total % $count;

        $installments = [];

        for ($i = 0; $i < $count; $i++) {
            $amount = $base;

            // distribute remainder cents
            if ($i < $remainder) {
                $amount += 1;
            }

            $installments[] = [
                'amount' => $amount,
                'due_date' => now()->addMonths($i + 1),
            ];
        }

        return $installments;
    }
}