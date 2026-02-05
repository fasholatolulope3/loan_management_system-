<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('guarantors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');

            // Basic Info
            $table->string('name');
            $table->string('phone');
            $table->string('relationship');
            $table->text('address');

            // Assessment Fields (Requirement #7 & #8)
            $table->string('marital_status')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('type')->default('Employee'); // <--- THE MISSING COLUMN FIX 🚀
            $table->string('employer_name')->nullable();
            $table->string('job_sector')->nullable();
            $table->string('position')->nullable();
            $table->decimal('net_monthly_income', 15, 2)->default(0);
            $table->json('business_financials')->nullable(); // For Owners

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('guarantors');
    }
};
