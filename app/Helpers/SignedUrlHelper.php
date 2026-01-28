<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;

class SignedUrlHelper
{
    /**
     * Generate a signed URL for admin routes.
     * URLs include an encrypted signature that prevents tampering.
     *
     * @param string $routeName The route name
     * @param array $parameters Route parameters
     * @param \DateTimeInterface|\DateInterval|int|null $expiration Optional expiration
     * @return string
     */
    public static function signedRoute(string $routeName, array $parameters = [], $expiration = null): string
    {
        if ($expiration) {
            return URL::temporarySignedRoute($routeName, $expiration, $parameters);
        }
        
        return URL::signedRoute($routeName, $parameters);
    }

    /**
     * Generate a signed URL for admin routes with a default 24-hour expiration.
     *
     * @param string $routeName The route name
     * @param array $parameters Route parameters
     * @return string
     */
    public static function temporarySignedRoute(string $routeName, array $parameters = []): string
    {
        return URL::temporarySignedRoute($routeName, now()->addHours(24), $parameters);
    }
}
