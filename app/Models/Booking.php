<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookings';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'facility_id',
        'user_id',
        'user_name',
        'applicant_name',
        'applicant_email', 
        'applicant_phone',
        'applicant_address',
        'event_name',
        'event_description',
        'event_date',
        'start_time',
        'end_time',
        'expected_attendees',
        'total_fee',
        'status', // e.g., pending, approved, rejected, completed, cancelled
        'admin_notes',
        'staff_verified_by', // Staff verification tracking
        'staff_verified_at',
        'staff_notes',
        'approved_by', // Admin approval tracking
        'approved_at',
        'rejected_reason',
        // Document file paths
        'valid_id_path',
        'id_back_path',
        'id_selfie_path',
        'authorization_letter_path',
        'event_proposal_path',
        'digital_signature'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'expected_attendees' => 'integer',
        'total_fee' => 'decimal:2',
        'approved_at' => 'datetime',
        'staff_verified_at' => 'datetime',
    ];

    // --- Relationships ---

    /**
     * Relationship: Booking belongs to a Facility.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    /**
     * Relationship: Booking belongs to a User (Citizen).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: Booking has one PaymentSlip.
     */
    public function paymentSlip()
    {
        return $this->hasOne(PaymentSlip::class);
    }

    // --- Custom Methods ---

    /**
     * Check if extending the booking end time would cause a schedule conflict.
     *
     * @param string $newEndTime The proposed new end time (H:i:s).
     * @return array{hasConflict: bool, conflicts: Collection, message: string}
     */
    public function checkExtensionConflict(string $newEndTime): array
    {
        // Sanity check: The new end time must be after the current start time
        if (Carbon::parse($newEndTime)->lessThan(Carbon::parse($this->start_time))) {
            return [
                'hasConflict' => true, 
                'conflicts' => collect(), 
                'message' => 'New end time must be after the start time.'
            ];
        }

        $conflicts = self::where('facility_id', $this->facility_id)
            ->whereDate('event_date', $this->event_date)
            ->where('id', '!=', $this->id) // Exclude current booking
            ->whereIn('status', ['approved', 'pending']) // Only check approved/pending bookings
            ->where(function($query) use ($newEndTime) {
                // Overlap occurs when: current_start < other_end AND new_end > other_start
                $query->where('start_time', '<', $newEndTime)
                      ->where('end_time', '>', $this->start_time);
            })
            ->with(['facility', 'user'])
            ->get();

        return [
            'hasConflict' => $conflicts->isNotEmpty(),
            'conflicts' => $conflicts,
            'message' => $conflicts->isNotEmpty() 
                ? 'Extension would conflict with ' . $conflicts->count() . ' existing booking(s)'
                : 'No conflicts detected'
        ];
    }

    /**
     * Extend the booking end time.
     * * @param string $newEndTime
     * @return bool
     */
    public function extendBooking(string $newEndTime): bool
    {
        $conflictCheck = $this->checkExtensionConflict($newEndTime);
        
        if ($conflictCheck['hasConflict']) {
            return false;
        }

        $this->end_time = $newEndTime;
        return $this->save();
    }
}