<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RoadAssistanceRequest extends Model {
    protected $connection = 'auth_db';
    
    protected $table = 'road_assistance_requests';

    protected $fillable = [
        'requester_name',
        'user_id',
        'event_name',
        'event_description',
        'event_location',
        'event_date',
        'event_start_time',
        'event_end_time',
        'expected_attendees',
        'affected_roads',
        'assistance_type',
        'special_requirements',
        'contact_phone',
        'contact_email',
        'status',
        'feedback',
        'response_data',
    ];

    protected $casts = [
        'event_date' => 'date',
        'expected_attendees' => 'integer',
    ];
}
