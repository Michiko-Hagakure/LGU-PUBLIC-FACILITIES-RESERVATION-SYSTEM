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
            if (!Schema::connection('facilities_db')->hasColumn('bookings', 'source_system')) {
                $table->string('source_system', 100)->nullable()->after('status')->comment('External system that created the booking');
            }
            if (!Schema::connection('facilities_db')->hasColumn('bookings', 'external_reference_id')) {
                $table->string('external_reference_id', 100)->nullable()->after('source_system')->comment('Reference ID from external system');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->table('bookings', function (Blueprint $table) {
            $table->dropColumn(['source_system', 'external_reference_id']);
        });
    }
};
