<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SsoAuthMiddleware
{
    /**
     * Handle an incoming request, attempting to authenticate via Laravel Auth or SSO parameters.
     *
     * @param Request $request
     * @param Closure(Request): Response $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Step 1: Already authenticated? Proceed.
        if (Auth::check()) {
            Log::info('SSO Middleware: User already authenticated', ['user_id' => Auth::id()]);
            return $next($request);
        }

        // Step 2: Check if SSO parameters exist (user coming from SSO login/redirect)
        if ($request->has('user_id') || $request->has('username') || $request->has('email')) {
            $userId = $request->input('user_id');
            $username = $request->input('username');
            $email = $request->input('email');

            Log::info('SSO Middleware: SSO params detected', [
                'user_id' => $userId,
                'username' => $username,
                'email' => $email
            ]);

            // Attempt to find user by external_id, ID, email, or username (name field)
            $user = User::where(function ($query) use ($userId, $email, $username) {
                if ($userId) {
                    $query->orWhere('id', $userId)->orWhere('external_id', $userId);
                }
                if ($email) {
                    $query->orWhere('email', $email);
                }
                if ($username) {
                    $query->orWhere('name', $username);
                }
            })->first();

            if ($user) {
                // Log the user in and regenerate session for security
                Auth::login($user, true);
                $request->session()->regenerate();

                Log::info('SSO Middleware: Authenticated via SSO', [
                    'user_id' => $user->id,
                    'role' => $user->role
                ]);

                // Redirect based on role to prevent looping back to the login page
                $redirectRoute = match ($user->role) {
                    'citizen' => route('citizen.dashboard'),
                    'admin' => route('admin.dashboard'),
                    'staff' => route('staff.dashboard'), // Added staff redirect
                    default => '/',
                };
                
                return redirect($redirectRoute);
            }

            Log::warning('SSO Middleware: No user found for SSO params');
            // Redirect to external login with an error flag
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=user_not_found');
        }

        // Step 3: If not authenticated and no SSO params, redirect to SSO login ONCE
        Log::info('SSO Middleware: Not authenticated, redirecting to external SSO login');
        return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?redirect=' . urlencode($request->fullUrl()));
    }
}