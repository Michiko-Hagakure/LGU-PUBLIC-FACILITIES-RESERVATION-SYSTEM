<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('auth_db')->create('energy_facility_requests', function (Blueprint $table) {
            $table->id();

            // Event Information
            $table->string('event_title', 255);
            $table->text('purpose')->nullable();
            $table->string('organizer_office', 255)->nullable();
            $table->string('point_person', 255);
            $table->string('contact_number', 50)->nullable();
            $table->string('contact_email', 255)->nullable();

            // Schedule Details
            $table->date('preferred_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->date('alternative_date')->nullable();
            $table->time('alternative_start_time')->nullable();
            $table->time('alternative_end_time')->nullable();

            // Attendance & Format
            $table->string('audience_type', 100)->nullable()->comment('employees, public, students, mixed');
            $table->string('session_type', 100)->nullable()->comment('orientation, training, workshop, briefing, meeting');

            // Venue Requirements
            $table->string('facility_type', 50)->nullable()->comment('small, medium, large');

            // Equipment & Technical Needs (stored as JSON)
            $table->boolean('needs_projector')->default(false);
            $table->string('laptop_option', 50)->default('no')->comment('yes, no, bringing_own');
            $table->boolean('needs_sound_system')->default(false);
            $table->boolean('needs_microphone')->default(false);
            $table->integer('microphone_count')->default(0);
            $table->string('microphone_type', 50)->nullable()->comment('handheld, lapel, both');
            $table->boolean('needs_wifi')->default(false);
            $table->boolean('needs_extension_cords')->default(false);
            $table->text('additional_power_needs')->nullable();
            $table->text('other_equipment')->nullable();

            // Materials & Documents
            $table->boolean('needs_handouts')->default(false);
            $table->string('handouts_format', 50)->nullable()->comment('softcopy, hardcopy, both');
            $table->boolean('needs_certificates')->default(false);
            $table->string('certificates_provider', 100)->nullable()->comment('us, them, both');

            // Food & Logistics
            $table->boolean('needs_refreshments')->default(false);
            $table->text('dietary_notes')->nullable();
            $table->text('delivery_instructions')->nullable();

            // Special Requests / Additional Notes
            $table->text('special_requests')->nullable();

            // Status & Admin Response
            $table->string('status', 50)->default('pending')->comment('pending, approved, rejected, completed');
            $table->text('admin_feedback')->nullable();
            $table->text('response_data')->nullable()->comment('JSON: assigned facility, equipment, schedule, budget, etc.');
            $table->unsignedBigInteger('booking_id')->nullable()->comment('Link to bookings table if approved');

            // Source tracking
            $table->unsignedBigInteger('user_id')->nullable()->comment('Energy system user ID');
            $table->unsignedBigInteger('seminar_id')->nullable()->comment('Energy system seminar ID');

            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('preferred_date');
            $table->index('seminar_id');
        });
    }

    public function down(): void
    {
        Schema::connection('auth_db')->dropIfExists('energy_facility_requests');
    }
};
