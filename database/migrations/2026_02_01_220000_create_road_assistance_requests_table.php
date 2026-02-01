<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table for Road and Transportation Infrastructure Monitoring road assistance requests
     */
    public function up(): void
    {
        Schema::connection('auth_db')->create('road_assistance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('requester_name');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('event_name');
            $table->text('event_description')->nullable();
            $table->string('event_location');
            $table->date('event_date');
            $table->time('event_start_time')->nullable();
            $table->time('event_end_time')->nullable();
            $table->integer('expected_attendees')->nullable();
            $table->text('affected_roads')->nullable();
            $table->text('assistance_type')->nullable(); // traffic_management, road_closure, escort, etc.
            $table->text('special_requirements')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('status')->default('pending'); // pending, Approved, Rejected
            $table->text('feedback')->nullable();
            $table->text('response_data')->nullable(); // JSON for approval details
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('auth_db')->dropIfExists('road_assistance_requests');
    }
};
