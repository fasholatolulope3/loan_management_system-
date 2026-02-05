<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // Business Details
            if (!Schema::hasColumn('loans', 'business_name')) {
                $table->string('business_name')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('loans', 'business_location')) {
                $table->string('business_location')->nullable()->after('business_name');
            }
            if (!Schema::hasColumn('loans', 'business_start_date')) {
                $table->date('business_start_date')->nullable()->after('business_location');
            }
            if (!Schema::hasColumn('loans', 'business_premise_type')) {
                $table->string('business_premise_type')->nullable()->after('business_start_date');
            }

            // Financial Metrics
            if (!Schema::hasColumn('loans', 'monthly_sales')) {
                $table->decimal('monthly_sales', 15, 2)->default(0)->after('business_premise_type');
            }
            if (!Schema::hasColumn('loans', 'cost_of_sales')) {
                $table->decimal('cost_of_sales', 15, 2)->default(0)->after('monthly_sales');
            }
            if (!Schema::hasColumn('loans', 'gross_profit')) {
                $table->decimal('gross_profit', 15, 2)->default(0)->after('cost_of_sales');
            }
            if (!Schema::hasColumn('loans', 'operational_expenses')) {
                $table->decimal('operational_expenses', 15, 2)->default(0)->after('gross_profit');
            }
            if (!Schema::hasColumn('loans', 'family_expenses')) {
                $table->decimal('family_expenses', 15, 2)->default(0)->after('operational_expenses');
            }
            if (!Schema::hasColumn('loans', 'payment_capacity')) {
                $table->decimal('payment_capacity', 15, 2)->default(0)->after('family_expenses');
            }
            if (!Schema::hasColumn('loans', 'equity_value')) {
                $table->decimal('equity_value', 15, 2)->default(0)->after('payment_capacity');
            }

            // JSON Repeating Tables
            if (!Schema::hasColumn('loans', 'daily_sales_logs')) {
                $table->json('daily_sales_logs')->nullable()->after('equity_value');
            }
            if (!Schema::hasColumn('loans', 'inventory_details')) {
                $table->json('inventory_details')->nullable()->after('daily_sales_logs');
            }
            if (!Schema::hasColumn('loans', 'business_references')) {
                $table->json('business_references')->nullable()->after('inventory_details');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'business_location',
                'business_start_date',
                'business_premise_type',
                'monthly_sales',
                'cost_of_sales',
                'gross_profit',
                'operational_expenses',
                'family_expenses',
                'payment_capacity',
                'equity_value',
                'daily_sales_logs',
                'inventory_details',
                'business_references'
            ]);
        });
    }
};