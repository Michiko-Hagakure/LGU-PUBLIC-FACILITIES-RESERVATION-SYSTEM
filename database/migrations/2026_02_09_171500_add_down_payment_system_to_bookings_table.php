<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds down payment system fields to bookings table.
     * 
     * New flow: Citizen books + pays upfront (25%/50%/75%/100%) → Staff verifies → Admin confirms
     * Only paid bookings lock time slots. No refunds.
     */
    public function up(): void
    {
        Schema::connection('facilities_db')->table('bookings', function (Blueprint $table) {
            // Payment tier selection (what % the citizen chose to pay upfront)
            $table->unsignedTinyInteger('payment_tier')->nullable()->after('total_amount')
                ->comment('Percentage citizen chose to pay upfront: 25, 50, 75, or 100');
            
            // Down payment tracking
            $table->decimal('down_payment_amount', 10, 2)->default(0)->after('payment_tier')
                ->comment('Calculated down payment amount based on tier');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('down_payment_amount')
                ->comment('Total amount paid so far');
            $table->decimal('amount_remaining', 10, 2)->default(0)->after('amount_paid')
                ->comment('Remaining balance to be paid');
            
            // Payment method and timestamp
            $table->string('payment_method', 50)->nullable()->after('amount_remaining')
                ->comment('cash, gcash, paymaya, bank_transfer, credit_card');
            $table->dateTime('down_payment_paid_at')->nullable()->after('payment_method')
                ->comment('When the down payment was recorded/collected');
            $table->unsignedBigInteger('payment_recorded_by')->nullable()->after('down_payment_paid_at')
                ->comment('Treasurer user_id who recorded the payment');
            
            // Partial rejection support
            $table->string('rejection_type', 50)->nullable()->after('rejected_reason')
                ->comment('id_issue, facility_issue, document_issue, info_issue');
            $table->json('rejection_fields')->nullable()->after('rejection_type')
                ->comment('JSON array of specific fields that need to be corrected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_tier',
                'down_payment_amount',
                'amount_paid',
                'amount_remaining',
                'payment_method',
                'down_payment_paid_at',
                'payment_recorded_by',
                'rejection_type',
                'rejection_fields',
            ]);
        });
    }
};
