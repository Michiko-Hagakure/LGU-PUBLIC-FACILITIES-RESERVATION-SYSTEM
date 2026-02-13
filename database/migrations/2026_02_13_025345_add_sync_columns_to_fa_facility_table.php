<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * * This migration adds synchronization tracking columns to the 'facilities' table.
     * We use the 'mysql_facilities' connection to target the cloud/secondary database.
     */
    public function up(): void
    {
        /** * FIX: Changed table name from 'fa_facility' to 'facilities' 
         * based on the actual table name found in your phpMyAdmin.
         */
        // Guard against different schemas in the target (cloud) DB — some exports use
        // `facility_id` as PK while local migrations use `id`. Skip if columns already exist.
        if (!Schema::connection('mysql_facilities')->hasColumn('facilities', 'is_synced')) {
            Schema::connection('mysql_facilities')->table('facilities', function (Blueprint $table) {
                $table->tinyInteger('is_synced')->default(0);
                $table->timestamp('last_synced_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /** * Ensure the table name here matches the 'up' method during rollback.
         */
        // Only drop columns if they exist on the target connection
        if (Schema::connection('mysql_facilities')->hasColumn('facilities', 'is_synced')) {
            Schema::connection('mysql_facilities')->table('facilities', function (Blueprint $table) {
                $table->dropColumn(['is_synced', 'last_synced_at']);
            });
        }
    }
};