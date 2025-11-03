<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_id',
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role', // citizen, staff, admin
        'phone_number',
        'region',
        'city',
        'barangay',
        'street_address',
        'address',
        'date_of_birth',
        'id_type',
        'id_number',
        'is_verified',
        'verified_at',
        // Authentication Security Fields
        'email_verified',
        'email_verification_token',
        'email_verification_sent_at',
        'phone_verified',
        'phone_verification_code',
        'phone_verification_sent_at',
        'phone_verification_attempts',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_enabled_at',
        'failed_verification_attempts',
        'verification_locked_until',
        'last_security_check',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'email_verification_token',
        'phone_verification_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'email_verified' => 'boolean',
        'phone_verified' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'two_factor_enabled_at' => 'datetime',
        'verification_locked_until' => 'datetime',
        'last_security_check' => 'datetime',
        'date_of_birth' => 'date',
    ];

    // --- Relationships ---

    /**
     * Relationship: User has many Bookings.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Relationship: User has many Payment Slips.
     */
    public function paymentSlips(): HasMany
    {
        return $this->hasMany(PaymentSlip::class, 'user_id');
    }

    // --- Role Checks ---

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isStaff(): bool { return $this->role === 'staff'; }
    public function isCitizen(): bool { return $this->role === 'citizen'; }

    // --- Verification Methods ---

    public function hasVerifiedEmail(): bool { return (bool) $this->email_verified; }
    public function hasVerifiedPhone(): bool { return (bool) $this->phone_verified; }
    public function hasTwoFactorEnabled(): bool { return (bool) $this->two_factor_enabled; }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        // Concatenate parts, ensuring 'name' is always available for backward compatibility
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name}");
    }

    /**
     * Set the name field based on first, middle, and last name.
     */
    protected static function boot(): void
    {
        parent::boot();

        // Auto-populate 'name' field when saving (for backward compatibility/display)
        static::saving(function ($user) {
            if ($user->first_name && $user->last_name) {
                $user->name = $user->full_name;
            }
        });
    }

    /**
     * Check if the account is currently locked due to failed verification attempts.
     */
    public function isLocked(): bool
    {
        return $this->verification_locked_until && $this->verification_locked_until->isFuture();
    }

    /**
     * Validate the provided phone verification code.
     *
     * @param string $code
     * @return bool
     */
    public function validatePhoneVerification(string $code): bool
    {
        if ($this->isLocked()) {
            return false;
        }

        if ($this->phone_verification_code === $code) {
            $this->update([
                'phone_verified' => true,
                'phone_verification_code' => null,
                'phone_verification_sent_at' => null,
                'phone_verification_attempts' => 0,
                'failed_verification_attempts' => 0,
            ]);
            return true;
        }
        
        $this->increment('phone_verification_attempts');
        $this->incrementFailedVerificationAttempts();
        return false;
    }

    /**
     * Increment failed verification attempts and apply security locks
     */
    public function incrementFailedVerificationAttempts(): void
    {
        $this->increment('failed_verification_attempts');
        
        // Lock account for 30 minutes after 5 failed attempts
        if ($this->failed_verification_attempts >= 5) {
            $this->update([
                'verification_locked_until' => now()->addMinutes(30),
            ]);
        }
    }

    /**
     * Check if user has completed all required verifications
     */
    public function hasCompletedRequiredVerifications(): bool
    {
        return $this->hasVerifiedEmail() && $this->hasVerifiedPhone();
    }
}