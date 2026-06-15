<?php

namespace App\Console\Commands;

use App\Models\Installment;
use Illuminate\Console\Command;

class MarkOverdueInstallments extends Command
{
    protected $signature = 'bnpl:mark-overdue';

    protected $description = 'Mark overdue installments';

    public function handle(): int
    {
        $count = Installment::query()
            ->where('status', 'pending')
            ->whereDate('due_date', '<', now())
            ->update([
                'status' => 'overdue'
            ]);

        $this->info("Marked {$count} installments as overdue");

        return self::SUCCESS;
    }
}