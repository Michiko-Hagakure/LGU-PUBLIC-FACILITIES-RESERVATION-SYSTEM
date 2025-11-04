<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom Middleware to check for Admin/Staff Authentication Status.
 * * This middleware ensures that the authenticated user is currently logged in 
 * using the 'admin' guard and redirects them to the login page if not.
 * * Note: If the request is AJAX, it returns a 401 Unauthorized response.
 */
class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Define the authentication guard used for administrators/staff
        $guard = 'admin';

        // --- 1. Check Authentication Status ---
        if (!Auth::guard($guard)->check()) {
            
            // --- 2. Handle Unauthorized Access ---

            // Check if the request expects a JSON response (e.g., AJAX calls)
            if ($request->expectsJson()) {
                // Return a JSON response with 401 Unauthorized status
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Unauthorized. Please login.',
                ], 401);
            }

            // If it's a standard web request, redirect the user to the login page.
            // The redirection target is defined by the 'admin.login' route name.
            return redirect()->route('admin.login')
                             ->with('error', 'Please login to access the Admin Dashboard.');
        }

        // --- 3. Continue Request ---
        // If the admin/staff is authenticated, proceed with the request.
        return $next($request);
    }
}