<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Remove unique constraint on booking_id to allow multiple payment slips per booking
     * (e.g., one for down payment, one for remaining balance)
     */
    public function up(): void
    {
        Schema::connection('facilities_db')->table('payment_slips', function (Blueprint $table) {
            $table->dropUnique(['booking_id']);
            $table->index('booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->table('payment_slips', function (Blueprint $table) {
            $table->dropIndex(['booking_id']);
            $table->unique('booking_id');
        });
    }
};
