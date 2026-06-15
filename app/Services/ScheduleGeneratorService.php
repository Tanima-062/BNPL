<?php

namespace App\Services;

use Carbon\Carbon;

class ScheduleGeneratorService
{
    /**
     * Generate equal installment schedule with rounding safety
     */
    public function generate(int $totalCents, int $count): array
    {
        $base = intdiv($totalCents, $count);
        $remainder = $totalCents % $count;

        $schedule = [];
        $today = Carbon::today();

        for ($i = 1; $i <= $count; $i++) {

            $amount = $base;

            // push remainder to last installment
            if ($i === $count) {
                $amount += $remainder;
            }

            $schedule[] = [
                'sequence' => $i,
                'amount' => $amount,
                'due_date' => $today->copy()->addMonths($i),
            ];
        }

        return $schedule;
    }
}