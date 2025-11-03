<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthSecurityService;
use App\Services\CitizenAuthService; // Requires you to create this service
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Citizen\CitizenRegisterRequest; // Requires you to create this file
use App\Http\Requests\Citizen\CitizenLoginRequest; // Requires you to create this file
use App\Http\Requests\Citizen\VerifyTwoFactorRequest; // Requires you to create this file
use App\Http\Requests\Citizen\EnableTwoFactorRequest; // Requires you to create this file

class CitizenAuthController extends Controller
{
    protected AuthSecurityService $authSecurityService;
    protected CitizenAuthService $citizenAuthService;

    /**
     * Inject services via constructor.
     */
    public function __construct(AuthSecurityService $authSecurityService, CitizenAuthService $citizenAuthService)
    {
        $this->authSecurityService = $authSecurityService;
        $this->citizenAuthService = $citizenAuthService;
    }

    // --- Registration ---

    /**
     * Show citizen registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('citizen.auth.register');
    }

    /**
     * Handle citizen registration.
     * Uses CitizenRegisterRequest for clean validation.
     */
    public function register(CitizenRegisterRequest $request): RedirectResponse
    {
        // Validation is handled by CitizenRegisterRequest
        $validated = $request->validated();
        
        try {
            // Business logic moved to service
            $user = $this->citizenAuthService->registerUser($validated);
            
            // Log the new user in immediately after registration
            Auth::login($user); 

            return redirect()->route('citizen.dashboard')->with('success', 'Registration successful! Welcome to the portal.');
            
        } catch (\Exception $e) {
            Log::error('Citizen Registration Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()->withErrors(['error' => 'An error occurred during registration. Please try again.']);
        }
    }

    // --- Login ---

    /**
     * Show citizen login form.
     */
    public function showLoginForm(): View
    {
        // Check if user is already authenticated via SSO
        if (Auth::check()) {
            return redirect()->route('citizen.dashboard');
        }
        return view('citizen.auth.login');
    }

    /**
     * Handle citizen login attempt.
     * Uses CitizenLoginRequest for clean validation.
     */
    public function login(CitizenLoginRequest $request): RedirectResponse
    {
        // Validation is handled by CitizenLoginRequest
        $credentials = $request->validated();
        
        $authenticated = $this->citizenAuthService->attemptLogin($credentials);

        if ($authenticated) {
            $user = Auth::user();
            
            // If 2FA is enabled, redirect to verification
            if ($user->hasTwoFactorEnabled()) {
                // Store user ID in session to retrieve after 2FA verification
                $request->session()->put('login.id', $user->id);
                // Log out the user temporarily until 2FA is verified
                Auth::logout(); 
                $request->session()->regenerateToken(); 
                
                return redirect()->route('citizen.security.verify-2fa');
            }
            
            // Standard login success
            $request->session()->regenerate();
            return redirect()->intended(route('citizen.dashboard'))->with('success', 'Welcome back!');
        }

        return redirect()->back()->withInput()->withErrors([
            'email' => 'The provided credentials do not match our records or your account is inactive.',
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the SSO login page after local logout
        return redirect()->route('login'); 
    }

    // --- 2FA Verification ---

    /**
     * Show 2FA verification form.
     */
    public function showTwoFactorVerify(): RedirectResponse|View
    {
        // Check if we have a user ID in the session from the login attempt
        if (!Session::has('login.id')) {
            return redirect()->route('login');
        }
        
        return view('citizen.auth.verify-2fa');
    }

    /**
     * Handle 2FA code verification.
     * Uses VerifyTwoFactorRequest for clean validation.
     */
    public function verifyTwoFactor(VerifyTwoFactorRequest $request): RedirectResponse
    {
        // Validation is handled by VerifyTwoFactorRequest
        $validated = $request->validated();
        
        // Get user from session
        $userId = $request->session()->pull('login.id');

        if (!$userId || !$user = User::find($userId)) {
            return redirect()->route('login')->with('error', 'Authentication state expired. Please log in again.');
        }

        // Verify the code using the injected service
        if ($this->authSecurityService->verifyTotpCode($user, $validated['verification_code'])) {
            // Log the user in officially
            Auth::login($user, true); 
            $request->session()->regenerate();
            
            return redirect()->intended(route('citizen.dashboard'))->with('success', 'Two-Factor Authentication successful!');
        }

        // Verification failed
        $request->session()->put('login.id', $userId); // Put ID back for re-attempt
        return redirect()->back()->withErrors(['verification_code' => 'Invalid verification code.']);
    }

    // --- 2FA Setup ---
    
    /**
     * Show 2FA setup page.
     */
    public function showTwoFactorSetup(): RedirectResponse|View
    {
        if (!Auth::check()) {
            return redirect()->route('sso.login');
        }

        $user = Auth::user();
        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('citizen.dashboard');
        }

        // Generate secret and QR code URL using the security service
        $secret = $this->authSecurityService->generateTotpSecret($user);
        $qrCodeUrl = $this->authSecurityService->generateQrCodeUrl($user);
        
        // Temporarily store the secret in the session until verified
        Session::put('2fa_setup.secret', $secret);

        return view('citizen.auth.setup-2fa', compact('secret', 'qrCodeUrl'));
    }

    /**
     * Enable 2FA after verification.
     * Uses EnableTwoFactorRequest for clean validation.
     */
    public function enableTwoFactor(EnableTwoFactorRequest $request): JsonResponse
    {
        // Validation is handled by EnableTwoFactorRequest
        $validated = $request->validated();
        
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Authentication required.'], 401);
        }
        
        $user = Auth::user();
        $secret = Session::pull('2fa_setup.secret'); // Get secret from session

        if (!$secret) {
             return response()->json(['success' => false, 'message' => '2FA setup timed out. Please refresh the page.'], 408);
        }

        if ($this->authSecurityService->enableTwoFactor($user, $validated['verification_code'], $secret)) {
            return response()->json([
                'success' => true,
                'message' => '2FA enabled successfully!',
                'recovery_codes' => $user->two_factor_recovery_codes // Must be stored as JSON in DB
            ]);
        }
        
        // If verification fails, put secret back for re-attempt
        Session::put('2fa_setup.secret', $secret); 
        return response()->json(['success' => false, 'message' => 'Invalid verification code.'], 400);
    }
}