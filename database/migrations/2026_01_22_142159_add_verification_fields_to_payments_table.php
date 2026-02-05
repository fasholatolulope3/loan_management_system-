<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Field to track who verified it
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');

            // Field to track when it was verified
            $table->timestamp('verified_at')->nullable();

            // Ensure 'type' exists if you haven't added it yet
            if (!Schema::hasColumn('payments', 'type')) {
                $table->string('type')->default('repayment')->after('id');
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['verified_by', 'verified_at', 'type']);
        });
    }
};
