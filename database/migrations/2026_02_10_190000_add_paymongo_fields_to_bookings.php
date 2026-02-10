<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('facilities_db')->table('bookings', function (Blueprint $table) {
            $table->string('paymongo_checkout_id')->nullable()->after('down_payment_paid_at');
            $table->string('paymongo_payment_id')->nullable()->after('paymongo_checkout_id');
        });
    }

    public function down(): void
    {
        Schema::connection('facilities_db')->table('bookings', function (Blueprint $table) {
            $table->dropColumn(['paymongo_checkout_id', 'paymongo_payment_id']);
        });
    }
};
