<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ValidateSignedAdminUrl
{
    /**
     * Routes that should be excluded from signed URL validation
     * (e.g., AJAX endpoints that are called frequently)
     */
    protected array $excludedRoutes = [
        'admin.calendar.events',
        'admin.dashboard.quick-stats',
    ];

    /**
     * Handle an incoming request.
     * Validates that admin URLs have a valid signature.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip validation for excluded routes
        $currentRoute = $request->route()?->getName();
        if ($currentRoute && in_array($currentRoute, $this->excludedRoutes)) {
            return $next($request);
        }

        // Validate the signed URL
        if (!$request->hasValidSignature()) {
            // If no signature or invalid, redirect to a signed version
            // This allows bookmarked/direct URLs to still work by redirecting
            if (!$request->has('signature')) {
                $signedUrl = URL::signedRoute($currentRoute, $request->route()->parameters());
                return redirect($signedUrl);
            }
            
            // Invalid signature - security violation
            abort(403, 'Invalid or expired URL signature.');
        }

        return $next($request);
    }
}
