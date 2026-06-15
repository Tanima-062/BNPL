<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('idempotency_key')
                ->unique();

            $table->string('provider_reference')
                ->nullable();

            $table->bigInteger('amount');

            $table->string('status')
                ->default(PaymentStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
