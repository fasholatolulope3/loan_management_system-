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
        Schema::table('guarantors', function (Blueprint $table) {
            $table->string('spouse_name')->nullable()->after('relationship');
            $table->string('spouse_phone')->nullable()->after('spouse_name');
            $table->string('business_activity')->nullable()->after('type');
            $table->decimal('avg_monthly_sales', 15, 2)->default(0)->after('business_activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guarantors', function (Blueprint $table) {
            //
        });
    }
};
