<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            // 1. Organizational Control
            $table->foreignId('collation_center_id')->constrained()->restrictOnDelete();
            $table->foreignId('client_id')->constrained()->restrictOnDelete();
            $table->foreignId('loan_product_id')->constrained();
            $table->foreignId('guarantor_id')->nullable()->constrained('guarantors');
            // 2. Financial Core
            $table->decimal('amount', 15, 2); // Requested Principal
            $table->decimal('interest_rate', 5, 2); // 10, 20, or 30
            $table->integer('duration_months'); // Logic handles Daily/Weekly based on Product Name
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // 3. Step 7 Requirements: Business & Financial Information
            $table->string('business_name')->nullable();
            $table->decimal('monthly_revenue', 15, 2)->default(0);
            $table->decimal('monthly_expenses', 15, 2)->default(0);
            $table->decimal('assets_value', 15, 2)->default(0);
            $table->decimal('liabilities_value', 15, 2)->default(0);

            // 4. Step 7 Requirements: Proposals & Guarantor Analysis
            $table->text('proposal_summary')->nullable(); // Staff writes this
            $table->text('guarantor_business_info')->nullable(); // Guarantor financial summary

            // 5. Requirement 8: Approval Loop & Adjustment
            $table->enum('status', ['pending', 'approved', 'rejected', 'active', 'completed', 'defaulted'])
                ->default('pending');
            $table->enum('approval_status', ['pending_review', 'adjustment_needed', 'under_review', 'approved'])
                ->default('pending_review');
            $table->text('review_notes')->nullable(); // Admin feedback for Staff

            // 6. Oversight
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->timestamps();

            // Indexes for Performance at Scale
            $table->index(['status', 'approval_status']);
            $table->index('collation_center_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};