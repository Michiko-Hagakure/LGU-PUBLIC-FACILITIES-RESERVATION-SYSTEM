<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::connection('facilities_db')->statement(
            "ALTER TABLE payment_slips MODIFY COLUMN payment_method ENUM('cash', 'gcash', 'paymaya', 'bank_transfer', 'credit_card', 'cashless') NULL"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::connection('facilities_db')->statement(
            "ALTER TABLE payment_slips MODIFY COLUMN payment_method ENUM('cash', 'gcash', 'paymaya', 'bank_transfer', 'credit_card') NULL"
        );
    }
};
