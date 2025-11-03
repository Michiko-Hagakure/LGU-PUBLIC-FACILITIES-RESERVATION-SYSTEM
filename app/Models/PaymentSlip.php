<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PaymentSlip extends Model
{
    protected $fillable = [
        'slip_number',
        'booking_id',
        'user_id',
        'generated_by',
        'amount',
        'status', // unpaid, paid, expired, cancelled
        'due_date',
        'paid_at',
        'payment_method',
        'cashier_notes',
        'paid_by_cashier'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    // --- Custom Methods ---

    /**
     * Generate a unique slip number (e.g., PS-YYYY-0001)
     */
    public static function generateSlipNumber(): string
    {
        $year = Carbon::now()->year;
        $prefix = "PS-{$year}-";
        
        // Find the last slip number for the current year
        $lastSlip = self::where('slip_number', 'like', "{$prefix}%")
                       ->orderBy('slip_number', 'desc')
                       ->first();
        
        if ($lastSlip) {
            // Extract the number part (last 4 digits) and increment
            $lastNumber = (int) substr($lastSlip->slip_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "{$prefix}{$newNumber}";
    }

    // --- Relationships ---

    /**
     * Relationship: PaymentSlip belongs to a Booking.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Relationship: PaymentSlip belongs to a User (Citizen).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Get the User who generated the slip (Admin/Staff).
     */
    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    /**
     * Relationship: Get the User who marked the slip as paid (Cashier/Admin).
     */
    public function paidByCashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by_cashier');
    }

    // --- Accessors ---

    /**
     * Accessor: Check if payment slip is expired.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->status === 'unpaid' && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Accessor: Get days until due.
     */
    public function getDaysUntilDueAttribute(): ?int
    {
        if ($this->status !== 'unpaid' || !$this->due_date) {
            return null;
        }

        $now = now()->startOfDay();
        $dueDate = $this->due_date->startOfDay();

        return $now->diffInDays($dueDate, false);
    }
}