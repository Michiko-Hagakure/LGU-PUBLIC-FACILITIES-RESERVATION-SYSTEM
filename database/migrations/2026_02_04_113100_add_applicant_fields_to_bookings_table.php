<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds applicant contact fields for external API bookings (e.g., from PF folder)
     * These fields store contact info when user_id is null (guest bookings)
     */
    public function up(): void
    {
        Schema::connection('facilities_db')->table('bookings', function (Blueprint $table) {
            // Add applicant fields after user_name if they don't exist
            if (!Schema::connection('facilities_db')->hasColumn('bookings', 'applicant_name')) {
                $table->string('applicant_name', 255)->nullable()->after('user_name');
            }
            if (!Schema::connection('facilities_db')->hasColumn('bookings', 'applicant_email')) {
                $table->string('applicant_email', 255)->nullable()->after('applicant_name');
            }
            if (!Schema::connection('facilities_db')->hasColumn('bookings', 'applicant_phone')) {
                $table->string('applicant_phone', 20)->nullable()->after('applicant_email');
            }
            if (!Schema::connection('facilities_db')->hasColumn('bookings', 'applicant_address')) {
                $table->string('applicant_address', 500)->nullable()->after('applicant_phone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('facilities_db')->table('bookings', function (Blueprint $table) {
            $columns = ['applicant_name', 'applicant_email', 'applicant_phone', 'applicant_address'];
            foreach ($columns as $column) {
                if (Schema::connection('facilities_db')->hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
