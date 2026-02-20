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
            $table->enum('sex', ['M', 'F'])->nullable()->after('marital_status');
            $table->integer('dependent_persons')->default(0)->after('sex');
            $table->date('date_of_visit_business')->nullable()->after('dependent_persons');
            $table->date('date_of_visit_residence')->nullable()->after('date_of_visit_business');
            $table->string('employer_address')->nullable()->after('position');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guarantors', function (Blueprint $table) {
            $table->dropColumn([
                'sex',
                'dependent_persons',
                'date_of_visit_business',
                'date_of_visit_residence',
                'employer_address',
            ]);
        });
    }
};
