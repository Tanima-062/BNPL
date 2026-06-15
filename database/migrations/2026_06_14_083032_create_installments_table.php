<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\InstallmentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('sequence');

            $table->bigInteger('amount');

            $table->date('due_date');

            $table->string('status')
                ->default(InstallmentStatus::PENDING->value);

            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
