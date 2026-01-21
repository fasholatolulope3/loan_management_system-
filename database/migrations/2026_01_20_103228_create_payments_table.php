<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->restrictOnDelete();
            // Inside create_payments_table migration
            $table->foreignId('schedule_id')->nullable()->constrained('loan_schedules')->onDelete('set null');
            $table->decimal('amount_paid', 15, 2);
            $table->timestamp('payment_date');
            $table->enum('method', ['cash', 'transfer', 'card', 'online']);
            $table->string('reference')->unique(); // Transaction Ref
            $table->foreignId('captured_by')->constrained('users');
            $table->timestamps();

            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
