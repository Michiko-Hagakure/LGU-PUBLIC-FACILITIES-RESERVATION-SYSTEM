<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Services\SSOService; // Requires you to create this service class

class SsoController extends Controller
{
    protected SSOService $ssoService;

    public function __construct(SSOService $ssoService)
    {
        $this->ssoService = $ssoService;
    }

    /**
     * HMAC-validated SSO login entrypoint.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // 1. Logging and Debugging
        $this->ssoService->logSsoAttempt($request);

        // 2. Validate essential parameters and signature
        if (!$this->ssoService->validateHmac($request)) {
            Log::warning('SSO Login Failed: HMAC signature mismatch or invalid params.', $request->all());
            return redirect('/login')->with('error', 'Authentication failed due to security verification.');
        }

        // 3. Process SSO data and authenticate user
        try {
            $ssoData = $this->ssoService->extractSsoData($request);
            
            $user = $this->ssoService->findOrCreateUser($ssoData);
            
            Auth::login($user, true); // Log user in and remember session

            Log::info('SSO Login Success:', ['user_id' => $user->id, 'role' => $user->role]);

            // 4. Determine redirect path
            $redirectPath = $this->ssoService->getRedirectPath($request, $user->role);
            
            return redirect()->intended($redirectPath);

        } catch (\Exception $e) {
            Log::error('SSO Login Error:', ['message' => $e->getMessage(), 'request' => $request->all()]);
            return redirect('/login')->with('error', 'An internal error occurred during authentication.');
        }
    }

    /**
     * Handle staff dashboard redirection after SSO login (often a placeholder).
     * This ensures the user is redirected to the correct staff route after authentication.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleStaffDashboard(Request $request)
    {
        // Since the user is already authenticated by the 'sso' middleware if they reach here,
        // we just ensure they have the 'staff' role before redirecting to the specific dashboard.
        $user = Auth::user();
        
        if ($user && $user->role === 'staff') {
            return redirect()->route('staff.dashboard');
        }
        
        // If not authenticated or not staff, redirect to main entry point
        return redirect()->route('home');
    }
}