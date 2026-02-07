<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('facilities_db')->table('bookings', function (Blueprint $table) {
            $table->text('payment_rejection_reason')->nullable()->after('status');
            $table->timestamp('payment_rejected_at')->nullable()->after('payment_rejection_reason');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_rejection_reason', 'payment_rejected_at']);
        });
    }
};
