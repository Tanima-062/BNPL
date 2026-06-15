<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PurchaseStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('purchases', function (Blueprint $table) {
        $table->id();
        $table->foreignId('merchant_id')
                ->constrained()
                ->cascadeOnDelete();
        $table->bigInteger('total_amount');

        $table->string('currency', 10);

        $table->integer('installments_count');

        $table->bigInteger('paid_amount')
            ->default(0);

        $table->bigInteger('outstanding_amount');

        $table->string('status')
            ->default(PurchaseStatus::ACTIVE->value);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
