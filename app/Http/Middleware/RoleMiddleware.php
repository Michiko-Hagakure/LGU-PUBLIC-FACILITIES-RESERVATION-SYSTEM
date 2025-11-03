<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Enforces that the authenticated user has the specified role.
     *
     * @param Request $request
     * @param Closure(Request): Response $next
     * @param string $role The required role ('admin', 'staff', 'citizen').
     * @return Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Redirect to the main login route if not authenticated
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has the required role
        if ($user->role !== $role) {
            
            // Centralized logic to determine the appropriate redirect path and error message
            $redirectDetails = $this->getRoleMismatchRedirect($user->role, $role);
            
            // Redirect to the determined dashboard with the appropriate error message
            return redirect()
                ->route($redirectDetails['route'])
                ->with('error', $redirectDetails['message']);
        }

        return $next($request);
    }

    /**
     * Determine the correct redirect route and message when the user role does not match the required role.
     * * @param string $userRole The authenticated user's actual role.
     * @param string $requiredRole The role required by the route.
     * @return array Contains 'route' (named route) and 'message' (error string).
     */
    private function getRoleMismatchRedirect(string $userRole, string $requiredRole): array
    {
        $message = 'Access denied. Insufficient privileges.';
        $route = 'home'; // Fallback route

        if ($userRole === 'citizen') {
            // Citizen trying to access non-citizen route
            $message = 'Access denied. You do not have administrative privileges.';
            $route = 'citizen.dashboard';
        } elseif ($userRole === 'admin') {
            // Admin trying to access staff or citizen route
            $route = 'admin.dashboard';
            $targetPortal = $requiredRole === 'staff' ? 'staff' : 'citizen';
            $message = "Access denied. Please use the admin portal, not the {$targetPortal} portal.";
        } elseif ($userRole === 'staff') {
            // Staff trying to access admin or citizen route
            if ($requiredRole === 'admin') {
                $message = 'Access denied. You do not have administrative privileges.';
                $route = 'staff.dashboard';
            } elseif ($requiredRole === 'citizen') {
                $message = 'Access denied. Please use the staff portal.';
                $route = 'staff.dashboard';
            }
        }
        
        // If all else fails and we don't have a clear redirect, just abort.
        if ($route === 'home') {
             abort(403, $message);
        }

        return [
            'route' => $route,
            'message' => $message,
        ];
    }
}