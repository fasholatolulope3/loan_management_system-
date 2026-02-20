<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Applicant Information
            $table->string('residence_since')->nullable()->after('guarantor_id');
            $table->integer('dependent_count')->default(0)->after('residence_since');
            $table->string('home_ownership')->nullable()->after('dependent_count');
            $table->decimal('next_rent_amount', 15, 2)->default(0)->after('home_ownership');
            $table->date('next_rent_date')->nullable()->after('next_rent_amount');

            // Business Metadata
            $table->integer('point_of_sale_count')->default(0)->after('business_premise_type');
            $table->boolean('has_co_owners')->default(false)->after('point_of_sale_count');
            $table->integer('employee_count')->default(0)->after('has_co_owners');

            // Financial Metrics
            $table->decimal('other_net_income', 15, 2)->default(0)->after('operational_expenses');
            $table->decimal('net_profit', 15, 2)->default(0)->after('other_net_income');
            $table->decimal('applied_margin', 15, 2)->default(0)->after('payment_capacity');
            $table->decimal('current_assets', 15, 2)->default(0)->after('applied_margin');
            $table->decimal('fixed_assets', 15, 2)->default(0)->after('current_assets');
            $table->decimal('total_liabilities', 15, 2)->default(0)->after('fixed_assets');

            // JSON Snapshot Fields
            $table->json('purchase_history')->nullable()->after('daily_sales_logs');
            $table->json('risk_mitigation')->nullable()->after('inventory_details');
            $table->string('guarantor_type')->nullable()->after('risk_mitigation');
            $table->json('guarantor_business_financials')->nullable()->after('guarantor_type');
            $table->json('guarantor_employment_details')->nullable()->after('guarantor_business_financials');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'residence_since',
                'dependent_count',
                'home_ownership',
                'next_rent_amount',
                'next_rent_date',
                'point_of_sale_count',
                'has_co_owners',
                'employee_count',
                'other_net_income',
                'net_profit',
                'applied_margin',
                'current_assets',
                'fixed_assets',
                'total_liabilities',
                'purchase_history',
                'risk_mitigation',
                'guarantor_type',
                'guarantor_business_financials',
                'guarantor_employment_details',
            ]);
        });
    }
};
