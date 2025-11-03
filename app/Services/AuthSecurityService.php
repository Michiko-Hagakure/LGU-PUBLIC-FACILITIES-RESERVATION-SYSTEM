<?php

namespace App\Services;

use App\Models\User;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session; // Added Session for dev codes
use PragmaRX\Google2FA\Google2FA;
use Twilio\Rest\Client;
use Carbon\Carbon; // Added Carbon for time-related checks

class AuthSecurityService
{
    // --- Constants for better readability and maintainability ---
    private const RECOVERY_CODE_COUNT = 8;
    private const RECOVERY_CODE_LENGTH = 10;
    private const TOTP_SECRET_LENGTH = 32;

    public function __construct(protected Google2FA $google2fa)
    {
        // Google2FA is now a promoted property.
    }

    /**
     * Send email verification.
     */
    public function sendEmailVerification(User $user): bool
    {
        try {
            $token = $user->generateEmailVerificationToken();
            $verificationUrl = route('citizen.auth.verify-email', ['token' => $token]);

            Log::info('Attempting to send email verification', [
                'user_id' => $user->id,
                'email' => $user->email,
                'verification_url' => $verificationUrl,
            ]);

            Mail::to($user->email)->send(new EmailVerificationMail($user, $verificationUrl));
            
            Log::info('Email verification Mailable dispatched successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send verification email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send phone verification code via SMS (using Twilio).
     */
    public function sendPhoneVerification(User $user): bool
    {
        if (!$this->canProceedWithVerification($user)) {
            Log::warning('Verification denied due to lock', ['user_id' => $user->id]);
            return false;
        }
        
        try {
            // Generates and stores the code in the User model
            $code = $user->generatePhoneVerificationCode(); 

            // Simulating Twilio/SMS sending in a real environment
            if (config('app.env') === 'local') {
                Session::put('dev_sms_verification', $code);
                Log::info('Development SMS Verification Code generated', [
                    'user_id' => $user->id,
                    'code' => $code
                ]);
                return true;
            }

            // Real Twilio implementation (uncomment and configure if used)
            /*
            $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
            $twilio->messages->create(
                $user->phone_number,
                [
                    'from' => config('services.twilio.from'),
                    'body' => "Your LGU verification code is: {$code}. Do not share this code.",
                ]
            );
            */

            Log::info('SMS verification code sent', ['user_id' => $user->id]);
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to send phone verification SMS', [
                'user_id' => $user->id,
                'phone' => $user->phone_number,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Verify phone verification code.
     */
    public function verifyPhoneVerificationCode(User $user, string $code): bool
    {
        if (!$this->canProceedWithVerification($user)) {
            Log::warning('Verification denied due to lock', ['user_id' => $user->id]);
            return false;
        }
        
        $isVerified = $user->verifyPhoneCode($code);
        
        if ($isVerified) {
            $this->resetFailedVerificationAttempts($user);
            Log::info('Phone verification successful', ['user_id' => $user->id]);
        } else {
            // Failed verification attempts are now handled inside the User model's verifyPhoneCode() method.
            Log::warning('Phone verification failed', ['user_id' => $user->id, 'code_attempt' => $code]);
        }
        
        return $isVerified;
    }

    /**
     * Generate and store a new TOTP secret for the user.
     */
    public function generateTotpSecret(User $user): string
    {
        // Use the specified length for stronger secret generation
        $secret = $this->google2fa->generateSecretKey(self::TOTP_SECRET_LENGTH);
        
        $user->update([
            'two_factor_secret' => encrypt($secret)
        ]);

        return $secret;
    }

    /**
     * Generate QR code URL for 2FA setup.
     */
    public function generateQrCodeUrl(User $user): string
    {
        $secret = decrypt($user->two_factor_secret);
        
        $companyName = config('app.name', 'LGU Facility Booking');
        $accountName = $user->email;
        
        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $accountName,
            $secret
        );
    }

    /**
     * Verify a time-based one-time password (TOTP) code.
     */
    public function verifyTwoFactorCode(User $user, string $code): bool
    {
        if (!$user->two_factor_secret) {
            return false; // 2FA not set up
        }

        $secret = decrypt($user->two_factor_secret);
        
        $isVerified = $this->google2fa->verifyKey($secret, $code);

        if ($isVerified) {
            $this->resetFailedVerificationAttempts($user);
        } else {
            $user->incrementFailedVerificationAttempts(); // Handle lock logic in User model
        }

        return $isVerified;
    }

    /**
     * Enable 2FA after successful verification, and generate recovery codes.
     */
    public function enableTwoFactor(User $user, string $code): bool
    {
        if ($this->verifyTwoFactorCode($user, $code)) {
            $recoveryCodes = $this->generateRecoveryCodes();
            
            $user->update([
                'two_factor_enabled' => true,
                'two_factor_recovery_codes' => $recoveryCodes, // Stores as JSON in DB
                'two_factor_enabled_at' => Carbon::now(),
            ]);
            
            Log::info('2FA enabled successfully and recovery codes generated', ['user_id' => $user->id]);
            return true;
        }

        return false;
    }

    /**
     * Generate an array of unique recovery codes.
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        $google2fa = new Google2FA();
        
        for ($i = 0; $i < self::RECOVERY_CODE_COUNT; $i++) {
            do {
                // Generate a random string and ensure it's not already in the array
                $code = strtoupper(substr(str_replace(['/', '+', '='], '', base64_encode(random_bytes(self::RECOVERY_CODE_LENGTH))), 0, self::RECOVERY_CODE_LENGTH));
            } while (in_array($code, $codes));
            
            $codes[] = $code;
        }

        return $codes;
    }

    /**
     * Verify recovery code and remove it from the list if valid.
     */
    public function verifyRecoveryCode(User $user, string $code): bool
    {
        // Recovery codes are stored as JSON and should be auto-cast to array by the model.
        $recoveryCodes = $user->two_factor_recovery_codes ?? [];
        
        $upperCode = strtoupper($code);
        
        if (empty($recoveryCodes) || !in_array($upperCode, $recoveryCodes)) {
            $user->incrementFailedVerificationAttempts(); // Treat as a failed security attempt
            return false;
        }

        // Remove used recovery code
        $updatedCodes = array_filter($recoveryCodes, fn($recoveryCode) => strtoupper($recoveryCode) !== $upperCode);

        $user->update([
            'two_factor_recovery_codes' => array_values($updatedCodes)
        ]);
        
        $this->resetFailedVerificationAttempts($user);

        return true;
    }

    /**
     * Check if user can proceed with any security verification (not locked).
     */
    public function canProceedWithVerification(User $user): bool
    {
        return !$user->isVerificationLocked();
    }

    /**
     * Reset failed verification attempts and unlock the user.
     */
    public function resetFailedVerificationAttempts(User $user): void
    {
        $user->update([
            'failed_verification_attempts' => 0,
            'verification_locked_until' => null,
        ]);
    }

    /**
     * Get development verification codes (for testing only).
     */
    public function getDevVerificationCodes(): array
    {
        if (config('app.env') !== 'local') {
            return [];
        }

        return [
            'email' => Session::get('dev_email_verification'),
            'sms' => Session::get('dev_sms_verification'),
        ];
    }
}