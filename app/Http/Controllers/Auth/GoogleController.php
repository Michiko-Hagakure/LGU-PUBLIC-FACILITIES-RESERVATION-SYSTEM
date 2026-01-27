<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Get Socialite driver with SSL fix for Laragon
     */
    private function getGoogleDriver()
    {
        // Fix for Laragon SSL certificate issue
        $guzzle = new \GuzzleHttp\Client([
            'verify' => false, // Disable SSL verification for local development
        ]);
        
        return Socialite::driver('google')->setHttpClient($guzzle);
    }

    /**
     * Redirect to Google OAuth for login
     */
    public function redirect()
    {
        session(['google_auth_type' => 'login']);
        return $this->getGoogleDriver()->redirect();
    }

    /**
     * Redirect to Google OAuth for registration
     */
    public function redirectForRegister()
    {
        session(['google_auth_type' => 'register']);
        return $this->getGoogleDriver()->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback(Request $request)
    {
        try {
            $googleUser = $this->getGoogleDriver()->user();
            $authType = session('google_auth_type', 'login');
            session()->forget('google_auth_type');
            
            // Check if email is registered in the system
            $user = DB::connection('auth_db')
                ->table('users')
                ->select('users.*', 'roles.name as role_name', 'subsystem_roles.role_name as subsystem_role_name')
                ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('subsystem_roles', 'users.subsystem_role_id', '=', 'subsystem_roles.id')
                ->where('email', $googleUser->getEmail())
                ->first();

            // Handle registration flow
            if ($authType === 'register') {
                if ($user) {
                    // Email already registered
                    if ($user->status === 'active' && $user->is_email_verified) {
                        return redirect()->route('login')->with('error', 'This Google account is already registered. Please sign in instead.');
                    } elseif ($user->status !== 'active') {
                        return redirect()->route('register')->with('error', 'An account with this email exists but is inactive. Please contact support.');
                    }
                }
                
                // Create user immediately with Google data
                $now = now();
                $userId = DB::connection('auth_db')->table('users')->insertGetId([
                    'username' => $this->generateUsername($googleUser->getName()),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'full_name' => $googleUser->getName(),
                    'password_hash' => Hash::make(\Str::random(32)), // Random password since they'll use Google
                    'status' => 'active',
                    'is_email_verified' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                
                // Assign default citizen role for facility reservation
                $subsystemRole = DB::connection('auth_db')
                    ->table('subsystem_roles')
                    ->where('subsystem_id', 4) // Public Facilities subsystem
                    ->where('role_name', 'Citizen')
                    ->first();
                
                if ($subsystemRole) {
                    DB::connection('auth_db')->table('users')->where('id', $userId)->update([
                        'subsystem_id' => 4,
                        'subsystem_role_id' => $subsystemRole->id
                    ]);
                }
                
                // Log them in immediately
                $user = DB::connection('auth_db')
                    ->table('users')
                    ->where('id', $userId)
                    ->first();
                
                // Create session and redirect to dashboard
                return $this->loginUserAndRedirect($request, $user, true);
            }

            // Handle login flow
            if (!$user || $user->status !== 'active') {
                // Email not registered - redirect back to login with error
                return redirect()->route('login')->with('error', 'This Google account is not registered in our system. Please register first or use an account that is already registered.');
            }

            // Check if email is verified
            if (!$user->is_email_verified) {
                return redirect()->route('login')->with('error', 'Please verify your email before logging in.');
            }

            // Update google_id if not set
            if (empty($user->google_id)) {
                DB::connection('auth_db')
                    ->table('users')
                    ->where('id', $user->id)
                    ->update([
                        'google_id' => $googleUser->getId(),
                        'updated_at' => now()
                    ]);
            }

            return $this->loginUserAndRedirect($request, $user, false);

        } catch (\Exception $e) {
            \Log::error('Google OAuth error: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine() . ' | Trace: ' . $e->getTraceAsString());
            
            // Check if this was a registration attempt
            $authType = session('google_auth_type', 'login');
            session()->forget('google_auth_type');
            
            if ($authType === 'register') {
                return redirect()->route('register')->with('error', 'Unable to sign up with Google. Please try again. Error: ' . $e->getMessage());
            }
            
            return redirect()->route('login')->with('error', 'Unable to login with Google. Please try again or use email/password. Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique username from full name
     */
    private function generateUsername($fullName)
    {
        $base = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $fullName));
        $username = $base;
        $counter = 1;
        
        while (DB::connection('auth_db')->table('users')->where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }
        
        return $username;
    }

    /**
     * Login user and redirect to appropriate dashboard
     */
    private function loginUserAndRedirect(Request $request, $user, $isNewRegistration = false)
    {
        // Get location info
        $location = ['country' => 'Unknown', 'city' => 'Unknown'];
        try {
            $response = \Illuminate\Support\Facades\Http::withOptions(['verify' => false])
                ->get('http://ip-api.com/json/' . $request->ip());
            if ($response->successful()) {
                $data = $response->json();
                $location = [
                    'country' => $data['country'] ?? 'Unknown',
                    'city' => $data['city'] ?? 'Unknown',
                ];
            }
        } catch (\Exception $e) {}

        // Log login history
        DB::connection('auth_db')->table('login_history')->insert([
            'user_id' => $user->id,
            'device_name' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'country' => $location['country'],
            'city' => $location['city'],
            'status' => 'success',
            'required_2fa' => false,
            'attempted_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Delete existing session with same session_id to avoid duplicate error
        $currentSessionId = session()->getId();
        DB::connection('auth_db')->table('user_sessions')
            ->where('session_id', $currentSessionId)
            ->delete();

        // Create user session
        DB::connection('auth_db')->table('user_sessions')->insert([
            'user_id' => $user->id,
            'session_id' => $currentSessionId,
            'device_name' => $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'country' => $location['country'],
            'city' => $location['city'],
            'logged_in_at' => now(),
            'last_active_at' => now(),
            'expires_at' => now()->addMinutes(120),
            'is_current' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get user with roles for role determination
        $userWithRoles = DB::connection('auth_db')
            ->table('users')
            ->select('users.*', 'roles.name as role_name', 'subsystem_roles.role_name as subsystem_role_name')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('subsystem_roles', 'users.subsystem_role_id', '=', 'subsystem_roles.id')
            ->where('users.id', $user->id)
            ->first();

        // Store user info in session
        session([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_name' => $user->full_name,
            'user_role' => $userWithRoles->role_name ?? $userWithRoles->subsystem_role_name ?? 'citizen',
        ]);

        // Determine redirect URL based on role
        $redirectUrl = route('citizen.dashboard'); // default

        if ($userWithRoles->role_name === 'super admin') {
            $redirectUrl = route('superadmin.dashboard');
        } elseif ($userWithRoles->subsystem_role_name === 'Admin') {
            $redirectUrl = route('admin.dashboard');
        } elseif ($userWithRoles->subsystem_role_name === 'Reservations Staff') {
            $redirectUrl = route('staff.dashboard');
        } elseif ($userWithRoles->subsystem_role_name === 'Treasurer') {
            $redirectUrl = route('treasurer.dashboard');
        } elseif ($userWithRoles->subsystem_role_name === 'CBD Staff') {
            $redirectUrl = route('cbd.dashboard');
        }

        return redirect($redirectUrl);
    }
}
