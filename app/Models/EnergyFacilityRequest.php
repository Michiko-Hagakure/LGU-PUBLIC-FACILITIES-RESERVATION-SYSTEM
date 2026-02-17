<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyFacilityRequest extends Model
{
    protected $connection = 'auth_db';

    protected $table = 'energy_facility_requests';

    protected $fillable = [
        // Event Information
        'event_title',
        'purpose',
        'organizer_office',
        'point_person',
        'contact_number',
        'contact_email',

        // Schedule Details
        'preferred_date',
        'start_time',
        'end_time',
        'alternative_date',
        'alternative_start_time',
        'alternative_end_time',

        // Attendance & Format
        'audience_type',
        'session_type',

        // Venue Requirements
        'facility_type',

        // Equipment & Technical Needs
        'needs_projector',
        'needs_sound_system',
        'needs_microphone',
        'microphone_count',
        'microphone_type',
        'laptop_option',
        'needs_wifi',
        'needs_extension_cords',
        'additional_power_needs',
        'other_equipment',

        // Materials & Documents
        'needs_handouts',
        'handouts_format',
        'needs_certificates',
        'certificates_provider',

        // Food & Logistics
        'needs_refreshments',
        'dietary_notes',
        'delivery_instructions',

        // Special Requests
        'special_requests',

        // Status & Admin Response
        'status',
        'admin_feedback',
        'response_data',
        'booking_id',

        // Source tracking
        'user_id',
        'seminar_id',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'alternative_date' => 'date',
        'needs_projector' => 'boolean',
        'needs_sound_system' => 'boolean',
        'needs_microphone' => 'boolean',
        'needs_wifi' => 'boolean',
        'needs_extension_cords' => 'boolean',
        'needs_handouts' => 'boolean',
        'needs_certificates' => 'boolean',
        'needs_refreshments' => 'boolean',
    ];
}
