<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('collaterals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');

            // From Form CF5: HG (Household), FA (Fixed Asset), etc.
            $table->string('type');
            $table->text('description');

            $table->decimal('market_value', 15, 2)->default(0);

            // Rule: usually 70% of market value for liquidation
            $table->decimal('liquidation_value', 15, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collaterals');
    }
};