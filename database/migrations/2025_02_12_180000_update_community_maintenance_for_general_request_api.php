<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates the community_maintenance_requests table to support the new
     * General Maintenance Request API (GeneralRequest.php) under /api/integration/CITIZEN/
     */
    public function up(): void
    {
        Schema::connection('facilities_db')->table('community_maintenance_requests', function (Blueprint $table) {
            // New fields for General Request API
            $table->string('category')->nullable()->after('external_report_id');
            $table->string('issue_type')->nullable()->after('category');
            $table->string('location', 500)->nullable()->after('description');
            $table->string('reporter_name')->nullable()->after('location');
            $table->string('reporter_contact')->nullable()->after('reporter_name');
            $table->string('photo_path')->nullable()->after('reporter_contact');
            $table->unsignedBigInteger('external_request_id')->nullable()->after('external_report_id');

            $table->index('category');
            $table->index('external_request_id');
        });

        // Update status enum to match new API status flow: Pending, In Progress, Completed, Closed
        // Map old statuses to new ones
        DB::connection('facilities_db')
            ->table('community_maintenance_requests')
            ->where('status', 'submitted')
            ->update(['status' => 'Pending']);

        DB::connection('facilities_db')
            ->table('community_maintenance_requests')
            ->whereIn('status', ['reviewed', 'in_progress'])
            ->update(['status' => 'In Progress']);

        DB::connection('facilities_db')
            ->table('community_maintenance_requests')
            ->where('status', 'resolved')
            ->update(['status' => 'Completed']);

        DB::connection('facilities_db')
            ->table('community_maintenance_requests')
            ->where('status', 'closed')
            ->update(['status' => 'Closed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status values
        DB::connection('facilities_db')
            ->table('community_maintenance_requests')
            ->where('status', 'Pending')
            ->update(['status' => 'submitted']);

        DB::connection('facilities_db')
            ->table('community_maintenance_requests')
            ->where('status', 'In Progress')
            ->update(['status' => 'in_progress']);

        DB::connection('facilities_db')
            ->table('community_maintenance_requests')
            ->where('status', 'Completed')
            ->update(['status' => 'resolved']);

        DB::connection('facilities_db')
            ->table('community_maintenance_requests')
            ->where('status', 'Closed')
            ->update(['status' => 'closed']);

        Schema::connection('facilities_db')->table('community_maintenance_requests', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['external_request_id']);
            $table->dropColumn([
                'category',
                'issue_type',
                'location',
                'reporter_name',
                'reporter_contact',
                'photo_path',
                'external_request_id',
            ]);
        });
    }
};
