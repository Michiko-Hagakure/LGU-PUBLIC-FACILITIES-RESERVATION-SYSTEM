<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Change payment_method from ENUM to VARCHAR to accept any PayMongo payment source type
     * (grab_pay, card, gcash, paymaya, dob, dob_ubp, brankas_bdo, brankas_landbank, brankas_metrobank, qrph, etc.)
     */
    public function up(): void
    {
        DB::connection('facilities_db')->statement(
            "ALTER TABLE payment_slips MODIFY COLUMN payment_method VARCHAR(50) NULL"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('facilities_db')->statement(
            "ALTER TABLE payment_slips MODIFY COLUMN payment_method ENUM('cash', 'gcash', 'paymaya', 'bank_transfer', 'credit_card', 'cashless') NULL"
        );
    }
};
