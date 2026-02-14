<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add is_synced and last_synced_at columns to every table used by
     * the SyncDataToCloud job.  The facilities table already has them
     * (see 2026_02_13_025345), so it is skipped here.
     */
    public function up(): void
    {
        $authTables = ['users', 'activity_logs'];
        $facilityTables = ['bookings', 'payment_slips', 'announcements', 'facilities'];

        foreach ($authTables as $table) {
            if (!Schema::connection('auth_db')->hasColumn($table, 'is_synced')) {
                Schema::connection('auth_db')->table($table, function (Blueprint $t) {
                    $t->tinyInteger('is_synced')->default(0);
                    $t->timestamp('last_synced_at')->nullable();
                });
            }
        }

        foreach ($facilityTables as $table) {
            if (!Schema::connection('facilities_db')->hasColumn($table, 'is_synced')) {
                Schema::connection('facilities_db')->table($table, function (Blueprint $t) {
                    $t->tinyInteger('is_synced')->default(0);
                    $t->timestamp('last_synced_at')->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $authTables = ['users', 'activity_logs'];
        $facilityTables = ['bookings', 'payment_slips', 'announcements', 'facilities'];

        foreach ($authTables as $table) {
            if (Schema::connection('auth_db')->hasColumn($table, 'is_synced')) {
                Schema::connection('auth_db')->table($table, function (Blueprint $t) {
                    $t->dropColumn(['is_synced', 'last_synced_at']);
                });
            }
        }

        foreach ($facilityTables as $table) {
            if (Schema::connection('facilities_db')->hasColumn($table, 'is_synced')) {
                Schema::connection('facilities_db')->table($table, function (Blueprint $t) {
                    $t->dropColumn(['is_synced', 'last_synced_at']);
                });
            }
        }
    }
};
